<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', $today)->first();

        $totalMasuk = Attendance::where('user_id', $user->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->where('status', 'masuk')
            ->count();

        $totalIzin = Attendance::where('user_id', $user->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->where('status', 'izin')
            ->count();

        $totalSakit = Attendance::where('user_id', $user->id)
            ->whereYear('date', now()->year)
            ->whereMonth('date', now()->month)
            ->where('status', 'sakit')
            ->count();

        // Mengambil data ketidakhadiran
        $ketidakhadiran = Attendance::where('user_id', $user->id)
            ->where(function ($query) {
            $query->where('status', 'izin')
                  ->orWhere('status', 'sakit');
            })
            ->get();

        return view('user.index', compact('attendance', 'totalMasuk', 'totalIzin', 'totalSakit', 'ketidakhadiran'));
    }

    public function absenDatang(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', $today)->first();

        if ($attendance) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen datang hari ini.']);
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => now()->toTimeString(),
            'status' => 'masuk',
        ]);
        return response()->json(['success' => true, 'message' => 'Absen datang berhasil!']);
    }

    public function absenPulang(Request $request)
    {
        $user = Auth::user();
        $today = now()->toDateString();
        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', $today)->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan absen datang hari ini.']);
        }

        if ($attendance->check_out) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen pulang hari ini.']);
        }

        $attendance->update([
            'check_out' => now()->toTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Absen pulang berhasil!']);
    }

    public function buatPerizinan(Request $request)
    {
        $request->validate([
            'status' => 'required|in:sakit,izin',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        $user = Auth::user();

        Attendance::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Perizinan berhasil dibuat!']);
    }
}
