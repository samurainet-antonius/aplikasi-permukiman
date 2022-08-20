<x-guest-layout>
    @push('styles')
    <script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.3.0/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>
    <script src="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>

    <style>
        #map {
            width: 100%;
            height: 250px;
        }

    </style>
    @endpush
    <nav class="bg-green">

        <div class="navbar navbar-dark navbar-expand px-5 pb-3 pt-3">
            <div class="collapse navbar-collapse">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item row">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/img/logo/logo1.png') }}" width="30"
                                class="d-inline-block align-top" alt="">
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
                            <a href="{{ route('login') }}"
                                class="nav-link btn btn-outline-light text-white px-4">Masuk</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

    </nav>
    <div class="row container-fluid" style="height: 100vh !important;">
        <div class="col-12">
            <div class="row justify-content-center">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h4 class="float-left">Lokasi</h4>
                            {{-- <p class="float-right">Last Update : {{ $evaluasi->updated_at == null ?  $evaluasi->created_at : $evaluasi->updated_at}}</p> --}}
                            <p class="float-right">Last Update : {{ $evaluasi->updated_at == null ?  \Carbon\Carbon::parse($evaluasi->created_at)->format('d M Y, H:i') : \Carbon\Carbon::parse($evaluasi->updated_at)->format('d M Y, H:i')}}</p>
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="row mb-5">
                            <div class="col-7">
                                <dl class="row">
                                    <dt class="col-sm-3">@lang('crud.evaluasi.inputs.tahun')</dt>
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

                                    <dt class="col-sm-3">@lang('crud.evaluasi.inputs.lingkungan')</dt>
                                    <dd class="col-sm-9">{{ $evaluasi->lingkungan ?? '-' }}</dd>
                                </dl>
                            </div>
                            <div class="col-5">
                                <dl class="row">
                                    <dt class="col-sm-5">@lang('crud.evaluasi.inputs.luas_kawasan')</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->luas_kawasan ?? '-' }} Ha</dd>

                                    <dt class="col-sm-5">@lang('crud.evaluasi.inputs.luas_kumuh')</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->luas_kumuh ?? '-' }} Ha</dd>

                                    <dt class="col-sm-5">@lang('crud.evaluasi.inputs.jumlah_bangunan')</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->jumlah_bangunan ?? '-' }} Unit</dd>

                                    <dt class="col-sm-5">@lang('crud.evaluasi.inputs.jumlah_penduduk')</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->jumlah_penduduk ?? '-' }} Jiwa</dd>

                                    <dt class="col-sm-5">@lang('crud.evaluasi.inputs.jumlah_kepala_keluarga')</dt>
                                    <dd class="col-sm-7">{{ $evaluasi->jumlah_kepala_keluarga ?? '-' }} KK</dd>
                                </dl>

                            </div>
                        </div>
                        <h4>Parameter</h4>
                        <hr />
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Aspek</th>
                                        <th scope="col">Kriteria</th>
                                        <th scope="col">Numerik</th>
                                        <th scope="col">Satuan</th>
                                        <th scope="col">Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kriteria as $item)
                                        @foreach ($item->evaluasi as $key => $value)
                                            @if ($key == 0)
                                                <tr>
                                                    <th rowspan="{{$item->sub}}">{{ $item->nama_kriteria }}</th>
                                                    <td>{{ $key+1 }}. {{$value->nama_subkriteria}}</td>
                                                    <td class="text-center">{{$value->jawaban}}</td>
                                                    <td class="text-center">{{$value->satuan}}</td>
                                                    <td rowspan="{{$item->sub}}"><img src="{{ asset('public/'.$item->foto[0]->foto) }}" class="img-thumbnail ml-4" style="max-height: 200px;"/></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>{{ $key+1 }}. {{$value->nama_subkriteria}}</td>
                                                    <td class="text-center">{{$value->jawaban}}</td>
                                                    <td class="text-center">{{$value->satuan}}</td>
                                                </tr>
                                            @endif

                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($kriteria as $value)
    <div class="modal fade" id="modal-{{$value->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Kriteria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($value->evaluasi as $item)
                    <div class="mb-3">
                        <p class="font-weight-bold"><i class="fas fa-circle mr-1"></i> {{ $item->nama_subkriteria }}</p>
                        <p class="ml-4">{{ $item->jawaban }} {{ $item->subkriteria->satuan }}</p>
                        <p class="ml-4">{{ $item->persen }} Prosen (%)</p>
                    </div>
                    @endforeach

                    <div class="row mt-5">
                        @foreach ($value->foto as $val)
                        <div class="col-6">
                            <img src="{{ asset('public/'.$val->foto) }}" class="img-fluid" />
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script type="text/javascript">
        var latitude = '{{ $evaluasi->latitude }}';
        var longitude = '{{ $evaluasi->longitude }}';

        var mapOptions = {
            center: [latitude, longitude],
            zoom: 16
        }

        var map = new L.map('map', mapOptions);

        var pos = new L.LatLng(latitude, longitude);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        var marker = L.marker(pos).addTo(map);

    </script>
</x-guest-layout>
