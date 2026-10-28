<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.staff.show_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('setting-profil') }}" class="float-right"><i class="fa fa-pencil"></i> Edit</a>
                    <div class="clearfix"></div>
                    <dl class="row">
                        <dt class="col-sm-3">Nama lengkap</dt>
                        <dd class="col-sm-9">: {{ $petugas->user->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">: {{ $petugas->user->email ?? '-' }}</dd>

                        <dt class="col-sm-3">Jabatan</dt>
                        <dd class="col-sm-9">: {{ $petugas->jabatan ?? '-' }}</dd>

                        <dt class="col-sm-3">Provinsi</dt>
                        <dd class="col-sm-9">: {{ $petugas->province->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Kabupaten/Kota</dt>
                        <dd class="col-sm-9">: {{ $petugas->city->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Kecamatan</dt>
                        <dd class="col-sm-9">: {{ $petugas->district->name ?? '-' }}</dd>

                        <dt class="col-sm-3">Kelurahan/Desa</dt>
                        <dd class="col-sm-9">: {{ $petugas->village->name ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
