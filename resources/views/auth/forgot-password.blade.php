<x-guest-layout>
    <div class="row bg-white container-fluid" style="height: 100vh !important;">
        <div class="d-none d-sm-none d-md-block  col-4 col-md-5 col-lg-5 col-xl-4" style="height: 100vh !important; padding: 0 !important;">
            <img src="{{ asset('assets/img/login1.png') }}" class="img" style="" alt="">

            <div class="section position-absolute top">
                <div class="content text-center">
                    <img src="{{ asset('assets/img/logo/logo1.png') }}" class="mx-auto d-block" height="100" alt="">
                    <h5>Dinas Perkim Deli Serdang <br> Sumatera Utara</h5>
                </div>
            </div>
            <img src="{{ asset('assets/img/login2.png') }}" class="img" style="" alt="">
        </div>
        <div class="col-12 col-md-7 col-lg-7 col-xl-8">
            <div class="row justify-content-center" style="margin-top: 25vh !important;">
                <div class="container col-10 col-md-10 col-lg-8 col-xl-6">
                    <form class="myForm user" method="post" action="{{ route('password.email') }}" id="{{ getFormId() }}">
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
