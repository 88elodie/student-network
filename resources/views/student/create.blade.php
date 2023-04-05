@extends('../layouts/app')
@section('title', 'Add student')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
<div class="card">
<form  method="post">
                        @csrf
                        <div class="card-header">
                            <h2>Add a student</h2>
                        </div>
                        <div class="card-body">   
                                <div class="control-group col-12">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                <div class="control-group col-12">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" class="form-control" required>
                                </div>
                                <div class="control-group col-12">
                                    <label for="phone">Phone</label>
                                    <input type="text" id="phone" name="phone" class="form-control" required>
                                </div>
                                <div class="control-group col-12">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" name="email" class="form-control" required>
                                </div>
                                <div class="control-group col-12">
                                    <label for="DOB">Date of birth</label>
                                    <input type="date" id="DOB" name="DOB" class="form-control" required>
                                </div>
                                    <label for="city_id">City</label>
                                    <select class="form-select" name="city_id" id="city_id" required>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" value="Add student">
                            <button class="btn btn-secondary"><a href="{{ route('student.index') }}">Cancel</a></button>
                        </div>
                    </form>
                </div>
                </div></div>
            </div>
            

@endsection