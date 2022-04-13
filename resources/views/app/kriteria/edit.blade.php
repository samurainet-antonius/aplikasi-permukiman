<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.kriteria.edit_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">
                <a href="{{ route('kriteria.index') }}">Kriteria</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('kriteria.update', $kriteria) }}" has-files class="mt-4">
                        @method('PUT')
                        @include('app.kriteria.form-inputs')

                        <div class="mt-10">
                            <a href="{{ route('kriteria.index') }}" class="button">
                                <i class="
                                                mr-1
                                                fa fa-solid fa-arrow-left
                                                text-primary
                                            "></i>
                                @lang('crud.common.back')
                            </a>

                            <button type="submit" class="btn btn-primary float-right">
                                <i class="mr-1 fa fa-soldi fa-save"></i>
                                @lang('crud.common.create')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
