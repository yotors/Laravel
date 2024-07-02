<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->validate([
            'name'=>['required'],
            'email'=>['required','email','unique:users,email'],
            'password'=>['required','confirmed'],
        ]);
        $user = User::create($user);
        $employer = $request->validate([
            'employer'=>['required'],
            'logo'=>['required','image'],
        ]);
        $logopath = $request->logo->store('logos');
        
        $user->employer()->create([
            'name'=>$employer['employer'],
            'logo'=>$logopath,
        ]);
        Auth::login($user);
        return redirect('/');
    }
}
