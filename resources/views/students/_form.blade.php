@php($student = $student ?? null)

<div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
    <div>
        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
        <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $student->student_id ?? '') }}"
            placeholder="e.g. STU-1234"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('student_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $student->email ?? '') }}"
            placeholder="student@example.com"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('first_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name <span class="text-gray-400">(optional)</span></label>
        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $student->middle_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('middle_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('last_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-gray-400">(optional)</span></label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone ?? '') }}"
            placeholder="e.g. +1 555 000 1234"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
        <select name="gender" id="gender"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
            <option value="">Select gender</option>
            @foreach (['Male', 'Female', 'Other'] as $gender)
                <option value="{{ $gender }}" @selected(old('gender', $student->gender ?? '') === $gender)>{{ $gender }}</option>
            @endforeach
        </select>
        @error('gender')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', isset($student->date_of_birth) ? $student->date_of_birth->toDateString() : '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('date_of_birth')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <input type="text" name="address" id="address" value="{{ old('address', $student->address ?? '') }}"
            placeholder="Street, City, State"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('address')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
        <select name="course_id" id="course_id"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
            <option value="">Select course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $student->course_id ?? '') == $course->id)>
                    {{ $course->course_code }} &mdash; {{ $course->course_name }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="year_level" class="block text-sm font-medium text-gray-700">Year Level</label>
        <select name="year_level" id="year_level"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
            <option value="">Select year level</option>
            @foreach (['1st Year', '2nd Year', '3rd Year', '4th Year'] as $yearLevel)
                <option value="{{ $yearLevel }}" @selected(old('year_level', $student->year_level ?? '') === $yearLevel)>{{ $yearLevel }}</option>
            @endforeach
        </select>
        @error('year_level')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
            <option value="active" @selected(old('status', $student->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $student->status ?? 'active') === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>