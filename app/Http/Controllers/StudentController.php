<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::when($request->q, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%')
                ->orWhere('email', 'like', '%' . $request->q . '%')
                ->orWhere('student_id', 'like', '%' . $request->q . '%');
        })->orderBy('name')->paginate(15)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'student_id' => 'nullable|string|max:50|unique:students,student_id',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'student_id' => 'nullable|string|max:50|unique:students,student_id,' . $student->id,
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->borrows()->whereHas('outstandingItems')->exists()) {
            return back()->with('error', 'Cannot delete student with active (unreturned) borrows.');
        }
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted.');
    }
}
