<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

public function authenticate(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Check role after successful login
        $user = Auth::user();

        if ($user->role == 0) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->role == 1) {
            return redirect()->intended('/karyawan');
        }

        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:0,1' // opsional, bisa dihapus jika role tidak diinput user
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user' // default role 'user' jika tidak diisi
        ]);

        Auth::login($user);
        return redirect('/register')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

public function user(Request $request)
{
    $query = User::query();

    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $users = $query->paginate(10);

    $users->appends($request->only('search'));

    return view('main.user.index', compact('users'));
}
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'role' => 'required',
        'profile_photo_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    if ($request->hasFile('profile_photo_path')) {
        $file = $request->file('profile_photo_path');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/profile', $filename);
        $user->profile_photo_path = 'profile/' . $filename;
    }

    $user->save();

    return redirect()->route('user')->with('success', 'User berhasil diperbarui.');
}

public function destroy($id)
{
    $user = User::findOrFail($id);

    // Hapus file jika ada
    if ($user->profile_photo_path && Storage::exists('public/' . $user->profile_photo_path)) {
        Storage::delete('public/' . $user->profile_photo_path);
    }

    $user->delete();

    return redirect()->route('user')->with('success', 'User berhasil dihapus.');
}

}