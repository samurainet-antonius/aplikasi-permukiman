<x-app-layout>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.evaluasi.create_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('evaluasi.index') }}">Evaluasi</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Create</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('evaluasi.store.kriteria', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['next']]) }}" has-files class="mt-4" enctype="multipart/form-data">
                        {{-- @include('app.evaluasi.form-inputs') --}}

                        @csrf
                        <div class="flex flex-wrap mb-5">
                            <h3>{{ $kriteria['nama'] }}</h3>
                            <p>Silakan lakukan pengisian data dengan cara memilih salah satu kondisi yang tepat, dan unggah maksimal dua foto</p>
                            <hr/>


                            @foreach ($subkriteria as $item)
                            <div class="form-group mb-3">
                                <label>{{$item->nama}}</label>
                                {{-- @php
                                    echo "<pre>";
                                    print_r($item);
                                @endphp --}}
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-1" value="Jawaban 1" {{ ($item->evaluasi == 'Jawaban 1') ? 'checked' : '' }} name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-1">Jawaban 1</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-2" value="Jawaban 2" {{ ($item->evaluasi == 'Jawaban 2') ? 'checked' : '' }} name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-2">Jawaban 2</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-3" value="Jawaban 3" {{ ($item->evaluasi == 'Jawaban 3') ? 'checked' : '' }} name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-3">Jawaban 3</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-4" value="Jawaban 4" {{ ($item->evaluasi == 'Jawaban 4') ? 'checked' : '' }} name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-4">Jawaban 4</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-5" value="Jawaban 5" {{ ($item->evaluasi == 'Jawaban 5') ? 'checked' : '' }} name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-5">Jawaban 5</label>
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

                                        <a href="{{ route('evaluasi.delete.foto', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['next'], 'id' => $val->id ]) }}" class="btn btn-danger btn-sm float-right ml-2">
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
                                                    <img src="{{ asset($val->foto) }}" class="img-fluid"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>

                        <div class="mt-10">
                            {{-- <a href="{{ route('evaluasi.create.next', $data['next']) }}" class="btn btn-primary float-right">
                                Selanjutnya
                            </a> --}}

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
                                {{-- <a href="{{ route('evaluasi.create.kriteria', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['next']]) }}" class="btn btn-primary float-right">
                                    Selanjutnya
                                </a> --}}
                                <button type="submit" id="submit" class="btn btn-primary float-right">
                                    {{-- <i class="mr-1 fa fa-soldi fa-save"></i> --}}
                                    {{-- @lang('crud.common.create') --}}
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
