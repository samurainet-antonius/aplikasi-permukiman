@php $editing = isset($evaluasi) ? $evaluasi : '' @endphp
@csrf
<?php
$evaluasidetail = isset($editing->evaluasidetail) ? $editing->evaluasidetail->toArray() : '';
?>
<div class="flex flex-wrap mb-5">
    <h3>Lokasi</h3>
    <hr/>

    <div class="form-group">
        <label>Tahun</label>
        <select class="select2-single form-control" name="tahun" id="tahun">
            @for($i=date("Y");$i>="2015";$i--)
                <option value="{{ $i; }}" {{ ($editing->tahun == $i) ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <label>Province</label>
        <select class="select2-single form-control" name="province_code" id="province" id="select2Single" onchange="submit()">
            <option value="12">SUMATERA UTARA</option>
        </select>
    </div>

    <div class="form-group">
        <label>City</label>
        <select class="select2-single form-control" name="city_code" id="city">
            @if ($city)
                @foreach ($city as $val)
                    <option value="{{$val->code}}" {{ (Request::get('city') == $val->code || $editing->city_code == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
            <option value="">City not found</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Districts</label>
        <select class="select2-single form-control" name="district_code" id="district">
            @if ($district)
                @foreach ($district as $val)
                    <option value="{{$val->code}}" {{ (Request::get('district') == $val->code || $editing->district_code == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
                <option value="">Districts no found</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Villages</label>
        <select class="select2-single form-control" name="village_code" id="village">
            @if ($village)
                @foreach ($village as $val)
                    <option value="{{$val->code}}" {{ (Request::get('district') == $val->code || $editing->village_code == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                @endforeach
            @else
                <option value="">Districts no found</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label>Lingkungan</label>
        <input type="text" class="form-control" name="lingkungan" value="{{ old('name', ($editing ? $evaluasi->lingkungan : '')) }}" required>
    </div>

    <div class="form-group">
        <label>Luas Kawasan</label>
        <div class="input-group mb-3">
            <input type="number" name="luas_kawasan" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="{{ old('name', ($editing ? $evaluasi->luas_kawasan : '')) }}">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">Ha</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label>Luas Kumuh</label>
        <div class="input-group mb-3">
            <input name="luas_kumuh" type="number" step="any" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon" value="{{ old('name', ($editing ? $evaluasi->luas_kumuh : '')) }}">
            <div class="input-group-append">
                <span class="input-group-text" id="basic-addon">Ha</span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col">
                <label>Latitude</label>
                <input type="text" class="form-control" name="latitude" id="lat" required value="{{ old('name', ($editing ? $evaluasi->latitude : '')) }}">
            </div>
            <div class="col">
                <label>Longitude</label>
                <input type="text" class="form-control" name="longitude" id="lng" required value="{{ old('name', ($editing ? $evaluasi->longitude : '')) }}">
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

        @if ($editing && $evaluasi->gambar_delinasi)
            <div class="border border-danger text-danger rounded p-2 mt-2 py-3 col-6">
                {{ $evaluasi->gambar_delinasi }}

                <a href="{{ route('evaluasi.delete.file', $evaluasi->id) }}" class="btn btn-danger btn-sm float-right ml-2">
                    <i class="fa fa-solid fa-trash"></i>
                </a>
                <button type="button" class="btn btn-secondary btn-sm float-right" data-toggle="modal" data-target="#modal">
                    <i class="fa fa-fw fa-eye"></i>
                </button>
            </div>

            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <img src="{{ asset($evaluasi->gambar_delinasi) }}" class="img-fluid"/>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>

@stack('scripts')
<script>

    $(document).ready(function(){
        $("#district").on('change',function(){
            var district = $(this).val();
            var edit = <?php echo json_encode($editing->district_id); ?>;
            $.ajax({
            url:'<?= '/l-app/village/district'; ?>?district='+edit,
            method:'GET',
            success:function(data){
                $("#village").html(data)
            }
            })
        });
    });

    var cek = <?php echo json_encode($editing); ?>;
    const pos = new L.LatLng(cek.latitude,cek.longitude);
    var map = L.map("map");
    var marker = {};
    L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

    map.setView(pos, 18);

    marker = new L.marker(pos, {draggable: true});
    map.addLayer(marker)

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

