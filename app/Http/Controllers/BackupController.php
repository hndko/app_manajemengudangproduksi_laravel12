<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('creator')->latest()->paginate(20);
        return view('settings.backups.index', compact('backups'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|in:full,database,files',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $type = $request->type;
            $filename = 'backup_' . $type . '_' . now()->format('Y-m-d_H-i-s') . '.sql';
            $path = 'backups/' . $filename;

            // Create backup record
            $backup = Backup::create([
                'filename' => $filename,
                'path' => $path,
                'type' => $type,
                'status' => 'pending',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            // For database backup, use mysqldump
            if (in_array($type, ['full', 'database'])) {
                $dbHost = config('database.connections.mysql.host');
                $dbName = config('database.connections.mysql.database');
                $dbUser = config('database.connections.mysql.username');
                $dbPass = config('database.connections.mysql.password');

                $backupPath = storage_path('app/public/' . $path);

                // Ensure directory exists
                if (!file_exists(dirname($backupPath))) {
                    mkdir(dirname($backupPath), 0755, true);
                }

                // Create SQL backup
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s %s > %s',
                    escapeshellarg($dbUser),
                    escapeshellarg($dbPass),
                    escapeshellarg($dbHost),
                    escapeshellarg($dbName),
                    escapeshellarg($backupPath)
                );

                exec($command, $output, $returnVar);

                if ($returnVar === 0 && file_exists($backupPath)) {
                    $backup->update([
                        'status' => 'completed',
                        'size' => filesize($backupPath),
                    ]);

                    ActivityLog::log('create', "Membuat backup {$filename}", $backup);

                    return redirect()->route('backups.index')
                        ->with('success', 'Backup berhasil dibuat');
                }
            }

            $backup->update(['status' => 'failed']);
            return back()->with('error', 'Gagal membuat backup');

        } catch (\Exception $e) {
            if (isset($backup)) {
                $backup->update(['status' => 'failed']);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function download(Backup $backup)
    {
        $path = storage_path('app/public/' . $backup->path);

        if (!file_exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan');
        }

        return response()->download($path, $backup->filename);
    }

    public function destroy(Backup $backup)
    {
        $path = storage_path('app/public/' . $backup->path);

        if (file_exists($path)) {
            unlink($path);
        }

        ActivityLog::log('delete', "Menghapus backup {$backup->filename}", $backup);
        $backup->delete();

        return redirect()->route('backups.index')
            ->with('success', 'Backup berhasil dihapus');
    }
}
