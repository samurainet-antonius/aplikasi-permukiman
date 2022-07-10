<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function index(SiteSetting $settings)
    {
        return view('app.settings.edit', [
            'data' => $settings,
        ]);
    }

    public function update(SiteSettingRequest $request, SiteSetting $settings)
    {
        $this->authorize('update', $settings);

        $validated = $request->validated();
        if ($request->hasFile('site_logo')) {
            if ($settings->site_logo) {
                Storage::delete($settings->site_logo);
            }

            $validated['site_logo'] = $request->file('site_logo')->store('public');
            $settings->site_logo = $validated['site_logo'];
        }

        $settings->site_name = $validated['site_name'];
        $settings->site_description = $validated['site_description'];
        $settings->site_email = $validated['site_email'];
        $settings->site_phone = $validated['site_phone'];
        $settings->site_address = $validated['site_address'];
        $settings->site_fax_email = $validated['site_fax_email'];

        $settings->save();

        return redirect()->back()
            ->withSuccess(__('crud.common.saved'));
    }
}