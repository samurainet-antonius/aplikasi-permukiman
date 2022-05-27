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
                    <form method="POST" action="{{ route('evaluasi.store.kriteria', ['evaluasi_id' => $data['evaluasi'], 'page' => $data['next']]) }}" has-files class="mt-4">
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
                                    <input type="radio" id="{{$item->id}}-1" value="Jawaban 1" @php ($item->evaluasi == 'Jawaban 1' ? 'checked' : '') @endphp name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-1">Jawaban 1</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-2" value="Jawaban 2" @php ($item->evaluasi == 'Jawaban 2' ? 'checked' : '') @endphp name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-2">Jawaban 2</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-3" value="Jawaban 3" @php ($item->evaluasi == 'Jawaban 3' ? 'checked' : '') @endphp name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-3">Jawaban 3</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-4" value="Jawaban 4" @php ($item->evaluasi == 'Jawaban 4' ? 'checked' : '') @endphp name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-4">Jawaban 4</label>
                                </div>
                                <div class="custom-control custom-radio mt-3">
                                    <input type="radio" id="{{$item->id}}-5" value="Jawaban 5" {{ ($item->evaluasi == 'Jawaban 5') ? 'checked' : '' }} name="jawaban[{{$kriteria['id']}}][{{$item->id}}]" class="custom-control-input">
                                    <label class="custom-control-label" for="{{$item->id}}-5">Jawaban 5</label>
                                </div>
                            </div>
                            @endforeach


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
                                <button type="submit" class="btn btn-primary float-right">
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
</x-app-layout>
