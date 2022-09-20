<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            {{-- <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Evaluasi</li> --}}
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">

                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select class="select2-single form-control" name="district_code" onchange="submit()">
                                        <option value="null">Pilih Kecamatan</option>
                                        @foreach ($district as $val)
                                            <option value="{{$val->code}}" {{ (Request::get('district_code') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Desa</label>
                                    <select class="select2-single form-control" name="village_code" onchange="submit()">

                                        @if ($village)
                                            <option value="null">Pilih Desa</option>
                                            @foreach ($village as $val)
                                                <option value="{{$val->code}}"  {{ (Request::get('village_code') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                            @endforeach
                                        @else
                                            <option value="null">Pilih kecamatan dahulu</option>
                                        @endif

                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select class="select2-single form-control" name="years" id="tahun" onchange="submit()">
                                        @for($i=date("Y");$i>="2015";$i--)
                                            <option value="{{ $i; }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label>Rentang</label>
                                    <select class="select2-single form-control" name="range" onchange="submit()">
                                        <option value="12" {{ (Request::get('range') == 12) ? 'selected' : ''}}>1 Tahun</option>
                                        <option value="9" {{ (Request::get('range') == 9) ? 'selected' : ''}}>9 Bulan</option>
                                        <option value="6" {{ (Request::get('range') == 6) ? 'selected' : ''}}>6 Bulan</option>
                                        <option value="3" {{ (Request::get('range') == 3) ? 'selected' : ''}}>3 Bulan</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>

                    <div class="mt-3">
                        <div class="text-center">
                            <h6 class="font-weight-bold">Status Kumuh</h6>
                            <h6 class="font-weight-bold">{{$text['district']}} {{$text['village']}}</h6>
                            <h6>{{$text['years']}}</h6>
                            <h6>Dinas Perumahan dan Kawasan Permukiman Serta Pertanahan Kabupaten Deli Serdang Sumatera Utara</h6>
                        </div>
                        <div id="container"></div>
                        <div class="text-center row mx-5">
                            @foreach ($status as $item)
                                <div class="col"><i class="fas fa-circle" style="color: {{$item->warna}};"></i> {{$item->nama}}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" style="margin-bottom: -20px;">
                    <p>Presentase Status Kumuh<br/>
                    {{$text['district']}}<br/>
                    {{$text['village']}}<br/>
                    Kabupaten Deli Serdang</p>
                    {{-- <p>Tahun </p> --}}
                </div>
                <div id="pieChart"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript">

            var data = <?php echo json_encode($data); ?>;
            var bulan = <?php echo json_encode($bulan); ?>;
            var labelData = ['cek', 'nama'];

            var district = <?php echo json_encode($req['district']); ?>;
            var village = <?php echo json_encode($req['village']); ?>;
            var years = <?php echo json_encode($req['years']); ?>;

            Highcharts.chart('container', {
                chart: {
                    type: 'column',
                    events: {
                        click: function(e) {
                            var link = '{{ route("dashboard.detail", ["district_code" => ":district", "village_code" => ":village", "years" => ":tahun", "month" => ":month"]) }}';
                            link = link.replace(':district', district);
                            link = link.replace(':village', village);
                            link = link.replace(':tahun', years);
                            link = link.replace(':month', this.series[0].searchPoint(e, true).category);
                            location.href = link
                            // console.log(this.series[0].searchPoint(e, true).category);
                        }
                    }
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                legend: {
                    enabled: false
                    // layout: 'horizontal',
                    // align: 'center',
                    // verticalAlign: 'bottom'
                },
                xAxis: {
                    categories: bulan,
                    // [
                    //     'Jan',
                    //     'Feb',
                    //     'Mar',
                    // ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: ''
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: data
                // [{
                //     name: 'Tokyo',
                //     data: [{
                //         y: 49.9,
                //         color: '#fff'
                //     }, {
                //         y: 71.5,
                //         color: '#fdfdfd'
                //     }, {
                //         y: 106.4,
                //         color: '#f3f3f3'
                //     }]

                // }, {
                //     name: 'New York',
                //     data: [83.6, 78.8, 98.5]

                // }, {
                //     name: 'London',
                //     data: [48.9, 38.8, 39.3]

                // }, {
                //     name: 'Berlin',
                //     data: [42.4, 33.2, 34.5]

                // }]
            });
        </script>
    @endpush
</x-app-layout>
