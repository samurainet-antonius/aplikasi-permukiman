<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.village.index_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">Pengaturan</li>
            <li class="breadcrumb-item active" aria-current="page">@lang('crud.village.name')</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <div class="col-12">
                        <form action="" method="get">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>@lang('crud.village.inputs.province')</label>
                                        <select class="select2-single form-control" name="province" id="province" id="select2Single" onchange="submit()">
                                            <option value="12">SUMATERA UTARA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>@lang('crud.village.inputs.city')</label>
                                        <select class="select2-single form-control" name="city" id="city" onchange="submit()">
                                            @if ($city)
                                                @foreach ($city as $val)
                                                    <option value="{{$val->code}}" {{ (Request::get('1207') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                                @endforeach
                                            @else
                                                <option>-------</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>@lang('crud.village.inputs.districts')</label>
                                        <select class="select2-single form-control" name="district" id="district" onchange="submit()">
                                            @if ($district)
                                                @foreach ($district as $val)
                                                    <option value="{{$val->code}}" {{ (Request::get('120701') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                                @endforeach
                                            @else
                                                <option>-------</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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

                    @can('create', App\Models\Village::class)
                    <a href="{{ route('village.create') }}" class="btn btn-primary ml-3">
                        <i class="mr-1 fa fa-solid fa-plus"></i>
                        @lang('crud.common.create')
                    </a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('crud.village.inputs.no')</th>
                                <th>@lang('crud.village.inputs.province')</th>
                                <th>@lang('crud.village.inputs.city')</th>
                                <th>@lang('crud.village.inputs.districts')</th>
                                <th>@lang('crud.village.inputs.name')</th>
                                <th>@lang('crud.village.inputs.code')</th>
                                <th>@lang('crud.village.inputs.latitude')</th>
                                <th>@lang('crud.village.inputs.longitude')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($village as $key => $value)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $value->district->city->province->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->district->city->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->district->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->code ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->latitude ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->longitude ?? '-' }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        @can('update', $value)
                                        <a href="{{ route('village.edit', $value) }}" class="mr-1 btn btn-warning btn-sm">
                                            <i class="fa fa-solid fa-pen"></i>
                                        </a>
                                        @endcan @can('delete', $value)
                                        <form action="{{ route('village.destroy', $value) }}" method="POST"
                                            onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
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
                        {!! $village->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
