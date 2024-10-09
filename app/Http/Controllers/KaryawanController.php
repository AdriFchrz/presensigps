<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    public function index(Request $request){

        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept');
        $query->join('department', 'karyawan.kode_dept', '=', 'department.kode_dept');
        $query->orderBy('nama_lengkap');
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_lengkap', 'like', '%'.$request->nama_karyawan.'%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $karyawan = $query->get();

        $department = DB::table('department')->get();
        return view('karyawan.index', compact('karyawan', 'department'));
    }

    public function store(Request $request) {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('123');

        if($request->hasFile('foto'))
        {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto'))
                {
                $folderPath = "uploads/karyawan"; // Jalur penyimpanan file
                $request->file('foto')->storeAs($folderPath, $foto, 'public');
                }
                return Redirect::back()->with(['success' => 'Data berhasil disimpan']);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan NIK " . $nik . " Sudah ada";
            }
            return Redirect::back()->with(['warning' => 'Data gagal disimpan' . $message]);
        }
    }

    public function edit(Request $request){
        $nik = $request->nik;
        $department = DB::table('department')->get();
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.edit', compact('department', 'karyawan'));
    }

    public function update($nik, Request $request) {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('123');
        $old_foto = $request->old_foto;

        if($request->hasFile('foto'))
        {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);
            if ($update) {
                if ($request->hasFile('foto'))
                {
                $folderPath = "uploads/karyawan"; // Jalur penyimpanan file
                $folderPathOld = "uploads/karyawan".$old_foto; // Jalur penyimpanan file
                Storage::delete($folderPathOld);
                $request->file('foto')->storeAs($folderPath, $foto, 'public');
                }
                return Redirect::back()->with(['success' => 'Data berhasil di Update']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data gagal di Update']);
        }
    }

    public function delete($nik) {
        $delete = DB::table('karyawan')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data berhasil di Hapus']);
        } else {
            return Redirect::back()->with(['waning' => 'Data berhasil di Hapus']);
        }
    }

}
