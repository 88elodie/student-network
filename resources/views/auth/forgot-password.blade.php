@extends('layouts.app')
@section('title', 'Password recovery')
@section('content')
<main class="frgt-password-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 pt-4">
                <div class="card">
                    <h3 class="card-header text-center">Forgotten password</h3>
                    <div class="card-body">
                        @if(session('success'))
                        <h4 class="text-success">{{session('success')}}</h4>
                        @endif
                        @if($errors)
                        @foreach($errors->all() as $error)
                        <h4 class="text-danger">{{$error}}</h4>
                        @endforeach
                        @endif
                        <form method="POST" action="{{ route('temp.password') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="Your email" id="email" class="form-control" name="email" required autofocus>
                                @if ($errors->has('email'))
                                <span class="text-danger">
                                    {{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">Send a password reset link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection