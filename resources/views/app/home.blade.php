<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SI-IPEH | Sistem Indikasi Informasi Permukiman Kumuh</title>

    <link href="{{ asset('/assets/img/logo/logo1.png') }}" rel="icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css" integrity="sha512-rqQltXRuHxtPWhktpAZxLHUVJ3Eombn3hvk9PHjV/N5DMUYnzKPC1i3ub0mEXgFzsaZNeJcoE0YHq0j/GFsdGg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    {{-- <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'> --}}

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/assets/css/ruang-admin.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
    <script src="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>

    <style>
        body{
            background-color:white;
        }
        *{
            font-family: 'Poppins';
        }
        #map {
            position: absolute;
            width: 100%;
            right: 0;
            height:100%;
        }
        .fs-14{
            font-size: 14px !important;
        }
        .bg-green {
            background-color: #03A64A !important;
        }
        #map { height: 91vh; width: 100%;}

        .blue   { -webkit-filter: hue-rotate( 30deg); filter: hue-rotate( 30deg); }
        .pink   { -webkit-filter: hue-rotate( 90deg); filter: hue-rotate( 90deg); }
        /* .red    { -webkit-filter: hue-rotate(150deg); filter: hue-rotate(150deg); } */
        .yellow { -webkit-filter: hue-rotate(210deg); filter: hue-rotate(210deg); }
        .green  { -webkit-filter: hue-rotate(270deg); filter: hue-rotate(270deg); }
        .alua   { -webkit-filter: hue-rotate(330deg); filter: hue-rotate(330deg); }
        .cek {
            height: 100px;
            width: 100px;
            background-color: red;
            border-radius: 50%;
            display: inline-block;
        }
    </style>

</head>

<body>


    <nav class="bg-green">

        <div class="navbar navbar-dark navbar-expand px-5 pb-3 pt-3">
            <div class="collapse navbar-collapse">

                <ul class="navbar-nav mr-auto">
                        <li class="nav-item row">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('assets/img/logo/logo1.png') }}" width="30" class="d-inline-block align-top" alt="">
                            </a>
                            <div class="fs-14 mt-2 ml-3 text-white">
                                <a href="{{ route('home') }}" class="text-white">
                                    Dinas Perkim Deli Serdang Sumatera Utara
                                </a>
                            </div>
                        </li>
                </ul>

                <div class="form-inline my-2 my-lg-0">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item mr-4">
                            <a href="{{ route('contact') }}" class="nav-link text-white">Kontak</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link btn btn-outline-light text-white px-4">Masuk</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </nav>

    <div class="content container-fluid">
        <div class="row" style="height: 91vh !important;">
            <div class="col-3 h-100 d-inline-block bg-white">
                <div class="container my-4">
                    <h5 class="font-weight-bold">Peta Online Kawasan Kumuh</h5>
                    <p class="my-4 fs-14">Selamat datang di layanan peta online kawasan kumuh Kabupaten Deli Serdang Sumatera Utara</p>
                    <p class="fs-14">Silakan masukan informasi daerah yang ingin anda ketahui</p>
                    <form>
                        <div class="form-group">
                            <label class="font-weight-bold fs-14">Kecamatan</label>
                            {{-- <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email"> --}}
                            <input type="hidden" id="district_code">
                            <select class="select2-single form-control" id="district">
                                @foreach ($district as $val)
                                <option value="{{ $val->code }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold fs-14">Desa</label>
                            <select class="select2-single form-control" id="village">
                                <option>-- Pilih Desa --</option>
                            </select>
                        </div>
                        <button type="button" class="btn bg-green text-white px-4" id="cari">Cari</button>
                    </form>
                </div>
            </div>
            <div class="col-9 h-100 d-inline-block border">
                <div id="map"></div>
            </div>
        </div>
    </div>

    @stack('modals')

    @stack('scripts')


    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('/assets/js/ruang-admin.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>



    @if (session()->has('success'))
    <script>
        const notyf = new Notyf({dismissible: true})
            notyf.success('{{ session('success') }}')
    </script>
    @endif

    @if($errors->any())
    @foreach ($errors->all() as $error)
    <script>
        const notyf = new Notyf({dismissible: true})
        notyf.error('{{ $error }}')


    //     var mapOptions = {
    //   center: [-6.346515, 108.322556],
    //   zoom: 13
    // }
    // var mapid = new L.map('map', mapOptions);
    // var marker = L.marker([-6.346515, 108.322556]).addTo(mapid);
    // marker.bindPopup("<b>itgenic</b><br>Perumahan Kepandean Regency Blok D No.20, Kepandean, Kec. Indramayu, Kabupaten Indramayu, Jawa Barat 45214").openPopup();
    // L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    //   attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    //   maxZoom: 18,
    //   id: 'mapbox.streets',
    //   accessToken: 'pk.eyJ1IjoicGF4aXRvdDE5OSIsImEiOiJja2Jmenp4M3MxMHA1MnhvNXl1cDdvaDQxIn0.qC-z0-WmnyfnWC8Yo_mQMg'
    // }).addTo(mapid);
    </script>
    @endforeach
    @endif

    <script type="text/javascript">
        var latitude = '3.4201802';
        var longitude = '98.704075';

        var mapOptions = {
			center: [latitude,longitude],
			zoom: 9
		}

        var map = new L.map('map', mapOptions);

        $(document).ready(function() {
            function district_code(x){
                latitude = x.latitude;
                longitude = x.longitude;
            }

            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '{{ route("village.select") }}',
                success: function(data) {
                    $('#village').empty();
                    $.each(data.data,function(index,val){
                        $('#village').append('<option value="'+val.code+'">'+val.name+'</option>');
                    })
                    district_code(data.district)
                }
            });

            $.ajax({
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '{{ route("village.home") }}',
                success: function(data) {
                    detailMap(data)
                }
            });

            $('#district').change(function() {
                $.ajax({
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route("village.select") }}',
                    data: {district_code: $("#district :selected").val()},
                    success: function(data) {
                        $('#village').empty();
                        $.each(data.data,function(index,val){
                            $('#village').append('<option value="'+val.code+'">'+val.name+'</option>');
                        })
                        district_code(data.district)
                    }
                });

                $.ajax({
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route("village.home") }}',
                    data: {district_code: $("#district :selected").val()},
                    success: function(data) {
                        // detailMap(data)
                    }
                });

                // map.flyTo([latitude,longitude], 10, {
                //     animate: true,
                //     duration: 1 // in seconds
                // });
            });

            $('#cari').click(function() {
                $.ajax({
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route("village.search") }}',
                    data: {code: $("#village :selected").val()},
                    success: function(data) {
                        // $('#village').empty();
                        // $.each(data.data,function(index,val){
                        //     $('#village').append('<option value="'+val.code+'">'+val.name+'</option>');
                        // })
                        // district_code(data.district)
                        console.log(data);

                        map.flyTo([data.latitude,data.longitude], 15, {
                            animate: true,
                            duration: 1 // in seconds
                        });

                        // L.circle([data.latitude,data.longitude], 500).addTo(map);
                    }
                });

                // $.ajax({
                //     type: 'GET',
                //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                //     url: '{{ route("village.home") }}',
                //     data: {district_code: $("#village :selected").val()},
                //     success: function(data) {
                //         detailMap(data)
                //     }
                // });

                // map.flyTo([latitude,longitude], 10, {
                //     animate: true,
                //     duration: 1 // in seconds
                // });
            });


        });

        // const api_url = "{{ route('village.home') }}";

        // fetch(api_url)
        // .then((response) => {
        //     return response.json();
        // })
        // .then((data) => {
        //     detailMap(data)
        // })

        // async function getapi(url) {

        //     const response = await fetch(url);

        //     var data = response.json();

        //     return data;
        // }

        // data = getapi(api_url);

        // colorMarker = ['#4CAF50', '#FBC02D', '#FF5722', '#FF5252'];
        colorMarker = [
            {color: '#4CAF50', status: 'Bukan Kawasan Kumuh', icon: '<i class="far fa-check-circle"></i>'},
            {color: '#FBC02D', status: 'Kawasan Kumuh Ringan', icon: '<i class="far fa-check-circle"></i>'},
            {color: '#FF5722', status: 'Kawasan Kumuh Sedang', icon: '<i class="fas fa-info-circle"></i>'},
            {color: '#FF5252', status: 'Kawasan Kumuh Berat', icon: '<i class="far fa-times-circle"></i>'},
        ];

        function showHideTooltip()
        {
            var mytooltip = this.getTooltip();
            if(this.isPopupOpen()){
                mytooltip.setOpacity(0.0);
            } else {
                mytooltip.setOpacity(0.9);
            }
        }

        function clickHideTooltip()
        {
            var mytooltip = this.getTooltip();
            mytooltip.setOpacity(0.0);
        }

        var pos = new L.LatLng('3.0869209','98.6985716');

        var greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        var markers = L.markerClusterGroup();

        var style = document.createElement('style');
        style.type = 'text/css';
        document.head.appendChild(style);

        // var marker = L.marker(pos).addTo(peta);


        async function detailMap(data) {
            data.forEach(function(val, i) {
                if(val.latitude != 'NULL' && val.longitude != 'NULL') {
                    const pos = new L.LatLng(val.latitude,val.longitude);
                    const title = val.name;
                    const kec = val.kecamatan;
                    const color = val.warna;
                    const status = val.status;
                    const icon = val.icon;
                    console.log(icon);
                    style.innerHTML += '.color-'+i+' { height: 20px; width: 20px; background-color: '+color+'; border-radius: 50%; display: inline-block; }';

                    var myIcon = L.divIcon({
                        className: 'color-'+i+'',
                        iconSize: new L.Point(20, 20)
                    });

                    var marker = L.marker(pos, {icon: myIcon, title: title}).addTo(map);

                    marker.bindPopup(title);
                    marker.bindPopup(L.popup({}).setContent(
                        `<div class="bg-white border" style="margin: -25px !important;">
                            <img src="{{ asset("assets/img/modal.png") }}" class="img-fluid" alt="">
                            <div class="text-center fa-7x mx-3">
                                <div class="mb-4" style="color: ${color};">
                                    ${icon}
                                    <h4 class="font-weight-bold">${status}</h4>
                                </div>
                                <div style="margin-bottom: -30px !important;">
                                    <h5>DESA ${title}</h5>
                                    <h6>Kec. ${kec}</h6>
                                </div>
                                <button onclick="removePopups()" type="button" class="btn btn-success px-4">Tutup</button>
                            </div>
                        </div>`
                    )).openPopup();

                    marker.bindTooltip(title);
                    marker.on('mouseover', showHideTooltip);
                    marker.on('click', clickHideTooltip).closePopup();

                    markers.addLayer(marker);
                }
            });
        }

        function removePopups() {
            map.closePopup();
        }

        map.addLayer(markers);

    </script>

</body>

</html>
