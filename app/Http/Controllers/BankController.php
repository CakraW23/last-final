<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $clients = User::where('role_id', 3)->get();
        $transactions = Transaction::whereIn('type', ['topup', 'withdraw'])->where('status', 'pending')->get();
        $histories = Transaction::whereIn('status', ['success', 'rejected'])->get();
        // dd($history);
        return view('bank.dashboard', compact('clients', 'transactions', 'histories'));
    }

    public function createPage()
    {
        $roles = Role::all();
        return view('bank.createuser', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role_id
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'wallet_number' => fake()->unique()->numerify('#########')
        ]);

        return back()->with('success_user', 'Berhasil Menambah User');
    }

    public function topup(Request $request)
    {
        $request->validate([
            'wallet_number' =>'required',
            'amount' =>'required|numeric|min:5000',
            'descriptions' =>'nullable|max:125'
        ]);

        $wallet = Wallet::where('wallet_number', $request->wallet_number)->first();

        if (!$wallet) {
            return back()->with('error_notfound_wallet', 'Nomor Rekening tidak ditemukan' );
        }

        $wallet->increment('balance', $request->amount);

        Transaction::create([
            'user_id' => $wallet->user_id,
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'descriptions' => $request->descriptions,
            'type' => 'topup',
            'status' => 'success',
        ]);

        return back()->with('success_topup', 'Top up berhasil.');
    }

    public function withdraw(Request $request) {
        $request->validate([
            'wallet_number' =>'required',
            'amount' =>'required|numeric|min:5000',
            'descriptions' =>'nullable|max:125'
        ]);

        $wallet = Wallet::where('wallet_number', $request->wallet_number)->first();

        if(!$wallet) {
            return back()->with('error_notfound_wallet', 'Nomor Rekening tidak ditemukan' );
        }

        if($wallet->balance < $request->amount) {
            return back()->with('error_amount', 'Saldo User tidak cukup.');
        }

        Transaction::create([
            'user_id' => $wallet->user_id,
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'descriptions' => $request->descriptions,
            'type' => 'withdraw',
            'status' => 'success'
        ]);

        $wallet->decrement('balance', $request->amount);
        return back()->with('success_withdraw', 'Tarik tunai user berhasil.');
    }

    public function approve($id)
    {
        $transactions = Transaction::find($id);

        $wallet = Wallet::where('user_id', $transactions->user_id)->first();

        if ($transactions->type == 'topup') {
            $wallet->increment('balance', $transactions->amount);
        }
        elseif ($transactions->type == 'withdraw') {
            if ($wallet->balance < $transactions->amount) {
                return back()->with('error_amount', 'Saldo User tidak cukup.');
            }
            $wallet->decrement('balance', $transactions->amount);
        }

        $transactions->update(['status' => 'success']);

        return back()->with('approve', 'Top up berhasil.');
    }

    public function reject($id)
    {
        $transactions = Transaction::find($id);

        if ($transactions->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak bisa di tolak.');
        }
        $transactions->update(['status' => 'rejected']);

        return back()->with('reject', 'Top up di tolak');
    }
}
