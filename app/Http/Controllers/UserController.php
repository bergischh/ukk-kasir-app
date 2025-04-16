<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('Success', 'Berhasil menambah data!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::find($id);

        return view('user.edit', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);
    
        $user = User::find($id);
    
        // Hanya update password jika tidak kosong
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:4',
            ]);
            $user->password = Hash::make($request->password);
        }
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
    
        $user->save();
    
        return redirect()->route('user.index')->with('success', 'Data berhasil diperbarui!');
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
    
        // Cegah penghapusan user dengan role admin
        if ($user && $user->role === 'admin') {
            return redirect()->back()->with('Failed', 'User dengan role admin tidak bisa dihapus!');
        }
    
        $user->delete();
    
        return redirect()->back()->with('Success', 'Berhasil menghapus data!');
    }
    

    public function showLogin() {
        return view('login');
    }

    public function submitLogin(Request $request) {
        $credientials = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($credientials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
        }

        return back()->with('Failed', 'Username atau password gagal!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate(); 

        return redirect('/')->with('Success', 'Anda telah berhasil logout');
    }
}
