<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // skip jika kosong
        if (!$row['username'] || !$row['name']) {
            return null;
        }

        // cek username unik
        if (User::where('username', $row['username'])->exists()) {
            return null;
        }

        // 🔥 auto assign leader
        $leader_id = null;

        if (Auth::user()->role == 'leader') {
            $leader_id = Auth::id();
        } else {
            // jika admin → ambil dari excel
            $leader = User::where('name', $row['leader'])->first();
            $leader_id = $leader ? $leader->id : null;
        }

        return new User([
            'name' => $row['name'],
            'username' => $row['username'],
            'password' => Hash::make($row['password'] ?? '123456'),
            'role' => $row['role'] ?? 'user',
            'leader_id' => $leader_id
        ]);
    }
}
