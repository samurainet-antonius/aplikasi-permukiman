@php $editing = isset($kriteria) @endphp
@csrf
<div class="flex flex-wrap mb-5">
    <h3>Lokasi</h3>
    <hr/>
    <div class="form-group">
        <label>Province</label>
        <select class="select2-single form-control" name="province_code" id="province" id="select2Single" onchange="submit()">
            <option value="12">SUMATERA UTARA</option>
        </select>
    </div>

    <div class="form-group">
        <label>City</label>
        <select class="select2-single form-control" name="city_code" id="city" onchange="submit()">
            @if ($city)
                @foreach ($city as $val)
                    <option value="{{$val->code}}" {{ (Request::get('city') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
            <option value="">City not found</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Districts</label>
        <select class="select2-single form-control" name="district_code" id="district" onchange="submit()">
            @if ($district)
                @foreach ($district as $val)
                    <option value="{{$val->code}}" {{ (Request::get('district') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
                <option value="">Districts no found</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Villages</label>
        <select class="select2-single form-control" name="village_code" id="village" onchange="submit()">
            @if ($village)
                @foreach ($village as $val)
                    <option value="{{$val->code}}" {{ (Request::get('district') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
                <option value="">Districts no found</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Tahun</label>
        <select class="select2-single form-control" name="tahun" id="tahun">
            @for($i=date("Y");$i>="2015";$i--)
                <option value="{{ $i; }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

    <h3>Data</h3>
    <hr/>
    @forelse($kriteria as $key => $value)
        <h5 class="mt-3 mb-4">{{ $value->nama }}</h5>
        @foreach($value->subkriteria as $x => $v)
            <div class="form-group">
                <label>{{ $v->nama }}</label>
                <textarea name="jawaban[<?php echo $value->id ?>][<?php echo $v->id ?>]" class="form-control" required></textarea>
            </div>
        @endforeach
    @empty
        @lang('crud.common.no_items_found')
    @endforelse

</div>