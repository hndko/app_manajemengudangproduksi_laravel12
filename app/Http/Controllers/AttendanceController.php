<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')
            ->when(!auth()->user()->isAdmin(), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest('date')
            ->paginate(20);

        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        return view('attendances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha,cuti',
            'notes' => 'nullable|string|max:500',
        ]);

        Attendance::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => now()->toDateString()],
            $request->only(['clock_in', 'clock_out', 'status', 'notes'])
        );

        return redirect()->route('attendances.index')
            ->with('success', 'Absensi berhasil disimpan');
    }

    public function show(Attendance $attendance)
    {
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,alpha,cuti',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance->update($request->only(['clock_in', 'clock_out', 'status', 'notes']));

        return redirect()->route('attendances.index')
            ->with('success', 'Absensi berhasil diperbarui');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')
            ->with('success', 'Absensi berhasil dihapus');
    }
}
