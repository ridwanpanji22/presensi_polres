@extends('admin.layout.dashboard')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Anggota</h1>
            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
        </div>

        <div class="mb-2">
            <a href="/admin/tambahAnggota" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                </span>
                <span class="text">Tambah Anggota</span>
            </a>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Pangkat</th>
                                <th>NRP</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Pangkat</th>
                                <th>NRP</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($user as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->rank }}</td>
                                    <td>{{ $item->nrp }}</td>
                                    <td>{{ $item->role }}</td>
                                    <td class="text-center">
                                        <a href="{{ route ('admin.absenAnggota', $item->id) }}" class="btn-sm btn-success mr-2">
                                            <span class="icon">
                                                <i class="fas fa-eye fa-sm text-black-50">
                                                    Absen
                                                </i>
                                            </span>
                                        </a>
                                        <a href="{{ route('admin.editAnggota', $item->id) }}" class="btn-sm btn-warning mr-2">
                                            <span class="icon ">
                                                <i class="fas fa-edit fa-sm text-black-50">
                                                    Edit
                                                </i>
                                            </span>
                                        </a>
                                        <form id="deleteForm{{ $item->id }}" action="{{ route('admin.deleteAnggota', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $item->id }})" class="btn-sm btn-danger">
                                                <span class="icon ">
                                                    <i class="fas fa-trash fa-sm text-black-50">
                                                        Hapus
                                                    </i>
                                                </span>
                                            </button>
                                        </form>
                                        {{-- <button onclick="deleteAnggota({{ $item->id }})" type="submit" class="btn btn-danger">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash fa-sm text-white-50"></i>
                                            </span>
                                        </button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm' + id);
                    if (form) {
                        form.submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Formulir tidak ditemukan.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        }
    </script>

@endsection