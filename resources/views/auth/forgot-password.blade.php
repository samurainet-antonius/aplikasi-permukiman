<x-guest-layout>
    <div class="row bg-white container-fluid" style="height: 100vh !important;">
        <div class="d-none d-sm-none d-md-block  col-4 col-md-5 col-lg-5 col-xl-4" style="height: 100vh !important; padding: 0 !important;">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/login.png') }}" class="img-fluid ml-n3" style="height: 100% !important; " alt="">
            </a>
        </div>
        <div class="col-12 col-md-7 col-lg-7 col-xl-8">
            <div class="row justify-content-center" style="margin-top: 25vh !important;">
                <div class="container col-10 col-md-10 col-lg-8 col-xl-6">
                    <form class="myForm user" method="post" action="{{ route('password.email') }}">
                        @csrf
                        <div>
                            @php
                                $says = says();
                            @endphp
                            <h5>Halo !</h5>
                            <h5 class="text-green font-weight-bold">{{ $says }}</h5>
                        </div>

                        <div class="text-center my-5">
                            <h4 class=""><span class="font-weight-bold text-green">Dapatkan kunci</span> ke akun anda</h4>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control input-lg" type="email" name="email" id="email" placeholder="email" />
                        </div>
                        <div class="form-group">
                            {!! htmlFormSnippet() !!}
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn bg-green text-white col-12" value="Kirim" />
                        </div>
                        <div class="text-center">
                            <a class="small text-dark" href="{{ route('login') }}">Anda sudah punya akun? klik untuk login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
