@extends('../layouts/app')
@section('title', 'Student list')
@section('content')

<h2 class="display-6 m-3">Student List</h2>
<div class="container-fluid">
    <div class="row m-3">
        @forelse($students as $student)
        <div class="col-sm-2">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $student->name }}</h5>
                    <h6 class="card-subtitle text-muted">{{ $student->DOB }}</h6>
                    <p class="card-text">city : {{ $student->city_name }}</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><a href="{{ route('student.show', $student->id) }}" class="card-link">View</a></li>
                    <li class="list-group-item"><a href="{{ route('student.edit', $student->id) }}" class="card-link">Edit</a></li>
                </ul>
            </div>
        </div>
        @empty
        @endforelse
    </div>
</div>
<div class="m-5">
{{ $students }}
</div>
@endsection