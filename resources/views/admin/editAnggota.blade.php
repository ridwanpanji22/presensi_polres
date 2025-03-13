@extends('admin.layout.dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->

        <div class="card shadow mb-4 col-6 mx-auto">
            <div class=" py-3">
                <h2 class="m-0 font-weight-bold text-primary text-center">Edit Anggota</h2>
            </div>
            <div class="card-body">
                <form class="user" action="{{ route('admin.updateAnggota', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nama </label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" value="{{ $user->name }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="position">Jabatan</label>
                        <input type="text" class="form-control" id="position" name="position" placeholder="Masukkan Jabatan"value="{{ $user->position }}" required>
                    </div>
                    <div class="form-group">
                        <label for="rank">Pangkat</label>
                        <input type="text" class="form-control" id="rank" name="rank" placeholder="Masukkan Pangkat" value="{{ $user->rank }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nrp">NRP</label>
                        <input type="nrp" class="form-control" id="nrp" name="nrp" placeholder="Masukkan NRP" value="{{ $user->nrp }}" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="" disabled><b>Pilih Role</b></option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Isi Password Jika Ingin Diubah">
                    </div>
                        <button type="submit" class="btn btn-primary">Edit</button>
                        <a href="/admin/anggota" class="btn btn-danger">Cancel</a>
                </form>
            </div>
        </div>

    </div>
@endsection