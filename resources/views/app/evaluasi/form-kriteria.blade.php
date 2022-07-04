<x-app-layout>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.evaluasi.create_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('evaluasi.index') }}">Evaluasi</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('evaluasi.store.kriteria', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['next']]) }}" has-files class="mt-4" enctype="multipart/form-data">

                        @csrf
                        <div class="flex flex-wrap mb-5">
                            <h3>{{ $kriteria['nama'] }}</h3>
                            <p>Silakan lakukan pengisian data dengan cara memilih salah satu kondisi yang tepat, dan unggah maksimal dua foto</p>
                            <hr/>


                            @foreach ($subkriteria as $key => $item)
                            <div class="form-group mb-4">
                                <label>{{ $key+1 }}. {{$item->nama}}</label>

                                <div class="row">
                                    <input type="text" class="ml-4 col-8 form-control" value="{{$item->evaluasi}}" placeholder="masukan nilai numerik" name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" required>
                                    <h6 class="col-2 mt-2">{{ $item->satuan }}</h6>
                                </div>
                                <br/>
                                <div class="row">
                                    <input type="text" class="ml-4 col-8 form-control" value="{{$item->evaluasi}}" placeholder="masukan persen" name="persen[{{$kriteria['id']}}][{{$item->id}}]" required>
                                    <h6 class="col-2 mt-2">Persen (%)</h6>
                                </div>


                            </div>
                            @endforeach

                            <div class="form-group mt-5">
                                <label>Unggah gambar &#42;</label>
                                <input type="file" class="form-control" id="file" name="file[]" accept="image/*" multiple>
                                <small class="form-text text-muted">
                                    Maksimal unggah 2 file <br> Tipe file berekstensi .jpeg/.jpg/.png <br> Maksimal 5MB
                                </small>


                                @foreach ($data['foto'] as $val)
                                    <div class="border border-danger text-danger rounded p-2 mt-2 py-3 col-6">
                                        {{ $val->foto }}

                                        <a href="{{ route('evaluasi.delete.foto.create', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['next'], 'id' => $val->id ]) }}" class="btn btn-danger btn-sm float-right ml-2">
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


                        </div>

                        <div class="mt-10">

                            @if ($data['next'] != 1)
                                <a href="{{ route('evaluasi.create.kriteria', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['prev']]) }}" class="btn btn-primary">
                                    Kembali
                                </a>
                            @else
                                <a href="{{ route('evaluasi.create') }}" class="btn btn-primary">
                                    Kembali
                                </a>
                            @endif


                            @if ($data['count'] == $data['next'])
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="mr-1 fa fa-soldi fa-save"></i>
                                    @lang('crud.common.create')
                                </button>
                            @else
                                <button type="submit" id="submit" class="btn btn-primary float-right">
                                    Selanjutnya
                                </button>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


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
