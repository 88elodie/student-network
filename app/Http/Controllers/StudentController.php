<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\City;
use App\Models\User;
use App\Models\Kaomoji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
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

        $students = Student::select()
        ->rightJoin('cities', 'students.city_id', '=', 'cities.city_id')
        ->orderBy('name', 'ASC')
        ->paginate(18);

        return view('student.index', ['students' => $students]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        $cities = City::select()->get();

        return view('student.create', ['cities' => $cities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        $student = Student::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'DOB' => $request->DOB,
            'city_id' => $request->city_id
        ]);
        return redirect()->route('student.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        //$student = Student::find($student)
        //->join('cities', 'students.city_id', '=', 'cities.city_id');
        $user = User::select()
        ->where('student_id', '=', $student->id)
        ->first();

        $back = url()->current();
        Session::put('back2', $back);

        return view('student.show', ['student' => $student, 'user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        $cities = City::select()->get();
        return view('student.edit', ['student' => $student, 'cities' => $cities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        $student->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'DOB' => $request->DOB,
            'city_id' => $request->city_id
            ]);
            return redirect()->route('student.show', $student->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        if(session('name') != 'Admin'){
            return redirect(route('dashboard'))->withErrors('You do not have access permission to that page.');
        }

        $student->delete();
        return redirect()->route('student.index');
    }
}
