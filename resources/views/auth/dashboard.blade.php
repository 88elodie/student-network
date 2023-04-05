@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
<h3 class="text-center p-4 display-5 mt-5">Hello {{ $name }}  {{ $kaomoji->text }}</h3>
@if($errors)
    @foreach($errors->all() as $error)
    <p class='text-danger text-center'>{{ $error }}</p>
    @endforeach
@endif
<p class="text-center text-muted">What would you like to do ?</p>
</div>
@guest
<div class="container-fluid ">
    <div class="row m-3 justify-content-center">
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('auth.index')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-door-open"></i>
                Login
            </div>
            </a>
        </div>
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('auth.create')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-pen"></i>
                Register
            </div>
            </a>
        </div>
</div>
@else
@if(Session::get('name') === 'Admin')
<div class="container-fluid ">
    <div class="row m-3 justify-content-center">
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('user.index')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-person-gear"></i>
                Manage users
            </div>
            </a>
        </div>
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('student.index')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-people"></i>
                Student List
            </div>
            </a>
        </div>
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('student.create')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-person-plus"></i>
                Add student
            </div>
            </a>
        </div>
</div>
@else
<div class="container-fluid ">
    <div class="row m-3 justify-content-center">
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('blog.create')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-plus-square"></i>Create a post
            </div>
            </a>
        </div>
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('blog.showuser', Auth::user()->id)}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-card-list"></i>View my posts
            </div>
            </a>
        </div>
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('document.create')}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-file-earmark-plus"></i>Upload a document
            </div>
            </a>
        </div>

        <div class="w-100"></div>

        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{route('document.userindex', Auth::user()->id)}}">
            <div class="card p-3 pt-5">
                <i class="bi bi-folder"></i>View my documents
            </div>
            </a>
        </div>
        <div class="col-sm-2 mt-3">
            <a class="text-decoration-none text-reset" href="{{ route('user.show', Auth::user()->id) }}">
            <div class="card p-3 pt-5">
                <i class="bi bi-person"></i>View my profile
            </div>
            </a>
        </div>
    </div>
</div>
@endif
@endguest
@endsection