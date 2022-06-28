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
            height: 250px;
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

                                <dt class="col-sm-3 mt-4">@lang('crud.evaluasi.inputs.latitude')</dt>
                                <dd class="col-sm-9 mt-4">{{ $evaluasi->latitude ?? '-' }}</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.longitude')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->longitude ?? '-' }}</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.luas_kawasan')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->luas_kawasan ?? '-' }} Ha</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.luas_kumuh')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->luas_kumuh ?? '-' }} Ha</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.jumlah_bangunan')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->jumlah_bangunan ?? '-' }} Ha</dd>

                                <dt class="col-sm-3">@lang('crud.evaluasi.inputs.jumlah_penduduk')</dt>
                                <dd class="col-sm-9">{{ $evaluasi->jumlah_penduduk ?? '-' }} Ha</dd>

                                <dt class="col-sm-3 mt-5">@lang('crud.evaluasi.inputs.status')</dt>
                                <dd class="col-sm-9 mt-5">{{ $evaluasi->status->nama ?? '-' }}</dd>

                                {{-- <dt class="col-sm-3">@lang('crud.evaluasi.inputs.status')</dt>
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

                                </dd> --}}
                            </dl>
                        </div>
                        <div class="col-5 row">
                            <div class="col-12">
                                <div id="map" class="map"></div>
                            </div>
                            <div class="col-12 mt-4">
                                <img src="{{ asset('public/'.$evaluasi->gambar_delinasi) }}" class="img-thumbnail"/>
                            </div>

                        </div>
                    </div>
                    <h4>Parameter</h4>
                    <hr/>
                    <form class="row justify-content-between mb-4">
                        <div class="col-2">
                            <select class="select2-single form-control" name="bulan" onchange="submit()">
                                @foreach ($bulan as $key => $item)
                                    <option value="{{$key}}" {{ (($date == $key ? 'selected' : '')) }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <a href="{{ route('evaluasi.edit.kriteria', ['evaluasi_id' => $evaluasi->id, 'page' => 0]) }}" class="btn btn-primary float-right {{ $cek == date('m') ? 'disabled' : '' }}">Pembaruan Data</a>
                        </div>
                    </form>
                    {{-- <div class="row">
                    </div> --}}
                    <div class="row">
                        @forelse ($kriteria as $item)
                            <?php $skor = floor($item->skor/$item->sub); ?>
                            <div class="col-4">

                                <div class="card mb-3" style="height: 180px; !important">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <p>{{$item->nama_kriteria}}</p>
                                                <button type="button" data-toggle="modal" data-target="#edit-{{$item->id}}" {{ $date == date('m') ? '' : 'disabled' }} class="px-3 btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#mods">
                                                    Edit
                                                </button>
                                                <button type="button" data-toggle="modal" data-target="#modal-{{$item->id}}" {{ $date == date('m') ? '' : 'disabled' }} class="px-3 btn btn-info btn-sm mt-2" data-toggle="modal" data-target="#mods">
                                                    View
                                                </button>
                                            </div>
                                        </div>
                                        @if($skor == 0)
                                        <div style="height: 180px; !important" class="col-md-4 text-white bg-primary py-5 rounded-right align-items-center text-center">
                                            <h1>{{$skor}}</h1>
                                        </div>
                                        @elseif($skor == 1)
                                        <div style="height: 180px; !important" class="col-md-4 text-white bg-warning py-5 rounded-right align-items-center text-center">
                                            <h1>{{$skor}}</h1>
                                        </div>
                                        @elseif($skor == 3)
                                        <div style="height: 180px; !important" class="col-md-4 text-white bg-warning py-5 rounded-right align-items-center text-center">
                                            <h1>{{$skor}}</h1>
                                        </div>
                                        @else
                                        <div style="height: 180px; !important" class="col-md-4 text-white bg-danger py-5 rounded-right align-items-center text-center">
                                            <h1>{{$skor}}</h1>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center">
                                <h2>Data belum ada.</h2>
                            </div>
                        @endforelse
                    </div>



                    {{-- <div class="table-responsive">
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
                    </div> --}}

                    {{-- <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kriteria</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($kriteria as $key => $value)
                                <tr>
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>
                                        {{ $value->nama_kriteria }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="mr-1 btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-{{$value->id}}">
                                                <i class="fa fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">
                                        @lang('crud.common.no_items_found')
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div> --}}
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


    @foreach ($kriteria as $value)
    <div class="modal fade" id="modal-{{$value->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    </div>
                @endforeach

                <div class="row mt-5">
                    @foreach ($value->foto as $val)
                        <div class="col-6">
                            <img src="{{ asset('public/'.$val->foto) }}" class="img-fluid"/>
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

    @foreach ($kriteria as $value)
    <div class="modal fade" id="edit-{{$value->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Edit Kriteria {{$value->nama_kriteria}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('evaluasi.store.kriteria.edit', ['evaluasi_id' => $evaluasi->id]) }}" has-files class="mt-4" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf
                    @foreach ($value->evaluasi as $key => $item)
                        <div class="form-group mb-3">
                            <label>{{ $key+1 }}. {{$item->nama_subkriteria}}</label>

                            <div class="row">
                                <input type="text" class="ml-4 col-8 form-control" value="{{$item->skor}}" placeholder="masukan skor" name="jawaban[{{$value->kriteria_id}}][{{$item->subkriteria_id}}]" required>
                                <h6 class="col-2 mt-2">{{ $item->subkriteria->satuan }}</h6>
                            </div>

                        </div>
                    @endforeach

                    {{-- <div class="row mt-5">
                        @foreach ($value->foto as $val)
                            <div class="col-6">
                                <img src="{{ asset($val->foto) }}" class="img-fluid"/>
                            </div>
                        @endforeach
                    </div> --}}
                    <div class="form-group mt-5">
                        <label>Unggah gambar &#42;</label>
                        <input type="file" class="form-control mt-2" id="file" name="file[]" accept="image/*" multiple>
                        <small class="form-text text-muted">
                            Maksimal unggah 2 file <br> Tipe file berekstensi .jpeg/.jpg/.png <br> Maksimal 5MB
                        </small>

                        @foreach ($value->foto as $val)
                            <div class="border border-danger text-danger rounded p-2 mt-2 py-3 col-6">
                                {{ $val->foto }}

                                <a href="{{ route('evaluasi.delete.foto', ['evaluasi_id' => $evaluasi->id, 'id' => $val->id ]) }}" class="btn btn-danger btn-sm float-right ml-2">
                                    <i class="fa fa-solid fa-trash"></i>
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm float-right" data-toggle="modal" data-target="#modal-{{$val->id}}">
                                    <i class="fa fa-fw fa-eye"></i>
                                </button>
                            </div>

                            <div class="modal fade" id="modal-{{$val->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <img src="{{ asset('public/'.$val->foto) }}" class="img-fluid"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- <button type="submit" class="btn btn-primary float-right">
                        <i class="mr-1 fa fa-soldi fa-save"></i>
                        @lang('crud.common.create')
                    </button> --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="mr-1 fa fa-soldi fa-save"></i>
                        @lang('crud.common.create')
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
    @endforeach

    <script type="text/javascript">
        var latitude = '{{ $evaluasi->latitude }}';
        var longitude = '{{ $evaluasi->longitude }}';

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

    @push('styles')
        <script>
            $(function(){
                $("#file").change(function(){
                    var $fileUpload = $("input[type='file']");
                    if (parseInt($fileUpload.get(0).files.length)>2){
                        alert("Hanya bisa upload gambar maksimal 2 file");
                        $fileUpload.val('');
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
