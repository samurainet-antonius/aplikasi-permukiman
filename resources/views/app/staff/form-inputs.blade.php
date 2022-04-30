@php $editing = isset($kriteria) @endphp
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
        value="{{ old('name', ($editing ? $user->name : '')) }}"
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
        value="{{ old('jabatan', ($editing ? $user->jabatan : '')) }}"
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
        value="{{ old('nomer_hp', ($editing ? $user->nomer_hp : '')) }}"
        maxlength="255"
        placeholder="masukan nomer whatsapp"
        />
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="district">Kecamatan</label>
                <select class="select2-single form-control" name="district" id="district">
                    <option>pilih kecamatan</option>
                    @foreach ($district as $val)
                        <option value="{{ $val->code }}">{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="desa">Desa</label>
                <select class="select2-single form-control" name="village" id="village">
                    <option>pilih desa</option>
                </select>
            </div>
        </div>
    </div>

    
    <h4 class="mb-4 mt-4">Akun</h4>
    <hr>

    <div class="form-group">
        <label>Email</label>
        <input
        type="text"
        class="form-control"
        name="email"
        value="{{ old('email', ($editing ? $user->email : '')) }}"
        maxlength="255"
        placeholder="masukan email"
        />
    </div>

    <div class="form-group">
        <label>Password</label>
        <input
        type="password"
        class="form-control"
        name="password"
        value=""
        maxlength="255"
        placeholder="masukan password"
        />
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
