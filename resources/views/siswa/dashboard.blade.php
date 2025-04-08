@extends('layouts.app')

@section('content')
<div class="flex flex-row w-full h-screen p-4">
    {{-- div kiri --}}
    <div class="w-full flex flex-col p-2">
        {{-- kotak kiri atas || div rekening --}}
        <div class="flex flex-col bg-gray-900 text-gray-300 rounded-lg shadow-xl p-6 h-52">
            <p class="text-xl">Nomor Rekening {{ $user->wallet->wallet_number ?? '-' }}</p>
            <h1 class="text-lg mt-4">Saldo anda</h1>
            <h1 class="text-3xl font-bold">Rp. {{ number_format((float)$user->wallet->balance ?? '-') }}</h1>
        </div>

        {{-- kotak kiri bawah || div transaksi --}}
        <div class="flex flex-col border border-gray-400 rounded-lg shadow-xl h-96 p-6 mt-4">
            <h1 class="text-3xl font-semibold mb-3">Transaksi</h1>
            <a href="{{ route('client.topup.page') }}" class="border border-gray-900 p-6 text-gray-900 hover:bg-gray-900 hover:text-white rounded-lg m-1 text-center transition duration-200">Top Up</a>
            <a href="{{ route('client.withdraw.page') }}" class="border border-gray-900 p-6 text-gray-900 hover:bg-gray-900 hover:text-white rounded-lg m-1 text-center transition duration-200">Withdraw</a>
            <a href="{{ route('client.transfer.page') }}" class="border border-gray-900 p-6 text-gray-900 hover:bg-gray-900 hover:text-white rounded-lg m-1 text-center transition duration-200">Transfer</a>
            <p class="m-3 text-sm">*Beberapa Transaksi membutuhkan persetujuan Admin</p>
        </div>
    </div>

    {{-- div kanan --}}
    <div class="w-full border border-gray-400 m-2 rounded-xl p-4">
        <h1 class="text-2xl font-semibold">Transaksi Terkini</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-2 py-2 text-left">Tgl</th>
                        <th class="px-2 py-2 text-left">User</th>
                        <th class=" py-2 text-left">Type</th>
                        <th class="px-2 py-2 text-left">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($histories as $history)
                    <tr>
                       <td class="px-2">
                        <div class="flex flex-col">
                        <span class="text-lg font-semibold">{{ $history->created_at->format('d') }}</span>
                        <span>{{ $history->created_at->format('M') }}</span>
                        </div>
                        </td>
                        <td class="px-2 py-2">{{ $history->user->name }}</td>
                        <td class=" py-2">{{ $history->type }}</td>
                        <td class="px-2 py-2">Rp {{ number_format($history->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
