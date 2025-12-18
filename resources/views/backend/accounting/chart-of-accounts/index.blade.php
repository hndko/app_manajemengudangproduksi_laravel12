@extends('layouts.app')

@section('title', 'Daftar Akun')
@section('header', 'Daftar Akun (Chart of Accounts)')
@section('subheader', 'Kelola daftar akun untuk akuntansi')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-lg font-semibold text-neutral-800 dark:text-white">Daftar Akun</h3>
        <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">
            <i data-feather="plus" class="w-4 h-4"></i>
            Tambah Akun
        </a>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Akun</th>
                    <th>Tipe</th>
                    <th>Saldo Normal</th>
                    <th>Saldo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                    @include('accounting.chart-of-accounts._account-row', ['account' => $account, 'level' => 0])
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
