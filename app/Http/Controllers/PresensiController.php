<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser= $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $image = $request->image;
        $folderPath = "public/uploads/absensi";
        $formatName = $nik . "-" . $tgl_presensi;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        //melakukan pengecekan absensi
        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
            if($radius > 100){
                return response()->json(['status' => "error|Maaf, Anda berada di luar radius absensi. Jarak anda adalah ".$radius." meter dari kantor |"]);
            } else {
                if ($cek > $lok_kantor->radius) {
                    //presensi pulang
                    $fileName = $formatName . "-out.png";
                    $file = $folderPath . $fileName;
                    $data_pulang = [
                        'jam_out' => $jam, 
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    //update table untuk jam pulang
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if ($update) {
                        Storage::put($file, $image_base64);
                        return response()->json(['status' => "success|Terimakasih, hati-hati di jalan!|out"]);
                    } else {
                        return response()->json(['status' => "error|Maaf, gagal absen!|out"]);
                    }
                } else {
                    //presensi masuk
                    $fileName = $formatName . "-in.png";
                    $file = $folderPath . $fileName;
                    $data = [
                        'nik' => $nik, 
                        'tgl_presensi' => $tgl_presensi, 
                        'jam_in' => $jam, 
                        'jam_out' => null, 
                        'foto_in' => $fileName, 
                        'foto_out' => null,
                        'lokasi_in' => $lokasi,
                        'lokasi_out' => null
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        Storage::put($file, $image_base64);
                        return response()->json(['status' => "success|Selamat Bekerja!|in"]);
                    } else {
                        return response()->json(['status' => "error|Maaf, gagal absen!|in"]);
                    }
                }
            }
    }
    
    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile() 
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request){
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        if($request->hasFile('foto'))
        {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($request->password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }
        
        // Melakukan update ke database
        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            // Menyimpan file foto baru jika ada
            if ($request->hasFile('foto'))
            {
                $folderPath = "uploads/karyawan"; // Jalur penyimpanan file
                $request->file('foto')->storeAs($folderPath, $foto, 'public');
            }
            return Redirect::back()->with(['success' => 'Data anda berhasil di update!']);
        } else {
            return Redirect::back()->with(['error' => 'Data anda gagal di update!']);
        }
    }

    public function histori() {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", 
        "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request) {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
        ->whereRaw('MONTH(tgl_presensi)="' .$bulan.'"')
        ->whereRaw('YEAR(tgl_presensi)="' .$tahun.'"')
        ->where('nik', $nik)
        ->orderBy('tgl_presensi')
        ->get();
        
        return view('presensi.gethistori', compact('histori'));
    }

    public function izin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin =  $request->tgl_izin;
        $status =  $request->status;
        $keterangan =  $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if($simpan){
            return redirect('/presensi/izin')->with(['success'=>'Data berhasil disimpan!']);
        } else {
            return redirect('/presensi/izin')->with(['error'=>'Data gagal disimpan!']);
        }
    }
}
