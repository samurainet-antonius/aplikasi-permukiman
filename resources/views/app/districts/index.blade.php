<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.districts.index_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">Setting</li>
            <li class="breadcrumb-item active" aria-current="page">Districts</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <form class="float-right">
                        <div class="input-group mb-3">
                        <input type="text" value="{{ $search ?? '' }}" name="search" class="form-control" placeholder="{{ __('crud.common.search') }}" aria-label="" aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <button class="btn btn-primary">
                                    <i class="fa fa-solid fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- @can('create', App\Models\Kriteria::class)
                    <a href="{{ route('kriteria.create') }}" class="btn btn-primary">
                        <i class="mr-1 fa fa-solid fa-plus"></i>
                        @lang('crud.common.create')
                    </a>
                    @endcan --}}
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('crud.districts.inputs.no')</th>
                                <th>@lang('crud.districts.inputs.province')</th>
                                <th>@lang('crud.districts.inputs.city')</th>
                                <th>@lang('crud.districts.inputs.name')</th>
                                <th>@lang('crud.districts.inputs.latitude')</th>
                                <th>@lang('crud.districts.inputs.longitude')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($districts as $key => $value)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $value->city->province->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->city->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->latitude ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->longitude ?? '-' }}
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
                    <div class="d-flex justify-content-end">
                        {!! $districts->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
