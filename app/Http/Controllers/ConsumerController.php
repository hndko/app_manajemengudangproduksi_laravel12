<?php
namespace App\Http\Controllers;
use App\Models\Consumer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ConsumerController extends Controller
{
    public function index() { return view('master-data.consumers.index', ['consumers' => Consumer::latest()->paginate(20)]); }
    public function create() { return view('master-data.consumers.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|max:255', 'email' => 'nullable|email', 'phone' => 'nullable|max:20']);
        $consumer = Consumer::create($request->all());
        ActivityLog::log('create', "Menambah konsumen {$consumer->name}", $consumer);
        return redirect()->route('consumers.index')->with('success', 'Konsumen berhasil ditambahkan');
    }
    public function show(Consumer $consumer) { return view('master-data.consumers.show', compact('consumer')); }
    public function edit(Consumer $consumer) { return view('master-data.consumers.edit', compact('consumer')); }
    public function update(Request $request, Consumer $consumer) {
        $request->validate(['name' => 'required|max:255']);
        $consumer->update($request->all());
        ActivityLog::log('update', "Mengubah konsumen {$consumer->name}", $consumer);
        return redirect()->route('consumers.index')->with('success', 'Konsumen berhasil diperbarui');
    }
    public function destroy(Consumer $consumer) {
        ActivityLog::log('delete', "Menghapus konsumen {$consumer->name}", $consumer);
        $consumer->delete();
        return redirect()->route('consumers.index')->with('success', 'Konsumen berhasil dihapus');
    }
}
