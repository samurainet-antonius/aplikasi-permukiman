<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Districts;
use App\Models\Village;
use App\Http\Requests\PetugasStoreRequest;
use App\Http\Requests\PetugasUpdateRequest;
use App\Models\Petugas;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use Exception;

class EmployeeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Petugas::class);

        $search = $request->get('search', '');

        $staff = Petugas::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.staff.index', compact('staff', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Petugas::class);
        $district = Districts::select('code', 'name')
            ->where('city_code', 1207)
            ->get();

        $roles = Role::get();


        $village = Village::select('code', 'district_code', 'name')
            ->where('district_code', $district[0]->code)
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();

        return view('app.staff.create', compact('roles', 'district', 'village'));
    }

    /**
     * @param \App\Http\Requests\KriteriaStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PetugasStoreRequest $request)
    {
        $this->authorize('create', Petugas::class);

        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $role = Role::find($request['roles']);

            if ($role['name'] == 'admin-provinsi' || $role['name'] == "super-admin") {
                $region_code = 0;
            }

            if ($role['name'] == 'admin-kabupaten' || $role['name'] == 'bupati' || $role['name'] == 'kepala-dinas' || $role['name'] == 'kepala-bidang' || $role['name'] == 'seksi' || $role['name'] == 'petugas-kabupaten') {
                $region_code = 1;
            }

            if ($role['name'] == 'admin-kecamatan' || $role['name'] == 'camat' || $role['name'] == 'petugas-kecamatan') {
                $region_code = 2;
            }

            if ($role['name'] == 'admin-kelurahan' || $role['name'] == 'lurah' || $role['name'] == 'petugas-kelurahan') {
                $region_code = 3;
            }

            $user = User::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'region_code' => $region_code
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

            $user->syncRoles($request->roles);

            DB::commit();

            return redirect()
                ->route('staff.index')
                ->withSuccess(__('crud.common.created'));
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();

            return redirect()
                ->route('staff.create')
                ->withErrors(__('crud.common.errors'));
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Petugas $staff)
    {
        $this->authorize('view', $staff);

        return view('app.staff.show', compact('staff'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Petugas::class);

        $district = Districts::select('code', 'name')
            ->where('city_code', 1207)
            ->get();

        $staff = Petugas::find($id);
        $roles = Role::get();

        $village = Village::select('code', 'district_code', 'name')
            ->where('district_code', $staff->district_code)
            ->orderBy('district_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->latest()
            ->get();

        return view('app.staff.edit', compact('roles', 'staff', 'district', 'village'));
    }

    /**
     * @param \App\Http\Requests\KriteriaUpdateRequest $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function update(PetugasUpdateRequest $request, $id)
    {
        $this->authorize('update', Petugas::class);

        $validated = $request->validated();

        $staff = Petugas::find($id);
        $user = User::find($staff->users_id);

        DB::beginTransaction();
        try {

            $user->update(['name' => $validated['name']]);
            unset($validated['name']);
            $staff->update($validated);
            DB::commit();

            return redirect()
                ->route('staff.index')
                ->withSuccess(__('crud.common.saved'));
        } catch (Exception $e) {

            DB::rollback();
            return redirect()
                ->route('staff.index')
                ->withErrors(__('crud.common.errors'));
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Petugas::class);

        DB::beginTransaction();
        try {
            $staff = Petugas::find($id);
            $user = User::find($staff->users_id);
            $user->delete();
            $staff->delete();
            DB::commit();

            return redirect()
                ->route('staff.index')
                ->withSuccess(__('crud.common.removed'));
        } catch (Exception $e) {
            DB::rollback();

            return redirect()
                ->route('staff.index')
                ->withErrors(__('crud.common.errors'));
        }
    }
}
