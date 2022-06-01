@php $editing = isset($subkriteria) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Kriteria</label>
        <select class="select2-single form-control" name="kriteria_id" id="select2Single">
            @foreach ($kriteria as $item)
            <option value="{{$item->id}}" {{ ($editing ? (($subkriteria->kriteria_id == $item->id) ? 'selected' : '') : '') }}>{{$item->nama}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Nama Subkriteria</label>
        <textarea
        class="form-control"
        name="nama"
        >{{ old('nama', ($editing ? $subkriteria->nama : '')) }}</textarea>
    </div>

</div>
