@php($grade = $grade ?? null)
@php($preselectedStudentId = $preselectedStudentId ?? 0)

<div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
    <div class="sm:col-span-2 rounded-md bg-accent-50 px-4 py-3 text-sm text-accent-700">
        You can only record grades for students assigned to your classes in the selected term.
    </div>

    <div>
        <label for="student_id" class="block text-sm font-medium text-slate-700">Student</label>
        <select name="student_id" id="student_id"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <option value="">Select student</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected((int) old('student_id', $grade->student_id ?? $preselectedStudentId) === $student->id)>
                    {{ $student->full_name }} &middot; {{ $student->student_id }}
                </option>
            @endforeach
        </select>
        @error('student_id')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="subject_id" class="block text-sm font-medium text-slate-700">Subject</label>
        <select name="subject_id" id="subject_id"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <option value="">Select subject</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}" @selected((int) old('subject_id', $grade->subject_id ?? '') === $subject->id)>
                    {{ $subject->subject_code }} &mdash; {{ $subject->subject_name }}
                </option>
            @endforeach
        </select>
        @error('subject_id')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="grade" class="block text-sm font-medium text-slate-700">Grade</label>
        <input type="number" name="grade" id="grade" value="{{ old('grade', $grade->grade ?? '') }}" step="0.25" min="1" max="5"
            placeholder="e.g. 1.50"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        <p class="mt-1 text-sm text-slate-500">Grading scale 1.00 (highest) &ndash; 5.00 (failing), in increments of 0.25.</p>
        @error('grade')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="semester" class="block text-sm font-medium text-slate-700">Semester</label>
        <select name="semester" id="semester"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @foreach ($semesters as $semester)
                <option value="{{ $semester }}" @selected(old('semester', $grade->semester ?? '') === $semester)>{{ $semester }}</option>
            @endforeach
        </select>
        @error('semester')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="academic_year" class="block text-sm font-medium text-slate-700">Academic Year</label>
        <select name="academic_year" id="academic_year"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @foreach ($academicYears as $year)
                <option value="{{ $year }}" @selected(old('academic_year', $grade->academic_year ?? '') === $year)>{{ $year }}</option>
            @endforeach
        </select>
        @error('academic_year')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="remarks" class="block text-sm font-medium text-slate-700">Remarks <span class="text-slate-400">(optional)</span></label>
        <input type="text" name="remarks" id="remarks" value="{{ old('remarks', $grade->remarks ?? '') }}"
            placeholder="e.g. Outstanding performance"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('remarks')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>