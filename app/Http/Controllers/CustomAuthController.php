<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\City;
use App\Models\Kaomoji;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CustomAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }

        return view('auth.index');
    }

    public function authentication(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20'
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::validate($credentials)) :
            return redirect()->route('auth.index')
                ->withErrors('These credentials do not match our records.');
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user, $request->get('remember'));
        $name = Auth::user()->name;
        $id = Auth::user()->id;
        Session::put('name', $name);
        Session::put('id', $id);

        return redirect()->intended('dashboard')->withSuccess('Signed in');
    }

    public function dashboard()
    {
        $name = 'Guest';
        $kaomoji = Kaomoji::inRandomOrder()->first();

        if (Auth::check()) {
            $name = Auth::user()->name;
        }

        return view('auth.dashboard', ['name' => $name, 'kaomoji' => $kaomoji]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect(route('auth.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }

        $students = Student::select()->get();
        return view('auth.create', ['students' => $students]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users|max:30',
            'student_id' => 'required|numeric|unique:users|exists:students,id',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:20'
        ]);

        $user = new User;
        $user->fill($request->all());
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect(route('auth.index'))->withSuccess('Your account was successfully created !');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function tempPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        if (User::where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->get();
            $user = $user[0];
            $userId = $user->id;
            $tempPass = str::random(25);
            $user->temp_password = $tempPass;
            $user->save();
            $to_name = $user->name;
            $to_email = $request->email;
            $body = "<a href='http://localhost:8000/new-password/" . $userId . "/" . $tempPass .
                "'>Click here to reset your password</a>";
            Mail::send(
                'email.mail',
                $data = [
                    'name' => $to_name,
                    'body' => $body
                ],
                function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->subject(
                        'Password reset'
                    );
                }
            );
            return redirect()->back()->withSuccess(
                'An email to reset your password was sent to you.'
            );
        }
        return redirect()->back()->withErrors('This user doesn\'t exist ');
    }

    public function newPassword(User $user, $tempPassword)
    {
        if ($user->temp_password === $tempPassword) {
            return view('auth.new-password');
        }
        return redirect('forgot-password')->withErrors(
            'These credentials do not match our records.'
        );
    }

    public function storeNewPassword(User $user, $tempPassword, Request $request)
    {
        //'i forgot my password' password change
        if ($user->temp_password === $tempPassword) {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);
            $user->password = Hash::make($request->password);
            $user->temp_password = NULL;
            $user->save();
            return redirect('login')->withSuccess(
                'Password successfully changed.'
            );
        }
        return redirect('forgot-password')->withErrors(
            'These credentials do not match our records.'
        );
    }

    public function changePassword(User $user)
    {
        if (Auth::user()->id != $user->id && Auth::user()->name != 'Admin') {
            return redirect(route('dashboard'))->withErrors('You do not have the permissions to do this action.');
        }

        return view('auth.change-password');
    }

    public function storePassword(User $user, Request $request)
    {
        if (Auth::user()->id != $user->id && Auth::user()->name != 'Admin') {
            return redirect(route('dashboard'))->withErrors('You do not have the permissions to do this action.');
        }

        //signed in password change

        $request->validate([
            'current_password' => 'current_password',
            'password' => 'required|min:6|confirmed',
        ]);

        if($request->password === $request->current_password){
            return redirect('change-password/'.$user->id)->withErrors(
                'Please choose a different password than your current one.'
            );
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('user/' . $user->id)->withSuccess(
            'Password successfully changed.'
        );
    }
}
