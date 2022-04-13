@php $editing = isset($statuskumuh) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Nama</label>
        <input
        type="text"
        class="form-control"
        name="nama"
        value="{{ old('nama', ($editing ? $statuskumuh->nama : '')) }}"
        maxlength="255"
        placeholder="Nama"
        />
    </div>

    <div class="form-group">
        <label>Warna</label>
        <input
        type="color"
        class="form-control"
        name="warna"
        value="{{ old('warna', ($editing ? $statuskumuh->warna : '')) }}"
        maxlength="255"
        placeholder="Warna"
        />
    </div>

</div>
