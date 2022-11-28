<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand bg-primary d-flex align-items-center justify-content-center"
        href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/img/logo/logo1.png') }}">
        </div>
        <div class="sidebar-brand-text mx-3">SI-IPEH</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Features
    </div>

    @if (Auth::user()->can('view-any', App\Models\Evaluasi::class))

    <li class="nav-item">
        <a class="nav-link" href="{{ route('evaluasi.index') }}">
            <i class="fab fa-fw fa-wpforms"></i>
            <span>Evaluasi</span>
        </a>
    </li>

    @endif

    <li class="nav-item">
        <a class="nav-link" href="{{ route('arsip.index') }}">
            <i class="fas fa-fw fa-file-archive"></i>
            <span>Riwayat</span>
        </a>
    </li>

    @if (Auth::user()->can('view-any', App\Models\Log::class))

    <li class="nav-item">
        <a class="nav-link" href="{{ route('log.index') }}">
            <i class="fa fa-fw fa-clipboard-list"></i>
            <span>Log Aktivitas</span>
        </a>
    </li>

    @endif

    @if (Auth::user()->can('view-any', App\Models\Petugas::class) || Auth::user()->can('view-any',
    App\Models\Kriteria::class) || Auth::user()->can('view-any', App\Models\SubKriteria::class) ||
    Auth::user()->can('view-any', App\Models\StatusKumuh::class))

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTableMaster"
            aria-expanded="true" aria-controls="collapseTableMaster">
            <i class="fas fa-fw fa-database"></i>
            <span>Master</span>
        </a>
        <div id="collapseTableMaster" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                @can('view-any', App\Models\Petugas::class)
                <a class="collapse-item" href="{{ route('staff.index') }}">Petugas</a>
                @endcan

                @can('view-any', App\Models\Kriteria::class)
                <a class="collapse-item" href="{{ route('kriteria.index') }}">Kriteria</a>
                @endcan

                {{-- @can('view-any', App\Models\SubKriteria::class)
                <a class="collapse-item" href="{{ route('subkriteria.index') }}">SubKriteria</a>
                @endcan --}}

                @can('view-any', App\Models\StatusKumuh::class)
                <a class="collapse-item" href="{{ route('statuskumuh.index') }}">Status Kumuh</a>
                @endcan

                @can('view-any', App\Models\StatusKriteria::class)
                <a class="collapse-item" href="{{ route('statuskriteria.index') }}">Status Kriteria</a>
                @endcan

            </div>
        </div>
    </li>
    @endif

    @if (Auth::user()->can('view-any', App\Models\User::class) || Auth::user()->can('view-any',
    Spatie\Permission\Models\Role::class) || Auth::user()->can('view-any', Spatie\Permission\Models\Permission::class)
    || Auth::user()->can('view-any',App\Models\Province::class) || Auth::user()->can('view-any',
    App\Models\City::class) || Auth::user()->can('view-any',App\Models\District::class) ||
    Auth::user()->can('view-any',App\Models\Village::class))
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
            aria-controls="collapseTable">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pengaturan</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                @can('view-any', App\Models\User::class)
                <a class="collapse-item" href="{{ route('users.index') }}">Akun</a>
                @endcan

                @can('view-any', Spatie\Permission\Models\Role::class)
                <a class="collapse-item" href="{{ route('roles.index') }}">Otoritas</a>
                @endcan

                @can('view-any', Spatie\Permission\Models\Permission::class)
                <a class="collapse-item" href="{{ route('permissions.index') }}">Hak Akses</a>
                @endcan

                @can('view-any', App\Models\District::class)
                <a class="collapse-item" href="{{ route('district.index') }}">Kecamatan</a>
                @endcan

                @can('view-any', App\Models\Village::class)
                <a class="collapse-item" href="{{ route('village.index') }}">Desa</a>
                @endcan

                @can('view-any', App\Models\Setting::class)
                <a class="collapse-item" href="{{ route('setting.index') }}">Website</a>
                @endcan

            </div>
        </div>
    </li>
    @endif

</ul>
