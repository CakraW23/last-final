@extends('layouts.app')

@section('content')
<div class="w-full flex justify-center overflow-hidden">
    <div class="bg-white p-8 w-full max-w-md flex flex-col items-center rounded-lg shadow-lg mb-24 overflow-hidden">
        <h1 class="text-2xl font-semibold mb-4">Tarik Tunai</h1>

        <form action="{{ route('client.withdraw') }}" method="POST" class="flex flex-col w-full space-y-4">
            @csrf
            <div>
                <label for="amount" class="block mb-1">Jumlah Saldo</label>
                <input type="number" id="amount" name="amount" class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" placeholder="Masukkan Jumlah Top up">
            </div>

            <div>
                <label for="description" class="block mb-1">Keterangan <span class="text-blue-400 text-xs">*opsional</span></label>
                <input type="text" id="description" name="description" class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" placeholder="Masukkan Keterangan">
            </div>

            <button type="submit" class="mt-4 px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700 transition">Tarik Tunai</button>
        </form>

        @if (session('success_withdraw'))
        <div class="bg-green-200 border border-green-500 p-3 rounded-lg text-green-900 w-full mt-3">
            {{ session('success_withdraw') }}
        </div>
        @elseif (session('error_withdraw'))
        <div class="bg-red-200 border border-red-500 p-3 rounded-lg text-red-900 w-full mt-3">
            {{ session('error_withdraw') }}
        </div>
        @endif
    </div>
</div>
@endsection
