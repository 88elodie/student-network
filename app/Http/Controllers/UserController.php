<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        $users = User::select()
        ->orderBy('name', 'ASC')
        ->paginate(18);

        $back = url()->current();
        Session::put('back2', $back);

        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        return view('user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(Auth::user()->id != $user->id && Auth::user()->name != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have the permissions to do this action.');
        }

        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if(Auth::user()->id != $user->id && Auth::user()->name != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have the permissions to do this action.');
        }

        $request->validate([
            'name' => 'required|max:30|'. Rule::unique('users')->ignore($user->id),
            'email' => 'required|email|'. Rule::unique('users')->ignore($user->id)
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $name = $request->name;
        Session::put('name', $name);
            
        return redirect(route('user.show', $user->id))->withSuccess('Profile has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Auth::user()->name != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have the permissions to do this action');
        }

        $posts = BlogPost::select()
        ->where('user_id', '=', $user->id)
        ->delete();

        $user->delete();
        return redirect(session('back2'));
    }
}
