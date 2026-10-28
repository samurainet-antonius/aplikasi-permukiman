<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dasbor</h1>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <div class="card-title text-center">
                        <h6>Kriteria Kumuh</h6>
                        <h6>Kabupaten Deli Serdang</h6>
                        <h6>Kecamatan {{ $village->district->name }}</h6>
                        <h6>Desa {{ $village->name }}</h6>
                        <h6>Bulan {{ $month }} Tahun {{ $years }}</h6>
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


                </div>

                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kecamatan</th>
                                <th>Desa</th>
                                <th>Lingkungan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $value)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>
                                    {{ $value->district->name }}
                                </td>
                                <td>
                                    {{ $value->village->name }}
                                </td>
                                <td>
                                    {{ $value->lingkungan }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        @can('view', $value)
                                        <a href="{{ url('l-app/evaluasi').'/'.$value->id.'?bulan='.$date }}" class="mr-1 btn btn-secondary btn-sm">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
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
                        {{-- {!! $data->links() !!} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
