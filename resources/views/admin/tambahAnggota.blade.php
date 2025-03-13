@extends('admin.layout.dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->

        <div class="card shadow mb-4 col-6 mx-auto">
            <div class=" py-3">
                <h2 class="m-0 font-weight-bold text-primary text-center">Tambah Anggota</h2>
            </div>
            <div class="card-body">
                <form class="user" action="{{ route('admin.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama </label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" {{ old('name') }} required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="position">Jabatan</label>
                        <input type="text" class="form-control" id="position" name="position" placeholder="Masukkan Jabatan" {{ old('position') }} required>
                    </div>
                    <div class="form-group">
                        <label for="rank">Pangkat</label>
                        <input type="text" class="form-control" id="rank" name="rank" placeholder="Masukkan Pangkat" {{ old('rank') }} required>
                    </div>
                    <div class="form-group">
                        <label for="nrp">NRP</label>
                        <input type="nrp" class="form-control" id="nrp" name="nrp" placeholder="Masukkan NRP" {{ old('nrp') }} required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" {{ old('role') }} required>
                            <option value="" disabled selected><b>Pilih Role</b></option>
                            <option value="admin">Admin</option>
                            <option value="user">Anggota</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                    </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/admin/anggota" class="btn btn-danger">Cancel</a>
                </form>
            </div>
        </div>

    </div>
@endsection