@php
    $typeColors = [
        'aset' => 'primary',
        'liabilitas' => 'danger',
        'ekuitas' => 'secondary',
        'pendapatan' => 'success',
        'beban' => 'warning',
    ];
@endphp

<tr class="hover:bg-dark-card/50">
    <td class="py-3 px-2">
        <span class="text-sm font-mono font-medium text-white" style="padding-left: {{ $level * 20 }}px">
            {{ $account->code }}
        </span>
    </td>
    <td class="py-3 px-2">
        <span class="text-sm text-white" style="padding-left: {{ $level * 20 }}px">
            @if($account->children->count() > 0)
                <svg class="w-3.5 h-3.5 inline mr-1 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
            @endif
            {{ $account->name }}
        </span>
    </td>
    <td class="py-3 px-2">
        <span class="badge badge-{{ $typeColors[$account->type] ?? 'neutral' }}">{{ ucfirst($account->type) }}</span>
    </td>
    <td class="py-3 px-2">
        <span class="text-sm text-neutral-300">{{ ucfirst($account->normal_balance) }}</span>
    </td>
    <td class="py-3 px-2">
        @if($account->is_active)
            <span class="badge badge-success">Aktif</span>
        @else
            <span class="badge badge-danger">Nonaktif</span>
        @endif
    </td>
    <td class="py-3 px-2 text-right">
        <div class="flex items-center justify-end gap-1">
            <a href="{{ route('chart-of-accounts.edit', $account) }}" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-warning-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            @if(!$account->is_locked && !$account->journalDetails()->exists())
            <form action="{{ route('chart-of-accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-danger-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
            @endif
        </div>
    </td>
</tr>

@foreach($account->children as $child)
    @include('backend.accounting.chart-of-accounts._account-row', ['account' => $child, 'level' => $level + 1])
@endforeach
