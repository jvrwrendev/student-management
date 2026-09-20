<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the system settings.
     */
    public function index(): View
    {
        return view('settings.index', [
            'honorsThreshold' => Setting::value('honors_threshold', '90.00'),
        ]);
    }

    /**
     * Update the system settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'honors_threshold' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Setting::set('honors_threshold', number_format((float) $validated['honors_threshold'], 2));

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
