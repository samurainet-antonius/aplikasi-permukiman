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

    <div class="form-group">
        <label>Icon Status</label>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="icon" id="icon1" value='<i class="far fa-check-circle"></i>' {{ ($editing ? (($statuskumuh->icon == '<i class="far fa-check-circle"></i>') ? 'checked' : '') : '') }}>
            <label class="form-check-label" for="icon1">
                <i class="far fa-check-circle fa-2x"></i>
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="icon" id="icon2" value='<i class="fas fa-info-circle"></i>' {{ ($editing ? (($statuskumuh->icon == '<i class="fas fa-info-circle"></i>') ? 'checked' : '') : '') }}>
            <label class="form-check-label" for="icon2">
                <i class="fas fa-info-circle fa-2x"></i>
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="icon" id="icon3" value='<i class="far fa-times-circle"></i>' {{ ($editing ? (($statuskumuh->icon == '<i class="far fa-times-circle"></i>') ? 'checked' : '') : '') }}>
            <label class="form-check-label" for="icon3">
                <i class="far fa-times-circle fa-2x"></i>
            </label>
        </div>

    </div>

</div>
