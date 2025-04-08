@extends('layouts.app')

@section('content')
<div class="w-full flex justify-center">
    <div class="bg-white p-8 w-full max-w-md flex flex-col items-center rounded-lg shadow-lg mb-24">
        <h1 class="text-2xl font-semibold mb-4">Tambah User</h1>

        <form action="{{ route('bank.createuser') }}" method="POST" class="flex flex-col w-full space-y-4">
            @csrf
            <div>
                <label for="name" class="block mb-1">Nama</label>
                <input type="text" id="name" name="name" class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" placeholder="Masukkan Nama">
            </div>

            <div>
                <label for="email" class="block mb-1">Email</label>
                <input type="text" id="email" name="email" class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" placeholder="Masukkan Email">
            </div>

            <div>
                <label for="password" class="block mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" placeholder="Masukkan Password">
            </div>

            <label for="role" class="block">Role</label>
            <Select id="role_id" name="role_id" class="w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200 m-0">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </Select>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Buat User</button>
        </form>
    </div>
</div>
@endsection
