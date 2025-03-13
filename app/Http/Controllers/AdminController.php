<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $attendance = Attendance::whereDate('date', $today)->get();
        $totalAnggota = User::where('role', 'user')->count();
        $totalMasuk = Attendance::where('status', 'masuk')->whereDate('date', $today)->count();
        $totalIzin = Attendance::where('status', 'izin')->whereDate('date', $today)->count();
        $totalSakit = Attendance::where('status', 'sakit')->whereDate('date', $today)->count();
        return view('admin.index', compact('attendance','totalAnggota', 'totalMasuk', 'totalIzin', 'totalSakit'));
    }

    public function anggota()
    {
        $user = User::orderBy('created_at', 'desc')->get(); //mengurutkan data terakhir yang diinput
        //mengubah data role dari "user" menjadi "Anggota"
        foreach ($user as $u) {
            if ($u->role == 'user') {
                $u->role = 'anggota';
            }
        }
        return view('admin.anggota', compact('user'));
    }

    public function tambahAnggota()
    {
        return view('admin.tambahAnggota');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'rank' => 'required',
            'nrp' => 'required|unique:users',
            'role' => 'required',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('error', 'Data NRP sudah terdaftar!');
        }

        User::create([
            'name' => $request->name,
            'position' => $request->position,
            'rank' => $request->rank,
            'nrp' => $request->nrp,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.anggota')->with('success', 'Data berhasil ditambahkan!');
    }

    public function editAnggota($id)
    {
        $user = User::find($id);
        return view('admin.editAnggota', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'rank' => 'required',
            'nrp' => 'required|unique:users,nrp,' . $id,
            'role' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('error', 'Data NRP sudah terdaftar!');
        }

        $user = User::find($id);

        if ($request->password) {
            $user->update([
                'name' => $request->name,
                'position' => $request->position,
                'rank' => $request->rank,
                'nrp' => $request->nrp,
                'role' => $request->role,
                'password' => bcrypt($request->password),
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'position' => $request->position,
                'rank' => $request->rank,
                'nrp' => $request->nrp,
                'role' => $request->role,
            ]);
        }

        return redirect()->route('admin.anggota')->with('success', 'Data ' . $user->name . ' berhasil diubah!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin.anggota')->with('success', 'Data ' . $user->name . ' berhasil dihapus!');
    }

    public function absensi()
    {
        return view('admin.absensi');
    }

    public function absenAnggota($id)
    {
        $attendance = Attendance::where('user_id', $id)->orderBy('date', 'desc')->get();
        $user = User::find($id);
        return view('admin.absenAnggota', compact('attendance', 'user'));
    }

    public function buatPerizinan(Request $request)
    {
        $request->validate([
            'status' => 'required|in:sakit,izin',
            'date' => 'required|date',
            'description' => 'required|string',
            'user_id' => 'required',
        ]);

        Attendance::create([
            'user_id' => $request->user_id,
            'date' => $request->date,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        //cek apakah tanggal yang dipilih sudah ada data absensi
        $attendance = Attendance::where('user_id', $request->user_id)->where('date', $request->date)->first();
        if ($attendance) {
            return response()->json(['success' => false, 'message' => 'Sudah ada data absensi pada tanggal tersebut!']);
        }

        return response()->json(['success' => true, 'message' => 'Perizinan berhasil dibuat!']);
    }

    //Hapus data absensi
    public function hapusAbsensi(Request $request)
    {
        $attendance = Attendance::find($request->id);
        $attendance->delete();
        return response()->json(['success' => true, 'message' => 'Data absensi berhasil dihapus!']);
    }

}
