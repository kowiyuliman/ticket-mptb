<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'new_password' => ['required','min:6','confirmed'],
        ],[
            'new_passwword.confirmed' => 'Konfirmasi password tidak sama ',
        ]);

        $user = auth()->user();

        // update password
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return back()->with('success','Password berhasil diubah');
    }
    

    
}