<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index()
        {
            if(auth()->user()->role == 'leader'){
            // hanya timnya
            $users = User::where('leader_id', auth()->id())->get();
        }else{
            // admin lihat semua
            $users = User::all();
        }

        return view('admin.users.index', compact('users'));

    }

    public function create()
    {
        // if (Auth::user()->role == 'admin') {
        //     $leaders = User::where('role','leader')->get();
        // } else {
        //     $leaders = collect(); // kosong
        // }

        $leaders = User::where('role','leader')->get();
        return view('admin.users.create', compact('leaders'));
    }

    public function store(Request $request)
    {

        // NORMALISASI
        $username = strtolower(trim($request->username));

        $request->merge([
            'username' => $username
        ]);

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        // SET LEADER
        if (auth()->user()->role == 'leader') {
            // leader hanya bisa buat user timnya
            $leader_id = auth()->id();
            $role = 'user';
        } else {
            // admin bebas
            $leader_id = $request->leader_id;
            $role = $request->role;
        }

        User::create([
            'name' => $request->name,
            'username' => $username,
            'password' => bcrypt($request->password),
            'role' => $role,
            'leader_id' => $leader_id
        ]);

        return redirect('/admin/users')->with('success','User berhasil dibuat');
    }

    public function edit($id) { 
        $user = User::findOrFail($id); 
        
        // leader hanya bisa edit timnya 
        if (auth()->user()->role == 'leader' && $user->leader_id != auth()->id()) 
            { 
                abort(403); 
            }

        if(auth()->user()->role == 'leader')
        { if($user->leader_id != auth()->id())
        { 
            abort(403);} 
        } 
        
        $leaders = User::where('role','leader')->get();
        
        return view('admin.users.edit', compact('user','leaders'));
    }

    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // proteksi leader
    if (auth()->user()->role == 'leader' && $user->leader_id != auth()->id()) {
        abort(403);
    }

    // VALIDASI BERBEDA
    $rules = [
        'name' => 'required',
        'username' => 'required|unique:users,username,'.$user->id,
    ];

    // hanya admin yang wajib kirim role
    if(auth()->user()->role == 'admin'){
        $rules['role'] = 'required';
    }

    $request->validate($rules);

    // NORMALISASI USERNAME
    $username = strtolower(trim($request->username));

    $data = [
        'name' => $request->name,
        'username' => $username,
    ];

    // hanya admin boleh ubah role & leader
    if(auth()->user()->role == 'admin'){
        $data['role'] = $request->role;
        $data['leader_id'] = $request->leader_id;
    }

    // password optional
    if ($request->password) {
        $data['password'] = bcrypt($request->password);
    }

    $user->update($data);

    return redirect('/admin/users')->with('success','User berhasil diupdate');
}

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('success','Import user berhasil');
    }

    public function bulkDelete(Request $request)
    {
        try{
            $ids = json_decode(
                $request->selected_users,
                true
            );
            if(empty($ids)){
                return back()
                    ->with(
                        'error',
                        'Tidak ada user dipilih'
                    );
            }
            User::whereIn('id',$ids)
                ->where('id','!=',auth()->id())
                ->delete();

            User::whereIn('id',$ids)->delete();
            return back()
                ->with(
                    'success',
                    count($ids).' user berhasil dihapus'
                );
            }catch(\Exception $e){
            return back()->with(
                'error',
                'Gagal menghapus user'
            );
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->role == 'leader' && $user->leader_id != auth()->id()) {
            abort(403);
        }

        $user->delete();

        return back()->with('success','User dihapus');
    }
}