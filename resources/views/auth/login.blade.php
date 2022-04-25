<x-guest-layout>
    <div class="row">
        <div class="col-4" style="height: 100vh !important; padding: 0 !important;">
            <img src="{{ asset('assets/img/login1.png') }}" class="img" style="" alt="">

            <div class="section position-absolute" style="top: 25vh !important;">
                <div class="content">
                    <img src="{{ asset('assets/img/logo/logo1.png') }}" class="mx-auto d-block" height="100" alt="">
                </div>
            </div>
            <img src="{{ asset('assets/img/login2.png') }}" class="img" style="" alt="">
        </div>
        <div class="col-8 bg-white">
            <div class="">
                <div class="container">
                    <form class="myForm user" method="post" action="{{ route('login-proses') }}">
                        @csrf
                        <div>
                            <h5>Halo !</h5>
                            <h5 class="text-green font-weight-bold">Selamat Pagi</h5>
                        </div>

                        <div class="text-center my-5">
                            <h4 class=""><span class="font-weight-bold text-green">Masuk</span> ke akun anda</h4>
                        </div>

                        <div class="form-group">
                            <label for="email">Username/Email</label>
                            <input class="form-control input-lg" type="email" name="email" id="email" placeholder="email" />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control input-lg" type="password" name="password" placeholder="password" />
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                <label class="custom-control-label" for="customCheck">Ingat akun saya</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn bg-green text-white col-12" value="Masuk" />
                        </div>
                        <div class="text-center">
                            <a class="small text-dark" href="">Anda belum punya akun? klik untuk mendaftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
