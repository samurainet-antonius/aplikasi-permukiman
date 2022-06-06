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

    <div class="form-row">
        <div class="form-group col">
            <label>Batas Skor Awal</label>
            <input type="number" class="form-control" step="0.01" name="nilai_min" value="{{ old('nilai_min', ($editing ? $statuskumuh->nilai_min : '')) }}" maxlength="10" placeholder="Batas Skor Awal"/>
        </div>
        <div class="form-group col">
            <label>Batas Skor Akhir</label>
            <input type="number" class="form-control" step="0.01" name="nilai_max" value="{{ old('nilai_max', ($editing ? $statuskumuh->nilai_max : '')) }}" maxlength="10" placeholder="Batas Skor Akhir"/>
        </div>
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
            <input class="form-check-input" type="radio" name="icon" id="icon2" value='<i class="fas fa-info-circle"></i>' {{ ($editing ? (($statuskumuh->icon == '<i class="fas fa-info-circle"></i>') ? 'checked' : '') : '') }}>
            <label class="form-check-label" for="icon2">
                <i class="fa fa-exclamation-circle fa-2x"></i>
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
