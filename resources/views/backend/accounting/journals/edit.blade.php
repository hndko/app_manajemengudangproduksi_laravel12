@extends('layouts.app')

@section('title', 'Edit Jurnal')
@section('header', 'Edit Jurnal')
@section('subheader', $journal->number)

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('journals.update', $journal) }}" method="POST" x-data="journalForm()" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <div class="card">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Header Jurnal
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date" class="form-label">Tanggal <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <input type="date" id="date" name="date" value="{{ old('date', $journal->date->format('Y-m-d')) }}" class="form-input pl-9 @error('date') border-danger-500 @enderror" required>
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @error('date')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">No. Jurnal</label>
                    <div class="form-input bg-dark-card/50 text-neutral-400">{{ $journal->number }}</div>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="form-label">Deskripsi <span class="text-danger-400">*</span></label>
                    <div class="relative">
                        <textarea id="description" name="description" rows="2" class="form-input pl-9 @error('description') border-danger-500 @enderror" placeholder="Deskripsi transaksi" required>{{ old('description', $journal->description) }}</textarea>
                        <svg class="w-4 h-4 absolute left-3 top-3 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    @error('description')<p class="text-xs text-danger-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Detail Jurnal
                </h3>
                <button type="button" @click="addRow()" class="btn btn-secondary text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Baris
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-dark-border">
                            <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2 w-1/3">Akun</th>
                            <th class="text-left text-xs font-medium text-neutral-500 uppercase py-2 px-2">Keterangan</th>
                            <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2 w-32">Debit</th>
                            <th class="text-right text-xs font-medium text-neutral-500 uppercase py-2 px-2 w-32">Kredit</th>
                            <th class="w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="border-b border-dark-border">
                                <td class="py-2 px-2">
                                    <select :name="`details[${index}][account_id]`" x-model="row.account_id" class="form-select text-sm" required>
                                        <option value="">Pilih akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 px-2">
                                    <input type="text" :name="`details[${index}][description]`" x-model="row.description" class="form-input text-sm" placeholder="Keterangan">
                                </td>
                                <td class="py-2 px-2">
                                    <input type="number" :name="`details[${index}][debit]`" x-model.number="row.debit" @input="row.credit = 0" class="form-input text-sm text-right" min="0" step="0.01" placeholder="0">
                                </td>
                                <td class="py-2 px-2">
                                    <input type="number" :name="`details[${index}][credit]`" x-model.number="row.credit" @input="row.debit = 0" class="form-input text-sm text-right" min="0" step="0.01" placeholder="0">
                                </td>
                                <td class="py-2 px-2">
                                    <button type="button" @click="removeRow(index)" x-show="rows.length > 2" class="p-1.5 rounded-lg hover:bg-dark-border transition-colors text-danger-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr class="bg-dark-card">
                            <td colspan="2" class="py-3 px-2 text-right text-sm font-semibold text-white">Total</td>
                            <td class="py-3 px-2 text-right text-sm font-bold text-white" x-text="formatCurrency(totalDebit)"></td>
                            <td class="py-3 px-2 text-right text-sm font-bold text-white" x-text="formatCurrency(totalCredit)"></td>
                            <td></td>
                        </tr>
                        <tr x-show="!isBalanced" class="bg-danger-500/10">
                            <td colspan="5" class="py-2 px-2 text-center text-xs text-danger-400">
                                ⚠️ Jurnal tidak balance. Selisih: <span x-text="formatCurrency(Math.abs(totalDebit - totalCredit))"></span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary" :disabled="!isBalanced">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Jurnal
            </button>
            <a href="{{ route('journals.show', $journal) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function journalForm() {
    return {
        rows: {!! json_encode($journal->details->map(fn($d) => [
            'account_id' => $d->account_id,
            'description' => $d->description,
            'debit' => $d->debit,
            'credit' => $d->credit,
        ])) !!},

        addRow() {
            this.rows.push({ account_id: '', description: '', debit: 0, credit: 0 });
        },

        removeRow(index) {
            if (this.rows.length > 2) {
                this.rows.splice(index, 1);
            }
        },

        get totalDebit() {
            return this.rows.reduce((sum, row) => sum + (parseFloat(row.debit) || 0), 0);
        },

        get totalCredit() {
            return this.rows.reduce((sum, row) => sum + (parseFloat(row.credit) || 0), 0);
        },

        get isBalanced() {
            return this.totalDebit > 0 && this.totalDebit === this.totalCredit;
        },

        formatCurrency(value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        }
    }
}
</script>
@endpush
@endsection
