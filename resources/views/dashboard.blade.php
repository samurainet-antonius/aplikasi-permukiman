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
                    <div class="row">

                        <div class="col-4">
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <select class="select2-single form-control" name="province_code" id="province" id="select2Single" onchange="submit()">
                                    <option value="12">SUMATERA UTARA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Desa</label>
                                <select class="select2-single form-control" name="province_code" id="province" id="select2Single" onchange="submit()">
                                    <option value="12">SUMATERA UTARA</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label>Rentang Tahun</label>
                                <select class="select2-single form-control" name="province_code" id="province" id="select2Single" onchange="submit()">
                                    <option value="12">5</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3">
                        <div class="text-center">
                            <h6 class="font-weight-bold">Status Kumuh</h6>
                            <h6 class="font-weight-bold">Kecamatan Gunung Meriah Desa Bintang Meriah</h6>
                            <h6>Dalam 5 Tahun</h6>
                            <h6>Dinas Perkim Deli Serdang Sumatera Utara</h6>
                        </div>
                        <div id="container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script type="text/javascript">
            var userData = <?php echo json_encode(['5','10'])?>;
            Highcharts.chart('container', {
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                        'October', 'November', 'December'
                    ]
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                chart: {
                    marginBottom: 100
                },
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                        name: 'Installation',
                        data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
                    }, {
                        name: 'Manufacturing',
                        data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
                    }, {
                        name: 'Sales & Distribution',
                        data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
                    }, {
                        name: 'Project Development',
                        data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
                    }, {
                        name: 'Other',
                        data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
                    }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });
        </script>
    @endpush
</x-app-layout>
