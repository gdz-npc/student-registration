<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Student::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|string|max:30|unique:students,student_id',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:students,email',
            'course'     => 'required|string|max:100',
            'year_level' => 'required|integer',
        ]);

        $student = Student::create($data);

        return response()->json([
            'message' => 'Student registered successfully',
            'data'    => $student
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json($student, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'student_id' => 'sometimes|required|string|max:30|unique:students,student_id,' . $student->id,
            'first_name' => 'sometimes|required|string|max:100',
            'last_name'  => 'sometimes|required|string|max:100',
            'email'      => 'sometimes|required|email|unique:students,email,' . $student->id,
            'course'     => 'sometimes|required|string|max:100',
            'year_level' => 'sometimes|required|integer',
        ]);

        $student->update($data);

        return response()->json([
            'message' => 'Student updated successfully',
            'data'    => $student
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully'
        ], 200);
    }
}