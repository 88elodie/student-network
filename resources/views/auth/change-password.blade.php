@extends('layouts.app')
@section('title', 'Change password')
@section('content')
<main class="change-password-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 pt-4">
                <div class="card">
                    <h3 class="card-header text-center">New password</h3>
                    <div class="card-body">
                        @if(session('success'))
                        <h4 class="text-success">{{session('success')}}</h4>
                        @endif
                        @if($errors)
                        @foreach($errors->all() as $error)
                        <h4 class="text-danger">{{$error}}</h4>
                        @endforeach
                        @endif
                        <form method="POST" action="">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="password" placeholder="Current password" id="current_password" class="form-control" name="current_password" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" placeholder="New password" id="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" placeholder="Confirm new password" class="form-control" name="password_confirmation" required>
                            </div>
                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">Change password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection