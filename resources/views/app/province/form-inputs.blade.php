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
        value="{{ old('latitude', ($editing ? $meta->lat : '')) }}"
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
        value="{{ old('longitude', ($editing ? $meta->long : '')) }}"
        maxlength="255"
        placeholder="input longitude"
        />
    </div>

</div>
