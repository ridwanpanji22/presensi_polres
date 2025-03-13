@extends('admin.layout.dashboard')

@section('content')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Absensi {{ $user->name }}</h1>
        </div>

        <div class="mb-2">
            <button href="#" id="buatPerizinan" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                </span>
                <span class="text">Buat Perizinan</span>
            </button>
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
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($attendance as $att)
                            <tr>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->check_in ? $att->check_in : '-' }}</td>
                                <td>{{ $att->check_out ? $att->check_out : '-' }}</td>
                                <td>{{ $att->status }}</td>
                                <td>{{ $att->description ? $att->description : '-' }}</td>
                                {{-- tombol hapus dengan modal konfirmasi sweetalert --}}
                                <td class="text-center">
                                    <button class="btn btn-danger btn-icon-split" onclick="deleteAttendance({{ $att->id }})">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash fa-sm text-white-50"></i>
                                        </span>
                                        <span class="text">Hapus</span>
                                    </button>
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
    {{-- Modal Knonfirmasi Hapus --}}
    <script>
        function deleteAttendance(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data absensi akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('admin.hapusAbsensi') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data absensi.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    });
                }
            });
        }
    </script>

    <!-- Buat Perizinan Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buatPerizinanButton = document.getElementById('buatPerizinan');
            const userId = '{{ $user->id }}';

            if (buatPerizinanButton) {
                buatPerizinanButton.addEventListener('click', function(event) {
                    event.preventDefault();

                    // Step 1: Pilih Status (Sakit/Izin)
                    Swal.fire({
                        title: 'Pilih Status',
                        input: 'select',
                        inputOptions: {
                            'sakit': 'Sakit',
                            'izin': 'Izin'
                        },
                        inputPlaceholder: 'Pilih status perizinan',
                        showCancelButton: true,
                        confirmButtonText: 'Selanjutnya',
                        cancelButtonText: 'Batal',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Status harus dipilih!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const status = result.value;

                            // Step 2: Pilih Tanggal
                            Swal.fire({
                                title: 'Pilih Tanggal',
                                input: 'date',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'Tanggal harus dipilih!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const date = result.value;

                                    // Step 3: Masukkan Keterangan
                                    Swal.fire({
                                        title: 'Masukkan Keterangan',
                                        input: 'textarea',
                                        inputPlaceholder: 'Tulis keterangan perizinan',
                                        inputValidator: (value) => {
                                            if (!value) {
                                                return 'Keterangan harus diisi!';
                                            }
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const description = result.value;

                                            // Kirim request perizinan ke server
                                            fetch('{{ route('admin.buatPerizinan') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    status: status,
                                                    date: date,
                                                    description: description,
                                                    user_id: userId
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Berhasil!',
                                                        text: data.message,
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    }).then(() => {
                                                        location.reload();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Gagal!',
                                                        text: data.message,
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                }
                                            })
                                            .catch(error => {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal!',
                                                    text: 'Terjadi kesalahan saat membuat perizinan.',
                                                    showConfirmButton: false,
                                                    timer: 2000
                                                });
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>

@endsection