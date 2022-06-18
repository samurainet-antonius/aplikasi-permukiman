@csrf
<div class="flex flex-wrap mb-5">
    <h3>Geografis</h3>
    <hr/>
    <div class="form-group">
        <label>Tahun</label>
        <select class="select2-single form-control" name="tahun" id="tahun">
            @for($i=date("Y");$i>="2015";$i--)
                <option value="{{ $i; }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
    
    <div class="form-group">
        <label>Provinsi</label>
        <select class="select2-single form-control" name="province_code" id="province" id="select2Single" onchange="submit()">
            <option value="12">SUMATERA UTARA</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kabupaten/Kota</label>
        <select class="select2-single form-control" name="city_code" id="city">
            @if ($city)
                @foreach ($city as $val)
                    <option value="{{$val->code}}" {{ (Request::get('city') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
            <option value="">Kabupaten/Kota tidak ditemukan</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Kecamatan</label>
        <select class="select2-single form-control" name="district_code" id="district">
            <option value="">Pilih Kecamatan</option>
            @if ($district)
                @foreach ($district as $val)
                    <option value="{{$val->code}}" {{ (Request::get('district') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
                <option value="">Kecamatan tidak ditemukan</option>
            @endif
        </select>
    </div>
    @if(Auth::user()->region_code == 3)
        <div class="form-group">
            <label>Desa/Keluarahan</label>
            <select class="select2-single form-control" name="village_code" id="village">
                <option value="">Pilih Kecamatan</option>
                @if ($village)
                    @foreach ($village as $val)
                        <option value="{{$val->code}}" {{ (Request::get('village') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                    @endforeach
                @else
                    <option value="">Desa/Keluarahan tidak ditemukan</option>
                @endif
            </select>
        </div>
    @else
        <div class="form-group">
            <label>Desa/Keluarahan</label>
            <select class="select2-single form-control" name="village_code" id="village">
                <option value="">Pilih Desa/Keluarahan</option>
            </select>
        </div>
    @endif

    <div class="form-group">
        <label>Lingkungan</label>
        <input type="text" class="form-control" name="lingkungan" required>
    </div>

    <div class="form-group">
        <label>Luas Kawasan</label>
        <div class="input-group mb-3">
            <input type="number" name="luas_kawasan" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">Ha</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Luas Kumuh</label>
        <div class="input-group mb-3">
            <input name="luas_kumuh" type="number" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon">Ha</span>
            </div>
        </div>
    </div>
    <button class="mb-4 btn btn-primary btn-sm" type="button" onclick="getLocation();">Lokasi Ku</button>
    <div id="map"></div>
</div>
<script>
    var map = L.map("map");
    L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

    map.setView([48.85, 2.35], 12);

    getLocation();

    function getLocation() {
    map.locate({
        setView: true,
        enableHighAccuracy: true
        })
        .on('locationfound', function(e) {
            console.log(e.latlng)
        var marker = new L.marker(e.latlng);
        marker.addTo(map);
        });
    }
</script>
