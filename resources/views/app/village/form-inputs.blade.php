@php $editing = isset($village) @endphp
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
        <label>@lang('crud.village.inputs.province')</label>
        <select class="select2-single form-control" name="province_code" id="province">
            <option>Pilih Provinsi</option>
            <option value="12" {{ $editing ? 'selected' : '' }}>SUMATERA UTARA</option>
        </select>
    </div>

    <div class="form-group">
        <label>@lang('crud.village.inputs.city')</label>
        <select class="select2-single form-control" name="city_code" id="city">
            <option>Pilih Kabupaten</option>
        </select>
    </div>

    <div class="form-group">
        <label>@lang('crud.village.inputs.districts')</label>
        <select class="select2-single form-control" name="district_code" id="district">
            <option>Pilih Kecamatan</option>
        </select>
    </div>

    <div class="form-group">
        <label>Name</label>
        <input
        type="text"
        class="form-control"
        name="name"
        value="{{ old('name', ($editing ? $village->name : '')) }}"
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

@if ($editing)

@push('scripts')

<script type="text/javascript">
    $(document).ready(function() {
        var province = 12;
        var selectCity = '{{ $city->code }}';
        $.ajax({
            url:'<?= '/l-app/city/province'; ?>?province='+province+'&select='+selectCity,
            method:'GET',
            success:function(data){
                $("#city").html(data)
            }
        })

        var city = 1207;
        var selectDistrict = '{{ $districts->code }}';
        $.ajax({
            url:'<?= '/l-app/district/city'; ?>?city='+city+'&select='+selectDistrict,
            method:'GET',
            success:function(data){
                $("#district").html(data)
            }
        })
    });
</script>

@endpush

@endif

