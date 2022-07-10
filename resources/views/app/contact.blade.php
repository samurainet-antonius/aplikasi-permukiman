<x-guest-layout>
    @push('styles')
        <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"/>
        <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

        <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
        <script src="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    @endpush
    <div>
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
                                <a href="" class="nav-link text-white">Kontak</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link btn btn-outline-light text-white px-4">Masuk</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </nav>

        <div class="row container-fluid" style="height: 91vh !important;">
            <div class="col-12 col-md-6 col-lg-6">
                <div id="map" class="map"></div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 bg-white p-5">
                <div class="my-4">
                    <h3 class="text-green font-weight-bold">HUBUNGI KAMI</h3>
                    <p>{{ siteSetting('site_description') }}</p>

                    <h6 class="text-green">Alamat</h6>
                    <p>{{ siteSetting('site_address') }}</p>

                    <h6 class="text-green">Nomer Telepon</h6>
                    <p>{{ siteSetting('site_phone') }}</p>

                    <h6 class="text-green">Fax Email</h6>
                    <p>{{ siteSetting('site_fax_email') }}</p>

                    <h6 class="text-green">Email</h6>
                    <p>{{ siteSetting('site_email') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

            var latitude = '3.5473007';
            var longitude = '98.8660959';

            var mapOptions = {
                center: [latitude,longitude],
                zoom: 16
            }

            var map = new L.map('map', mapOptions);

            var pos = new L.LatLng('3.548028862755848','98.86626756137362');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(map);

            var marker = L.marker(pos).addTo(map);

            // 3.548028862755848, 98.86626756137362

        </script>

    @push('scripts')

    @endpush
</x-guest-layout>
