<x-app-layout>
    @push('styles')
        <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"/>
        <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

        <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
        <script src="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.js"></script>
        <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>

        <style>
        #map {
            width: 100%;
            height: 100%;
        }
    </style>
    @endpush


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.evaluasi.show_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('evaluasi.index') }}">Evaluasi</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4>Lokasi</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-7">
                            <dl class="row">
                                <dt class="col-sm-3">Tahun</dt>
                                <dd class="col-sm-9">{{ $evaluasi->tahun ?? '-' }}</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.provinsi')</dt>
                                <dd class="col-sm-9">
                                    {{ $evaluasi->province->name ?? '-' }}
                                </dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.kota')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->city->name ?? '-' }}</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.kecamatan')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->district->name ?? '-' }}</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.desa')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->village->name ?? '-' }}</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.status')</dt>
                                <dd class="col-sm-9">

                                    @if(Auth::user()->roles[0]->name == "super-admin" || Auth::user()->roles[0]->name == "admin-provinsi" || Auth::user()->roles[0]->name == "admin-kabupaten")

                                    <form action="{{ route('change-status',$evaluasi->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <select name="status" class="form-control" onchange="submit()" require>
                                                <option value="">- Pilih Status Kumuh -</option>
                                                @foreach($status as $v)
                                                    <option value="{{ $v->id }}" {{ ($evaluasi->status_id == $v->id ) ? 'selected' : '' }}>{{ $v->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>

                                    @else
                                        {{ $evaluasi->status->nama }}
                                    @endif

                                </dd>
                            </dl>
                        </div>
                        <div class="col-5">
                            <div id="map" class="map"></div>
                        </div>
                    </div>
                    <h4>Data</h4>
                    <hr/>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    @foreach($kriteria as $v)
                                        <th>{{ $v->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($kriteria as $z)
                                        <td>
                                        @foreach($evaluasi->evaluasidetail as $x)
                                            <dt class="col-sm-12">{{ $x->nama_subkriteria }}</dt>
                                            <dd class="col-sm-12">{{ $x->jawaban }}</dd>
                                        @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <div class="mt-10">
                        <a href="{{ route('evaluasi.index') }}" class="button">
                            <i class="
                            mr-1
                            fa fa-solid fa-arrow-left
                            text-primary
                            "></i>
                            @lang('crud.common.back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var latitude = '{{ $village->latitude }}';
        var longitude = '{{ $village->longitude }}';

        var mapOptions = {
            center: [latitude,longitude],
            zoom: 16
        }

        var map = new L.map('map', mapOptions);

        var pos = new L.LatLng(latitude,longitude);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        var marker = L.marker(pos).addTo(map);

    </script>

</x-app-layout>
