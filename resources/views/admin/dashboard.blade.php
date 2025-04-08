@extends('layouts.app')

@section('content')
<div class="w-full p-4">
    <div class="w-full bg-gray-100 rounded-lg shadow-lg p-6 mb-4">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-semibold p-2">Data User</h2>
        <a href="{{ route('admin.Createuser') }}" class="bg-green-800 text-white p-2 rounded">Create User</a>
    </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">User</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">No. Rek</th>
                        <th class="px-4 py-2 text-left">Terbuat</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->role->name }}</td>
                            <td class="px-4 py-2">{{ $user->wallet->wallet_number ?? '-'}}</td>
                            <td class="px-4 py-2">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-2 flex justify-center items-center">
                                <a href="{{  route('admin.Edituser', $user)  }}" class="bg-blue-800 text-white p-2 rounded">Edit</a>

                                <form action="{{ route('admin.deleteuser', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ml-2 bg-red-800 text-white p-2 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-2">{{ $transaction->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-2">{{ $transaction->user->name }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 rounded-md text-white {{ $transaction->status === 'approved' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
