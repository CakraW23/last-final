@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col">
        <div class="w-full flex flex-col lg:flex-row gap-4 min-h-screen mb-4 mt-4">
            {{-- Kartu Transaksi (Top Up dari Bank) --}}
            <div class="w-full lg:w-1/3 bg-white rounded-lg shadow-lg p-5">
                <div>
                    <h1 class="text-2xl font-semibold mb-1">Top Up User</h1>
                    <form action="{{ route('bank.topup') }}" method="POST" class="space-y-2">
                        @csrf
                        <div>
                            <label class="">Nomor Rekening</label>
                            <input type="number" name="wallet_number" required
                                class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                                placeholder="Masukkan Nomor Rekening">
                        </div>
                        <div>
                            <label class="">Jumlah</label>
                            <input type="number" name="amount" required
                                class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                                placeholder="Masukkan Jumlah">
                        </div>
                        <div>
                            <label class="">Deskripsi</label>
                            <input type="text" name="description"
                                class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                                placeholder="Opsional">
                        </div>
                        <button type="submit"
                            class="w-full bg-gray-900 hover:bg-gray-700 text-white py-2 rounded-md transition">Kirim Top
                            Up</button>
                    </form>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold mt-3">Tarik Tunai User</h1>
                    <form action="{{ route('bank.withdraw') }}" method="POST" class="space-y-2">
                        @csrf
                        <div>
                            <label class="">Nomor Rekening</label>
                            <input type="number" name="wallet_number" required
                                class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                                placeholder="Masukkan Nomor Rekening">
                        </div>
                        <div>
                            <label class="">Jumlah</label>
                            <input type="number" name="amount" required
                                class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                                placeholder="Masukkan Jumlah">
                        </div>
                        <div>
                            <label class="">Deskripsi</label>
                            <input type="text" name="description"
                                class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                                placeholder="Opsional">
                        </div>
                        <button type="submit"
                            class="w-full bg-gray-900 hover:bg-gray-700 text-white py-2 rounded-md transition">Tarik
                            Tunai</button>
                    </form>
                </div>
            </div>

            {{-- Tabel Request Transaksi --}}
            <div class="w-full lg:w-2/3 bg-gray-100 rounded-lg shadow-lg p-6">
                <div class="flex justify-between mb-2">
                    <h2 class="text-xl font-semibold">Request Transaksi</h2>
                    <a href="{{ route('bank.Createuser') }}" class="p-2 bg-green-400 hover:bg-green-600 rounded">Tambah user</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">User</th>
                                <th class="px-4 py-2 text-left">Nomor Rekening</th>
                                <th class="px-4 py-2 text-left">Jumlah</th>
                                <th class="px-4 py-2 text-left">Deskripsi</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-2">{{ $transaction->user->name }}</td>
                                    <td class="px-4 py-2">{{ $transaction->wallet->wallet_number }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $transaction->descriptions ?? '-' }}</td>
                                    <td class="px-4 py-2 flex">
                                        <form action="{{ route('bank.approve', $transaction->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                class="border border-green-600 text-green-600 hover:bg-green-600 hover:text-white px-3 py-1 rounded-md mx-1 transition duration-150">Approve</button>
                                        </form>

                                        <form action="{{ route('bank.reject', $transaction->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <button
                                                class="border border-red-600 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1 rounded-md transition duration-150">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel History Transaksi --}}
        </div>
        <div class="w-full bg-gray-100 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Riwayat Transaksi</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Jumlah</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @foreach ($histories as $history)
                            <tr>
                                <td class="px-4 py-2">{{ $history->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $history->user->name }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($history->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 rounded-md text-white {{ $history->status === 'approved' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                        {{ ucfirst($history->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- tabel client --}}
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">User</th>
                        <th class="px-4 py-2 text-left">No. Rek</th>
                        <th class="px-4 py-2 text-left">Terbuat</th>
                        {{-- <th class="px-4 py-2 text-center">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="px-4 py-2">{{ $client->name }}</td>
                            <td class="px-4 py-2">{{ $client->wallet->wallet_number ?? '-'}}</td>
                            <td class="px-4 py-2">{{ $client->created_at->format('d M Y') }}</td>
                            {{-- <td class="px-4 py-2 flex justify-center items-center">
                                <a href="{{  route('admin.Edituser', $user)  }}" class="bg-blue-800 text-white p-2 rounded">Edit</a>

                                <form action="{{ route('admin.deleteuser', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 bg-red-800 text-white p-2 rounded">Delete</button>
                                </form>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
