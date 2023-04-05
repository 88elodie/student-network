@extends('../layouts/app')
@section('title', 'User list')
@section('content')

<h2 class="display-6 m-3">User List</h2>
<div class="container-fluid">
    <div class="row m-3">
        @forelse($users as $user)
        @if($user->name === 'Admin')
        @else
        <div class="col-sm-2">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <h6 class="card-subtitle text-muted">joined {{ $user->created_at }}</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('user.show', $user->id) }}" class="card-link">View</a></li>
                    <li class="list-group-item"><a href="{{ route('user.edit', $user->id) }}" class="card-link">Edit</a></li>
                </ul>
            </div>
        </div>
        @endif
        @empty
        @endforelse
    </div>
</div>
<div class="m-5">
{{ $users }}
</div>
@endsection