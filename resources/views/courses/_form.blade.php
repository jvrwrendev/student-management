@php($course = $course ?? null)

<div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
    <div>
        <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
        <input type="text" name="course_code" id="course_code" value="{{ old('course_code', $course->course_code ?? '') }}"
            placeholder="e.g. BSIT"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('course_code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
        <input type="text" name="course_name" id="course_name" value="{{ old('course_name', $course->course_name ?? '') }}"
            placeholder="e.g. Bachelor of Science in Information Technology"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
        @error('course_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-gray-400">(optional)</span></label>
        <textarea name="description" id="description" rows="4"
            placeholder="A brief description of the course."
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600">{{ old('description', $course->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600">
            <option value="active" @selected(old('status', $course->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $course->status ?? 'active') === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>