<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        $user = User::with('wallet')->find(Auth::id());
        $histories = Transaction::whereIn('status', ['success', 'rejected'])->get();

        return view('siswa.dashboard', compact('user', 'histories'));
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' =>'required|numeric|min:5000',
            'descriptions' => 'nullable|max:125'
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->first();

        Transaction::create([
            'user_id' => Auth::id(),
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => 'topup',
            'status' => 'pending',
            'descriptions' => $request->descriptions,
        ]);

        return redirect()->back()->with('success_topup', 'Permintaan Sedang Diproses');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' =>'required|numeric|min:5000',
            'descriptions' => 'nullable|max:125'
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->first();

        if ($wallet->balance > $request->amount) {
            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $wallet->id,
                'amount' => $request->amount,
                'type' => 'withdraw',
                'status' => 'pending',
                'descriptions' => $request->descriptions,
            ]);

            return redirect()->back()->with('success_withdraw', 'Berhasil, Permintaan Sedang diproses');
        }else {
            return redirect()->back()->with('error_withdraw', 'Maaf, Saldo Anda Tidak Cukup');
        }
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'wallet_number' => 'required',
            'amount' =>'required|numeric|min:5000',
            'descriptions' => 'nullable|max:125'
        ]);

        $senderWallet = Auth::user()->wallet;
        $receiverWallet = Wallet::where('wallet_number', $request->wallet_number)->first();

        if (!$receiverWallet) {
            return back()->with('error_notfound_wallet', 'Rekening Tidak Ditemukan.');
        }

        if ($senderWallet->id === $receiverWallet->id) {
            return back()->with('error_same_wallet', 'Tidak bisa mengirim ke diri sendiri.');
        }

        if ($senderWallet->balance < $request->amount) {
            return back()->with('error_amount', 'Maaf, Saldo anda tidak cukup.');
        }

        DB::beginTransaction();
        try {
            $senderWallet->decrement('balance', $request->amount);
            $receiverWallet->increment('balance', $request->amount);

            Transaction::create([
                'user_id' => Auth::id(),
                'wallet_id' => $senderWallet->id,
                'receiver_id' => $receiverWallet->user_id,
                'amount' => $request->amount,
                'type' => 'transfer',
                'status' => 'success',
                'descriptions' => $request->descriptions,
            ]);

            DB::commit();
            return back()->with('success_transfer', 'Berhasil Transfer');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error_transfer', 'Gagal Transfer. Silahkan coba kembali.');
        }
    }
}
