<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\PetugasUpdateRequest;
use App\Models\Petugas;
use App\Models\City;
use App\Models\Province;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Auth;

class UserController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', User::class);

        $search = $request->get('search', '');

        $users = User::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.users.index', compact('users', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', User::class);

        $roles = Role::get();

        $region = Province::all();

        return view('app.users.create', compact('roles', 'region'));
    }

    public function selectSearch(Request $request)
    {
        $region = [];
        if ($request->has('q')) {
            $search = $request->q;
            $roles = DB::table('roles')->find($request->roles);

            if (strpos($roles->name, 'provinsi') !== false) {
                $region = Province::select("code", "name")->where('code', '12')->get();
            } elseif (strpos($roles->name, 'kabupaten') !== false) {
                $region = City::select("code", "name")->where('name', 'LIKE', "%$search%")->get();
            } elseif (strpos($roles->name, 'kecamatan') !== false) {
                $region = District::select("code", DB::raw('CONCAT("KECAMATAN", " - ", name) as name'))->where('name', 'LIKE', "%$search%")->get();
            } elseif (strpos($roles->name, 'kelurahan') !== false) {
                $region = Village::select("code", DB::raw('CONCAT("KELURAHAN", " - ", name) as name'))->where('name', 'LIKE', "%$search%")->get();
            } else {
                $region = Province::select("code", "name")->where('code', '12')->get();
            }
        }
        return response()->json($region);
    }

    /**
     * @param \App\Http\Requests\UserStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('public');
        }

        $user = User::create($validated);

        $user->syncRoles($request->roles);

        return redirect()
            ->route('users.index')
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $this->authorize('view', $user);

        return view('app.users.show', compact('user'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $roles = Role::get();

        return view('app.users.edit', compact('user', 'roles'));
    }

    /**
     * @param \App\Http\Requests\UserUpdateRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        if (empty($request['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('public');
        }

        $role = Role::find($validated['roles']);

        if ($role['name'] == 'admin-provinsi' || $role['name'] == "super-admin") {
            $validated['region_code'] = 0;
        }

        if ($role['name'] == 'admin-kabupaten' || $role['name'] == 'bupati' || $role['name'] == 'kepala-dinas' || $role['name'] == 'kepala-bidang' || $role['name'] == 'seksi' || $role['name'] == 'petugas-kabupaten') {
            $validated['region_code'] = 1;
        }

        if ($role['name'] == 'admin-kecamatan' || $role['name'] == 'camat' || $role['name'] == 'petugas-kecamatan') {
            $validated['region_code'] = 2;
        }

        if ($role['name'] == 'admin-kelurahan' || $role['name'] == 'lurah' || $role['name'] == 'petugas-kelurahan') {
            $validated['region_code'] = 3;
        }

        $user->update($validated);

        $user->syncRoles($request->roles);

        return redirect()
            ->route('users.index')
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        Petugas::where('users_id', $user->id)->delete();
        $user->delete();

        return redirect()
            ->route('users.index')
            ->withSuccess(__('crud.common.removed'));
    }

    public function profil()
    {
        $auth = Auth::user();
        $user_id = $auth->id;

        $petugas = Petugas::where('users_id', $user_id)->first();

        return view('app.profile.profile', compact('petugas'));
    }

    public function profilChange()
    {

        $auth = Auth::user();
        $user_id = $auth->id;

        $petugas = Petugas::where('users_id', $user_id)->first();

        return view('app.profile.setting', compact('petugas'));
    }

    public function updateProfile(PetugasUpdateRequest $request)
    {

        $validated = $request->validated();

        $auth = Auth::user();
        $user_id = $auth->id;

        $user = User::find($user_id);

        DB::beginTransaction();
        try {

            $user->update(['name' => $validated['name']]);

            Petugas::where('users_id', $user_id)->update([
                'jabatan' => $validated['jabatan'],
                'nomer_hp' => $validated['nomer_hp'],
            ]);

            DB::commit();
            return redirect()
                ->route('profil')
                ->withSuccess(__('crud.common.saved'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()
                ->route('setting-profil')
                ->withErrors(__('crud.common.errors'));
        }
    }

    public function setting()
    {

        $auth = Auth::user();
        $user_id = $auth->id;

        $user = User::find($user_id);

        return view('app.profile.password', compact('user'));
    }

    public function settingPassword(Request $request)
    {

        $auth = Auth::user();
        $user_id = $auth->id;

        $user = User::find($user_id);

        DB::beginTransaction();
        try {

            if (!empty($request->password)) {
                $data['password'] = Hash::make($request->password);
                $data['email'] = $request->email;
            }

            $user->update($data);

            DB::commit();
            return redirect()
                ->route('setting')
                ->withSuccess(__('crud.common.saved'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()
                ->route('setting')
                ->withErrors(__('crud.common.errors'));
        }
    }
}
