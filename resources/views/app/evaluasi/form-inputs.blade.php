@csrf
<div class="flex flex-wrap mb-5">
    <h3>Lokasi</h3>
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
        <label>Luas Verifikasi</label>
        <div class="input-group mb-3">
            <input name="luas_kumuh" type="number" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon">Ha</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Jumlah Bangunan</label>
        <div class="input-group mb-3">
            <input name="jumlah_bangunan" type="number" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon">Unit</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Jumlah Penduduk</label>
        <div class="input-group mb-3">
            <input name="jumlah_penduduk" type="number" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon">Jiwa</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col">
                <label>Latitude</label>
                <input type="text" class="form-control" name="latitude" id="lat" required>
            </div>
            <div class="col">
                <label>Longitude</label>
                <input type="text" class="form-control" name="longitude" id="lng" required>
            </div>
        </div>
    </div>

    <button class="mb-4 btn btn-primary btn-sm" type="button" onclick="getLocation();">Lokasi Ku</button>
    <div id="map"></div>

    <div class="form-group mt-5">
        <label>Unggah Gambar Delinasi &#42;</label>
        <input type="file" class="form-control" id="file" name="gambar_delinasi" accept="image/*" multiple>
        <small class="form-text text-muted">
            Tipe file berekstensi .jpeg/.jpg/.png <br> Maksimal 5MB
        </small>
    </div>

</div>
<script>
    var map = L.map("map");
    var marker = {};
    L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

    map.setView([3.4201802, 98.704075], 12);

    lokasi()

    function lokasi() {
        map.locate({
            setView: true,
            enableHighAccuracy: true
        })
        .on('locationfound', function(e) {
            // console.log(e.latlng)

            var formLok = e.latlng;
            document.getElementById("lat").value = formLok.lat;
            document.getElementById("lng").value = formLok.lng;

            marker = new L.marker(e.latlng, {draggable: true});
            map.addLayer(marker)
            .on('dragend', function() {
                var coord = String(marker.getLatLng()).split(',');
                // console.log(marker.getLatLng());
            });
        });
    }

    function getLocation() {
        map.removeLayer(marker)
        // map.stopLocate()
        var lok = map.locate({
            setView: true,
            enableHighAccuracy: true
        })
        // console.log(lok._lastCenter);
        var formLok = lok._lastCenter;
        document.getElementById("lat").value = formLok.lat;
        document.getElementById("lng").value = formLok.lng;
    }


    map.on('click',
        function (e) {
            marker.setLatLng(e.latlng)
            console.log(marker.getLatLng());
            var formLok = e.latlng;
            document.getElementById("lat").value = formLok.lat;
            document.getElementById("lng").value = formLok.lng;
        }
    );
</script>
