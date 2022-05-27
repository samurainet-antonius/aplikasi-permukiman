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
                    <form method="POST" action="{{ route('evaluasi.store') }}" has-files class="mt-4">
                        @include('app.evaluasi.form-inputs')

                        <div class="mt-10">
                            <a href="{{ route('evaluasi.index') }}" class="button">
                                <i class="
                                                mr-1
                                                fa fa-solid fa-arrow-left
                                                text-primary
                                            "></i>
                                @lang('crud.common.back')
                            </a>

                            <button type="submit" class="btn btn-primary float-right">
                                {{-- <i class="mr-1 fa fa-soldi fa-save"></i> --}}
                                {{-- @lang('crud.common.create') --}}
                                Selanjutnya
                            </button>

                            {{-- <a href="{{ route('evaluasi.create.kriteria', 0) }}" class="btn btn-primary float-right">
                                Selanjutnya
                            </a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
