@extends('layout.admin.tabler')
@section('content')
<div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                  Data Master
                </div>
                <h2 class="page-title">
                  Karyawan
                </h2>
              </div>
            </div>
          </div>
        </div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnTambahKaryawan">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M16 19h6" /><path d="M19 16v6" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /></svg>
                                    Create Data
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/karyawan" method="GET" >
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="kode_dept" id="kode_dept" class="form-select">
                                                    <option value="">All Department</option>
                                                    @foreach ($department as $d)
                                                        <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-device-tablet-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.5 21h-5.5a1 1 0 0 1 -1 -1v-16a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v7" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                                                Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nik</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No HP</th>
                                        <th>Foto</th>
                                        <th>Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($karyawan as $d)
                                    @php
                                        $path = $d->foto ? Storage::url('uploads/karyawan/'.$d->foto) : asset('assets/img/nophoto.jpg');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->nik }}</td>
                                        <td>{{ $d->nama_lengkap }}</td>
                                        <td>{{ $d->jabatan }}</td>
                                        <td>{{ $d->no_hp }}</td>
                                        <td>
                                            <img src="{{ $path }}" class="avatar" alt="">  
                                        </td>
                                        <td>{{ $d->nama_dept }}</td>
                                        <td>
                                            <div class="btn-group">
                                            <a href="#" class="edit btn btn-primary btn-sm" nik="{{ $d->nik }}">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                            <form action="/karyawan/{{ $d->nik }}/delete" method="POST" style="margin-left:5px">
                                                @csrf
                                                <a class="btn btn-danger btn-sm delete-confirm">
                                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
                                            </a>
                                            </form>
                                            </div>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create Data Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('karyawan.store') }}" method="POST" id="frmKaryawan" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-world-code"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.942 13.02a9 9 0 1 0 -9.47 7.964" /><path d="M3.6 9h16.8" /><path d="M3.6 15h9.9" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3c2 3.206 2.837 6.913 2.508 10.537" /><path d="M20 21l2 -2l-2 -2" /><path d="M17 17l-2 2l2 2" /></svg>
                            </span>
                            <input type="text" value="" id="nik" class="form-control" name="nik" placeholder="NIK">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                            </span>
                            <input type="text" value="" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-gavel"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 10l7.383 7.418c.823 .82 .823 2.148 0 2.967a2.11 2.11 0 0 1 -2.976 0l-7.407 -7.385" /><path d="M6 9l4 4" /><path d="M13 10l-4 -4" /><path d="M3 21h7" /><path d="M6.793 15.793l-3.586 -3.586a1 1 0 0 1 0 -1.414l2.293 -2.293l.5 .5l3 -3l-.5 -.5l2.293 -2.293a1 1 0 0 1 1.414 0l3.586 3.586a1 1 0 0 1 0 1.414l-2.293 2.293l-.5 -.5l-3 3l.5 .5l-2.293 2.293a1 1 0 0 1 -1.414 0z" /></svg>
                            </span>
                            <input type="text" value="" id="jabatan" class="form-control" name="jabatan" placeholder="Jabatan">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                            </span>
                            <input type="text" value="" id="no_hp" class="form-control" name="no_hp" placeholder="No HP">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <select name="kode_dept" id="kode_dept" class="form-select">
                        <option value="">All Department</option>
                        @foreach ($department as $d)
                            <option {{ Request('kode_dept')==$d->kode_dept ? 'selected' : '' }} value="{{ $d->kode_dept }}">{{ $d->nama_dept }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-upload"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11v6" /><path d="M9.5 13.5l2.5 -2.5l2.5 2.5" /></svg>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

    <div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Data Karyawan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="loadeditform">

          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnTambahKaryawan").click(function() {
            $("#modal-inputkaryawan").modal("show");
        });

        $(".edit").click(function() {
            var nik = $(this).attr('nik');
            $.ajax({
                type:'POST',
                url: '/karyawan/edit',
                cache: false,
                data:{
                    _token: "{{ csrf_token(); }}",
                    nik: nik
                },
                success:function(respond){
                    $("#loadeditform").html(respond);
                }
            });
            $("#modal-editkaryawan").modal("show");
        });

        $(".delete-confirm").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You are about to delete this data permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire("Deleted!", "The data has been successfully deleted.", "success");
                }
            });
        });

        $("#frmKaryawan").submit(function(){
            var nik = $("#nik").val();
            var nama_lengkap = $("nama_lengkap").val();
            var jabatan = $("#jabatan").val();
            var no_hp = $("#no_hp").val();
            var kode_dept = $("frmKaryawan").find("#kode_dept").val();
            if (nik == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Kolom NIK harus terisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=> {
                    $('#nik').focus();
                });
                return false;
            } else if (nama_lengkap == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Kolom Nama karyawan harus terisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=> {
                    $('#nama_lengkap').focus();
                });
                return false;
            } else if (jabatan == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Kolom Jabatan karyawan harus terisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=> {
                    $('#jabatan').focus();
                });
                return false;
            } else if (no_hp == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Kolom Nomor Handphone harus terisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=> {
                    $('#no_hp').focus();
                });
                return false;
            } else if (kode_dept == "") {
                Swal.fire({
                title: 'Warning!',
                text: 'Kolom Department harus terisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
                }).then((result)=> {
                    $('#kode_dept').focus();
                });
                return false;
            }
        });
    });
</script>
@endpush