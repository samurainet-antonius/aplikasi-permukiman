<x-guest-layout>
    <div class="row bg-white" style="height: 100vh !important;">
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
            <div class="row justify-content-center" style="margin-top: 7vh !important;">
                <div class="container col-10 col-md-10 col-lg-8 col-xl-6">
                    <form class="myForm user" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div>
                            <h5>Halo !</h5>
                            @php
                                $says = says();
                            @endphp
                            <h5 class="text-green font-weight-bold">{{ $says }}</h5>
                        </div>

                        <div class="text-center my-4">
                            <h4 class=""><span class="font-weight-bold text-green">Buat Akun</span> untuk dapat masuk</h4>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="district">Kecamatan</label>
                                    <select class="select2-single form-control" name="district" id="district">
                                        <option>pilih kecamatan</option>
                                        @foreach ($district as $val)
                                            <option value="{{ $val->code }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="desa">Desa</label>
                                    <select class="select2-single form-control" name="village" id="village">
                                        <option>pilih desa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input class="form-control input-lg" type="text" name="jabatan" placeholder="masukan jabatan" />
                        </div>
                        <div class="form-group">
                            <label for="nomer_hp">Nomer Whatsapp</label>
                            <input class="form-control input-lg" type="number" name="nomer_hp" placeholder="masukan nomer whatsapp" />
                        </div>
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input class="form-control input-lg" type="text" name="name" id="name" :value="old('name')" placeholder="masukan username" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control input-lg" type="email" name="email" id="email" :value="old('email')" placeholder="masukan email" />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control input-lg" type="password" name="password" placeholder="masukan password" />
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn bg-green text-white col-12 mt-3" value="Buat Akun" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

    <script type="text/javascript">

        $(document).ready(function() {
            $('#district').change(function() {
                $.ajax({
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route("village.select") }}',
                    data: {district_code: $("#district :selected").val()},
                    success: function(data) {
                        $('#village').empty();
                        $('#village').append('<option>pilih desa</option>');
                        $.each(data.data,function(index,val){
                            $('#village').append('<option value="'+val.code+'">'+val.name+'</option>');
                        })
                    }
                });
            });
        });

    </script>

    @endpush


</x-guest-layout>
