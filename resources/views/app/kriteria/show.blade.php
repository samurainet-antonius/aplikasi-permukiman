<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.kriteria.index_title')</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Kriteria</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <h5>{{ $kriteria->nama }}</h5>
                    <hr>
                    <form class="float-right">
                        <div class="input-group mb-3">
                            <input type="text" value="{{ $search ?? '' }}" name="search" class="form-control"
                                placeholder="{{ __('crud.common.search') }}" aria-label=""
                                aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <button class="btn btn-primary">
                                    <i class="fa fa-solid fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('crud.kriteria.inputs.no')</th>
                                <th>@lang('crud.kriteria.inputs.nama')</th>
                                <th>@lang('crud.kriteria.inputs.satuan')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kriteria->subkriteria as $key => $value)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $value->nama ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->satuan ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <div class="mt-10">
                        <a href="{{ route('kriteria.index') }}" class="button">
                            <i class="mr-1 fa fa-solid fa-arrow-left text-primary"></i>
                            @lang('crud.common.back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
