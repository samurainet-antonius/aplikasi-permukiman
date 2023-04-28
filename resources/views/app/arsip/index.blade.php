<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.arsip.index_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Arsip evaluasi</li>
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
                    <form action="{{ route('arsip.index') }}" class="float-left">
                        <div class="form-group">
                            <select class="select2-single form-control" name="tahun" id="tahun" onchange="submit()">
                                @for($i=date("Y")-1;$i>="2015";$i--)
                                    <option value="{{ $i; }}" {{ (Request::get('tahun') == $i) ? 'selected' : ''}}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('crud.arsip.inputs.no')</th>
                                <th>@lang('crud.arsip.inputs.provinsi')</th>
                                <th>@lang('crud.arsip.inputs.kota')</th>
                                <th>@lang('crud.arsip.inputs.kecamatan')</th>
                                <th>@lang('crud.arsip.inputs.desa')</th>
                                <th>@lang('crud.arsip.inputs.status')</th>
                                <th>@lang('crud.arsip.inputs.created_at')</th>
                                <th>@lang('crud.arsip.inputs.updated_at')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($evaluasi as $key => $value)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $value->province->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->city->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->district->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->village->name ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->status->nama ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->created_at ?? '-' }}
                                </td>
                                <td>
                                    {{ $value->updated_at ?? '-' }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        @can('view', $value)
                                        <a href="{{ route('evaluasi.show', $value) }}" class="mr-1 btn btn-secondary btn-sm">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                        @endcan @can('delete', $value)
                                        <form action="{{ route('evaluasi.destroy', $value) }}" method="POST"
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
                        {!! $evaluasi->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
