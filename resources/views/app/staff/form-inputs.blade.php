@php $editing = isset($staff) @endphp
@csrf
<div class="flex flex-wrap mb-5">
    <h4 class="mb-4">Data diri</h4>
    <hr>
    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="name"
            value="{{ old('name', ($editing ? $staff->user->name : '')) }}" maxlength="255"
            placeholder="masukan nama" />
    </div>

    <div class="form-group">
        <label>Jabatan</label>
        <input type="text" class="form-control" name="jabatan"
            value="{{ old('jabatan', ($editing ? $staff->jabatan : '')) }}" maxlength="255"
            placeholder="masukan jabatan" />
    </div>

    <div class="form-group">
        <label>Nomer Whatsapp</label>
        <input type="text" class="form-control" name="nomer_hp"
            value="{{ old('nomer_hp', ($editing ? $staff->nomer_hp : '')) }}" maxlength="255"
            placeholder="masukan nomer whatsapp" />
    </div>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="district">Kecamatan</label>
                <select class="select2-single form-control" name="district_code" id="district">
                    <option>pilih kecamatan</option>
                    @foreach ($district as $val)
                    <option value="{{ $val->code }}" {{ ($editing ? (($staff->district_code == $val->code) ? 'selected'
                        : '') : '') }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="desa">Desa</label>
                <select class="select2-single form-control" name="village_code" id="village">
                    <option>pilih desa</option>
                    @foreach ($village as $val)
                    <option value="{{ $val->code }}" {{ ($editing ? (($staff->village_code == $val->code) ? 'selected' :
                        '') : '') }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div>
