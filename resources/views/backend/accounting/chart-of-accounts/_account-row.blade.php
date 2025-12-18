<tr>
    <td style="padding-left: {{ $level * 1.5 }}rem">
        <code class="text-sm">{{ $account->code }}</code>
    </td>
    <td style="padding-left: {{ $level * 1.5 }}rem">
        @if($account->children->count())
            <strong>{{ $account->name }}</strong>
        @else
            {{ $account->name }}
        @endif
    </td>
    <td>
        <span class="badge badge-{{ $account->type_color }}">
            {{ $account->type_label }}
        </span>
    </td>
    <td class="capitalize">{{ $account->normal_balance }}</td>
    <td class="text-right">
        Rp {{ number_format($account->balance, 0, ',', '.') }}
    </td>
    <td>
        @unless($account->is_locked)
        <div class="flex items-center gap-2">
            <a href="{{ route('chart-of-accounts.edit', $account) }}" class="text-warning-500 hover:text-warning-600">
                <i data-feather="edit" class="w-4 h-4"></i>
            </a>
            <form action="{{ route('chart-of-accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Yakin hapus akun ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-danger-500 hover:text-danger-600">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
        @endunless
    </td>
</tr>
@foreach($account->children as $child)
    @include('accounting.chart-of-accounts._account-row', ['account' => $child, 'level' => $level + 1])
@endforeach
