<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.staff.create_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">
                <a href="{{ route('kriteria.index') }}">@lang('crud.staff.name')</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.store') }}" has-files class="mt-4">
                    @php $editing = isset($staff) @endphp
                    @csrf
                    <div class="flex flex-wrap mb-5">
                        <h4 class="mb-4">Data diri</h4>
                        <hr>
                        <div class="form-group">
                            <label>Nama</label>
                            <input
                            type="text"
                            class="form-control"
                            name="name"
                            value="{{ old('name', ($editing ? $staff->user->name : '')) }}"
                            maxlength="255"
                            placeholder="masukan nama"
                            />
                        </div>

                        <div class="form-group">
                            <label>Jabatan</label>
                            <input
                            type="text"
                            class="form-control"
                            name="jabatan"
                            value="{{ old('jabatan', ($editing ? $staff->jabatan : '')) }}"
                            maxlength="255"
                            placeholder="masukan jabatan"
                            />
                        </div>

                        <div class="form-group">
                            <label>Nomer Whatsapp</label>
                            <input
                            type="text"
                            class="form-control"
                            name="nomer_hp"
                            value="{{ old('nomer_hp', ($editing ? $staff->nomer_hp : '')) }}"
                            maxlength="255"
                            placeholder="masukan nomer whatsapp"
                            />
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="district">Kecamatan</label>
                                    <select class="select2-single form-control" name="district" id="district">
                                        @foreach ($district as $val)
                                            <option value="{{ $val->code }}" {{ ($editing ? (($staff->district_code == $val->code) ? 'selected' : '') : '') }}>{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="desa">Desa</label>
                                    <select class="select2-single form-control" name="village" id="village">
                                        <option>pilih desa</option>
                                        @foreach ($village as $val)
                                            <option value="{{ $val->code }}" {{ ($editing ? (($staff->village_code == $val->code) ? 'selected' : '') : '') }}>{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h4 class="mb-4 mt-4">Akun</h4>
                        <hr>
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>

                        <div class="form-group">
                            <label for="Password">Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="form-group">
                            <label>Assign @lang('crud.roles.name')</label>
                            <select class="select2-single form-control" name="roles" id="select2Single">
                                @foreach ($roles as $role)
                                <option value="{{$role->id}}" {{ ($editing ? (($user->hasRole($role)) ? 'selected' : '') : '') }}>{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                        <div class="mt-10">
                            <a href="{{ route('kriteria.index') }}" class="button">
                                <i class="
                                                mr-1
                                                fa fa-solid fa-arrow-left
                                                text-primary
                                            "></i>
                                @lang('crud.common.back')
                            </a>

                            <button type="submit" class="btn btn-primary float-right">
                                <i class="mr-1 fa fa-soldi fa-save"></i>
                                @lang('crud.common.create')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
