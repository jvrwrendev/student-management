@php($teacher = $teacher ?? null)

<div class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
    <div>
        <label for="first_name" class="block text-sm font-medium text-slate-700">First Name</label>
        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $teacher->first_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('first_name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="middle_name" class="block text-sm font-medium text-slate-700">Middle Name <span class="text-slate-400">(optional)</span></label>
        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $teacher->middle_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('middle_name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="last_name" class="block text-sm font-medium text-slate-700">Last Name</label>
        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $teacher->last_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('last_name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $teacher->email ?? '') }}"
            placeholder="teacher@example.com"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('email')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-slate-700">Phone <span class="text-slate-400">(optional)</span></label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $teacher->phone ?? '') }}"
            placeholder="e.g. +1 555 000 1234"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('phone')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="department" class="block text-sm font-medium text-slate-700">Department <span class="text-slate-400">(optional)</span></label>
        <input type="text" name="department" id="department" value="{{ old('department', $teacher->department ?? '') }}"
            placeholder="e.g. Computer Studies"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        @error('department')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-slate-700">Status</label>
        <select name="status" id="status"
            class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            <option value="active" @selected(old('status', $teacher->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $teacher->status ?? 'active') === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2">
        <hr class="border-slate-200">
    </div>

    @if ($teacher === null)
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" name="create_account" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500"
                    {{ old('create_account') ? 'checked' : '' }}>
                Create a login account for this teacher
            </label>
            <p class="mt-1 text-sm text-slate-500">The teacher will be able to sign in with these credentials.</p>
        </div>
    @endif

    @if ($teacher === null)
        <div>
            <label for="login_email" class="block text-sm font-medium text-slate-700">Login Email <span class="text-slate-400">(default: profile email)</span></label>
            <input type="email" name="login_email" id="login_email" value="{{ old('login_email', $teacher->email ?? '') }}"
                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @error('login_email')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input type="password" name="password" id="password"
                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @error('password')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="sm:col-span-2">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
        </div>
    @else
        <div class="sm:col-span-2">
            <label for="reset_password" class="block text-sm font-medium text-slate-700">Reset Password <span class="text-slate-400">(optional)</span></label>
            <input type="password" name="reset_password" id="reset_password" placeholder="Leave blank to keep the current password"
                class="mt-1 block w-full rounded-md border-0 bg-white px-3 py-2 text-sm text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-accent-600">
            @error('reset_password')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
            @if ($teacher->user)
                <p class="mt-2 text-sm text-slate-500">Current login: {{ $teacher->user->email }}</p>
            @else
                <p class="mt-2 text-sm text-amber-600">This teacher does not have a login yet.</p>
            @endif
        </div>
    @endif
</div>