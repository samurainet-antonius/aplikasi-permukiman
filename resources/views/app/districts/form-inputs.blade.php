@php $editing = isset($province) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Code</label>
        <input
        type="text"
        class="form-control"
        name="code"
        value="{{ $code }}"
        placeholder="code"
        readonly
        />
    </div>

    <div class="form-group">
        <label>@lang('crud.districts.inputs.province')</label>
        <select class="select2-single form-control" name="province_code" id="province">
            <option value="12">SUMATERA UTARA</option>
        </select>
    </div>

    <div class="form-group">
        <label>@lang('crud.districts.inputs.city')</label>
        <select class="select2-single form-control" name="city_code" id="city">
            <option value="1207">KABUPATEN DELI SERDANG</option>
        </select>
    </div>

    <div class="form-group">
        <label>Name</label>
        <input
        type="text"
        class="form-control"
        name="name"
        value="{{ old('name', ($editing ? $province->name : '')) }}"
        maxlength="255"
        placeholder="input name"
        />
    </div>

    <div class="form-group">
        <label>Latitude</label>
        <input
        type="text"
        class="form-control"
        name="latitude"
        value="{{ old('latitude', ($editing ? $province->meta->lat : '')) }}"
        maxlength="255"
        placeholder="input latitude"
        />
    </div>

    <div class="form-group">
        <label>Longitude</label>
        <input
        type="text"
        class="form-control"
        name="longitude"
        value="{{ old('longitude', ($editing ? $province->meta->long : '')) }}"
        maxlength="255"
        placeholder="input longitude"
        />
    </div>

</div>
