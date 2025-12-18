<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Consumer;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    public function index(Request $request)
    {
        $query = Consumer::query();

        // Filter by search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $data = [
            'consumers' => $query->latest()->paginate(15)->withQueryString(),
            'filters' => $request->only(['search', 'is_active']),
        ];

        return view('backend.master-data.consumers.index', $data);
    }

    public function create()
    {
        $data = [
            'lastCode' => Consumer::generateCode(),
        ];

        return view('backend.master-data.consumers.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:consumers,code|max:50',
            'name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'npwp' => 'nullable|max:30',
            'contact_person' => 'nullable|max:255',
        ]);

        $consumerData = $request->only([
            'code', 'name', 'phone', 'email',
            'address', 'npwp', 'contact_person'
        ]);
        $consumerData['is_active'] = $request->boolean('is_active', true);

        $consumer = Consumer::create($consumerData);
        ActivityLog::log('create', "Menambah konsumen {$consumer->name}", $consumer);

        return redirect()->route('consumers.index')
            ->with('success', 'Konsumen berhasil ditambahkan');
    }

    public function show(Consumer $consumer)
    {
        $data = [
            'consumer' => $consumer->load(['salesTransactions' => fn($q) => $q->latest()->take(10)]),
        ];

        return view('backend.master-data.consumers.show', $data);
    }

    public function edit(Consumer $consumer)
    {
        $data = [
            'consumer' => $consumer,
        ];

        return view('backend.master-data.consumers.edit', $data);
    }

    public function update(Request $request, Consumer $consumer)
    {
        $request->validate([
            'code' => 'required|max:50|unique:consumers,code,' . $consumer->id,
            'name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'npwp' => 'nullable|max:30',
            'contact_person' => 'nullable|max:255',
        ]);

        $consumerData = $request->only([
            'code', 'name', 'phone', 'email',
            'address', 'npwp', 'contact_person'
        ]);
        $consumerData['is_active'] = $request->boolean('is_active', true);

        $consumer->update($consumerData);
        ActivityLog::log('update', "Mengubah konsumen {$consumer->name}", $consumer);

        return redirect()->route('consumers.index')
            ->with('success', 'Konsumen berhasil diperbarui');
    }

    public function destroy(Consumer $consumer)
    {
        ActivityLog::log('delete', "Menghapus konsumen {$consumer->name}", $consumer);
        $consumer->delete();

        return redirect()->route('consumers.index')
            ->with('success', 'Konsumen berhasil dihapus');
    }
}
