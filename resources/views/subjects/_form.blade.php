@php($subject = $subject ?? null)

<div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
    <div>
        <label for="subject_code" class="block text-sm font-medium text-slate-700">Subject Code</label>
        <input type="text" name="subject_code" id="subject_code" value="{{ old('subject_code', $subject->subject_code ?? '') }}"
            placeholder="e.g. IT-101"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('subject_code')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="subject_name" class="block text-sm font-medium text-slate-700">Subject Name</label>
        <input type="text" name="subject_name" id="subject_name" value="{{ old('subject_name', $subject->subject_name ?? '') }}"
            placeholder="e.g. Introduction to Computing"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('subject_name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="description" class="block text-sm font-medium text-slate-700">Description <span class="text-slate-400">(optional)</span></label>
        <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">{{ old('description', $subject->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="units" class="block text-sm font-medium text-slate-700">Units</label>
        <input type="number" name="units" id="units" value="{{ old('units', $subject->units ?? 3.0) }}"
            step="0.5" min="0" max="30"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('units')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
        <select name="status" id="status"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <option value="active" @selected(old('status', $subject->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $subject->status ?? 'active') === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>