<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Districts;
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
        $district = Districts::select('code', 'name')
            ->where('city_code', 1207)
            ->get();

        $roles = Role::get();
        $this->authorize('create', Petugas::class);

        return view('app.staff.create',compact('roles','district'));
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
        try{

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

            $user->syncRoles($request->roles);

            DB::commit();

            return redirect()
            ->route('staff.index')
            ->withSuccess(__('crud.common.created'));

        }catch(Exception $e){
            DB::rollback();

            return redirect()
            ->route('staff.create')
            ->withSuccess(__('crud.common.errors'));
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

        $staff = Petugas::find($id);

        return view('app.staff.edit')->with('staff', $kriteria);
    }

    /**
     * @param \App\Http\Requests\KriteriaUpdateRequest $request
     * @param \App\Models\Kriteria $kriteria
     * @return \Illuminate\Http\Response
     */
    public function update(PetugasaUpdateRequest $request, $id)
    {
        $this->authorize('update', Petugas::class);

        $validated = $request->validated();

        $kriteria = Petugas::find($id);

        $staff->update($validated);

        $staff->syncRoles($request->roles);

        return redirect()
            ->route('petugas.index')
            ->withSuccess(__('crud.common.saved'));
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
        try{
            $staff = Petugas::find($id);
            $user = User::find($staff->users_id);
            $user->delete();
            $staff->delete();
            DB::commit();

            return redirect()
            ->route('staff.index')
            ->withSuccess(__('crud.common.removed'));

        }catch(Exception $e){
            DB::rollback();

            return redirect()
            ->route('staff.index')
            ->withSuccess(__('crud.common.errors'));
        }
    }
}
