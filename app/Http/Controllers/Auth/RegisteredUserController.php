<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Districts;
use App\Models\Petugas;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $district = Districts::select('code', 'name')
            ->where('city_code', 1207)
            ->get();

        return view('auth.register', compact('district'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'district' => ['required'],
            'village' => ['required'],
            'jabatan' => ['required'],
            'nomer_hp' => ['required'],
        ]);

        DB::beginTransaction();

        try {
            
            $user = User::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            $petugas = Petugas::create([
                'users_id' => $user->id,
                'province_code' => 12,
                'city_code' => 1207,
                'district_code' => $request->district,
                'village_code' => $request->village,
                'jabatan' => $request->jabatan,
                'nomer_hp' => $request->nomer_hp,
            ]);

            DB::commit();

            // Auth::login($user);
            return redirect()->route('login')->withSuccess(__('crud.common.created'));

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('login')->withSuccess(__('crud.common.errors'));
        }
    }
}
