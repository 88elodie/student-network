<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){
            $posts = BlogPost::select()->orderBy('created_at', 'DESC')->paginate(18);
            return view('blog.index', ['posts' => $posts]);
        }

        $back = url()->previous();
        Session::put('back2', $back);

        return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(session('name') === 'Admin'){
            return redirect(route('dashboard'))->withErrors('Admin cannot create posts.');
        }elseif(Auth::check()){
            return view('blog.create', ['user_id' => session('id')]);
        }

        return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(session('name') === 'Admin'){
            return redirect(route('dashboard'))->withErrors('Admin cannot create posts.');
        }elseif(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        $blog = BlogPost::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user_id
        ]);

        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $blogPost)
    {
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        $back = url()->previous();
        Session::put('back2', $back);

        return view('blog.show', ['post' => $blogPost]);
    }

    public function showuser($user)
    {
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        $posts = BlogPost::select()
        ->where('user_id', '=', $user)
        ->orderBy('created_at', 'DESC')
        ->paginate(18);

        $user = User::select()
        ->where('id', '=', $user)
        ->first();

        $back = url()->current();
        Session::put('back2', $back);

        return view('blog.showuser', ['posts' => $posts, 'user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function edit(BlogPost $blogPost)
    {
        if(Auth::user()->id != $blogPost->user_id && Auth::user()->name != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You cannot edit this post.');
        }

        return view('blog.edit', ['post' => $blogPost]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        if(Auth::user()->id != $blogPost->user_id && Auth::user()->name != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You cannot edit this post.');
        }

        $request->validate([
            'title' => 'required|max:100',
            'body' => 'required'
        ]);

        $blogPost->update([
            'title' => $request->title,
            'body' => $request->body
        ]);
            
        return redirect(route('blog.show', $blogPost->id))->withSuccess('Post has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $blogPost)
    {
        if(Auth::user()->id != $blogPost->user_id && Auth::user()->name != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You cannot delete this post.');
        }

        $blogPost->delete();
        return redirect(session('back2'));

    }
}
