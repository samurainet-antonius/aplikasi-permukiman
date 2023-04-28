<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('crud.staff.profile_title')</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Setting</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <dl class="row">

                            <dt class="col-sm-3">Email</dt>
                            <dd class="col-sm-9">
                                <input type="email" name="email" class="form-control" value="{{ $user->email ?? '-' }}" required>
                            </dd>

                            <dt class="col-sm-3">Password</dt>
                            <dd class="col-sm-9">
                                <input type="password" name="password" class="form-control">
                                <span class="text-danger">*Apabila password tidak ubah, mohon dikosongkan</span>
                            </dd>

                            <dt class="col-sm-3"></dt>
                            <dd class="col-sm-9">
                                <button class="btn btn-sm btn-success">Simpan</button>
                            </dd>
                        </dl>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
