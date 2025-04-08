<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $transactions = Transaction::whereIn('type', ['topup', 'withdraw'])->where('status', 'pending')->get();
        $histories = Transaction::whereIn('status', ['success', 'rejected'])->get();
        // dd($history);
        return view('bank.dashboard', compact('transactions', 'histories'));
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
