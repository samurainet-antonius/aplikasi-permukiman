@php $editing = isset($statuskriteria) @endphp
@csrf
<div class="flex flex-wrap mb-5">

    <div class="form-group">
        <label>Tahun</label>
        <select class="select2-single form-control" name="tahun" id="tahun">
            @for($i=date("Y");$i>="2016";$i--)
                <option value="{{ $i; }}" {{ ($editing ? (($statuskriteria->tahun == $i) ? 'selected' : '') : '') }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <label>Nama</label>
        <input type="text"
        class="form-control"
        name="nama"
        value="{{ old('nama', ($editing ? $statuskriteria->nama : '')) }}"
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
        value="{{ old('warna', ($editing ? $statuskriteria->warna : '')) }}"
        maxlength="255"
        placeholder="Warna"
        />
    </div>

    <div class="form-row">
        <div class="form-group col">
            <label>Batas Skor Awal</label>
            <input type="number" class="form-control" step="0.01" name="nilai_min" value="{{ old('nilai_min', ($editing ? $statuskriteria->nilai_min : '')) }}" maxlength="10" placeholder="Batas Skor Awal"/>
        </div>
        <div class="form-group col">
            <label>Batas Skor Akhir</label>
            <input type="number" class="form-control" step="0.01" name="nilai_max" value="{{ old('nilai_max', ($editing ? $statuskriteria->nilai_max : '')) }}" maxlength="10" placeholder="Batas Skor Akhir"/>
        </div>
    </div>

</div>
