@php($schedule = $schedule ?? null)
@php($isTeacher = auth()->user()->isTeacher())

<div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
    @if ($isTeacher)
        <div class="sm:col-span-2 rounded-md bg-accent-50 px-4 py-3 text-sm text-accent-700">
            Schedules you create are automatically assigned to you.
        </div>
    @endif

    <div>
        <label for="student_id" class="block text-sm font-medium text-slate-700">Student</label>
        <select name="student_id" id="student_id"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <option value="">Select student</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected((int) old('student_id', $schedule->student_id ?? '') === $student->id)>
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
                <option value="{{ $subject->id }}" @selected((int) old('subject_id', $schedule->subject_id ?? '') === $subject->id)>
                    {{ $subject->subject_code }} &mdash; {{ $subject->subject_name }}
                </option>
            @endforeach
        </select>
        @error('subject_id')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if (! $isTeacher)
        <div>
            <label for="teacher_id" class="block text-sm font-medium text-slate-700">Teacher</label>
            <select name="teacher_id" id="teacher_id"
                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
                <option value="">Select teacher</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected((int) old('teacher_id', $schedule->teacher_id ?? '') === $teacher->id)>
                        {{ $teacher->full_name }}
                    </option>
                @endforeach
            </select>
            @error('teacher_id')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div>
        <label for="room" class="block text-sm font-medium text-slate-700">Room <span class="text-slate-400">(optional)</span></label>
        <input type="text" name="room" id="room" value="{{ old('room', $schedule->room ?? '') }}"
            placeholder="e.g. Room 204"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('room')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="day" class="block text-sm font-medium text-slate-700">Day</label>
        <select name="day" id="day"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <option value="">Select day</option>
            @foreach ($days as $day)
                <option value="{{ $day->value }}" @selected(old('day', $schedule->day ?? '') === $day->value)>{{ $day->value }}</option>
            @endforeach
        </select>
        @error('day')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="start_time" class="block text-sm font-medium text-slate-700">Start Time</label>
        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $schedule->start_time ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('start_time')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="end_time" class="block text-sm font-medium text-slate-700">End Time</label>
        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $schedule->end_time ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('end_time')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="semester" class="block text-sm font-medium text-slate-700">Semester</label>
        <select name="semester" id="semester"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @foreach ($semesters as $semester)
                <option value="{{ $semester }}" @selected(old('semester', $schedule->semester ?? '') === $semester)>{{ $semester }}</option>
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
                <option value="{{ $year }}" @selected(old('academic_year', $schedule->academic_year ?? '') === $year)>{{ $year }}</option>
            @endforeach
        </select>
        @error('academic_year')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>