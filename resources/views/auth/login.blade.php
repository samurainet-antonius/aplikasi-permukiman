<x-guest-layout>
    <div class="row bg-white container-fluid" style="height: 100vh !important;">
        <div class="d-none d-sm-none d-md-block  col-4 col-md-5 col-lg-5 col-xl-4"
            style="height: 100vh !important; padding: 0 !important;">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/login.png') }}" class="img-fluid ml-n3" style="height: 100% !important; "
                    alt="">
            </a>
        </div>
        <div class="col-12 col-md-7 col-lg-7 col-xl-8">
            <div class="row justify-content-center" style="margin-top: 25vh !important;">
                <div class="container col-10 col-md-10 col-lg-8 col-xl-6">
                    <form class="myForm user" method="post" action="{{ route('login-proses') }}">
                        @csrf
                        <div>
                            @php
                            $says = says();
                            @endphp
                            <h5>Halo !</h5>
                            <h5 class="text-green font-weight-bold">{{ $says }}</h5>
                        </div>

                        <div class="text-center my-5">
                            <h4 class=""><span class="font-weight-bold text-green">Masuk</span> ke akun anda</h4>
                        </div>

                        <div class="form-group">
                            <label for="email">Username/Email</label>
                            <input class="form-control input-lg" type="email" name="email" id="email"
                                placeholder="email" />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control input-lg" type="password" name="password"
                                placeholder="password" />
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                                        <input type="checkbox" class="custom-control-input" id="customCheck">
                                        <label class="custom-control-label" for="customCheck">Ingat akun saya</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        <a class="font-weight-bold text-dark"
                                            href="{{ route('password.request') }}">Lupa password ?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn bg-green text-white col-12" value="Masuk" />
                        </div>
                        <div class="text-center">
                            <a class="small text-dark" href="{{ route('register') }}">Anda belum punya akun? klik untuk
                                mendaftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
