<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
          <img src="img/logo/logo2.png">
        </div>
        <div class="sidebar-brand-text mx-3">AIP</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Features
      </div>

      @if (Auth::user()->can('view-any', Spatie\Permission\Models\Role::class) || Auth::user()->can('view-any', Spatie\Permission\Models\Permission::class))

      <li class="nav-item">
        <a class="nav-link" href="{{ route('evaluasi.index') }}">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Evaluasi</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTableMaster" aria-expanded="true"
          aria-controls="collapseTableMaster">
          <i class="fas fa-fw fa-database"></i>
          <span>Master</span>
        </a>
        <div id="collapseTableMaster" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            @can('view-any', Spatie\Permission\Models\Kriteria::class)
                <a class="collapse-item" href="{{ route('kriteria.index') }}">Kriteria</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\SubKriteria::class)
                <a class="collapse-item" href="{{ route('subkriteria.index') }}">SubKriteria</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\StatusKumuh::class)
                <a class="collapse-item" href="{{ route('statuskumuh.index') }}">Status Kumuh</a>
            @endcan

          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-cog"></i>
          <span>Setting</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">

            @can('view-any', App\Models\User::class)
                <a class="collapse-item" href="{{ route('users.index') }}">Users</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\Role::class)
                <a class="collapse-item" href="{{ route('roles.index') }}">Roles</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\Permission::class)
                <a class="collapse-item" href="{{ route('permissions.index') }}">Permissions</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\Province::class)
                <a class="collapse-item" href="{{ route('province.index') }}">Province</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\City::class)
                <a class="collapse-item" href="{{ route('city.index') }}">City</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\District::class)
                <a class="collapse-item" href="{{ route('district.index') }}">District</a>
            @endcan

            @can('view-any', Spatie\Permission\Models\Village::class)
                <a class="collapse-item" href="{{ route('village.index') }}">Village</a>
            @endcan

          </div>
        </div>
      </li>

      @endif
    </ul>
