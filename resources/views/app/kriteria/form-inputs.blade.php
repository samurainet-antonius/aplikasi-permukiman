@php $editing = isset($kriteria) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Nama</label>
        <input
        type="text"
        class="form-control"
        name="nama"
        value="{{ old('name', ($editing ? $kriteria->nama : '')) }}"
        maxlength="255"
        placeholder="Nama"
        />
    </div>

</div>
