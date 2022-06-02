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

                            <div class="col-4">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select class="select2-single form-control" name="district_code" id="district" onchange="submit()">
                                        <option>Pilih Kecamatan</option>
                                        @foreach ($district as $val)
                                            <option value="{{$val->code}}" {{ (Request::get('district') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>Desa</label>
                                    <select class="select2-single form-control" name="village_code" id="{{ $village == '' ? 'village' : '' }}">
                                        <option>Pilih Desa</option>

                                        @if ($village)
                                            @foreach ($village as $val)
                                                <option value="{{$val->code}}" {{ (Request::get('district') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>Rentang Tahun</label>
                                    <select class="select2-single form-control" name="years" id="years" id="select2Single" onchange="submit()">
                                        <option value="5">5 Tahun</option>
                                        <option value="4">4 Tahun</option>
                                        <option value="3">3 Tahun</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>

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


    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
            // var userData = <?php echo json_encode(['5','10']) ?>;
            var data = <?php echo json_encode($data); ?>;
            var years = <?php echo json_encode($years); ?>;
            console.log(years);
            Highcharts.chart('container', {
                title: {
                    text: ''
                },
                xAxis: {
                    crosshair: {
                        enabled: true,
                    },
                    categories: years,
                },
                yAxis: {
                    title: {
                        text: ''
                    }
                },
                chart: {
                    marginBottom: 100,
                    events: {
                        click: function(e) {
                            // alert(this.series[0].searchPoint(e, true).category);
                            // var x = new Date();
                            // var myHeading = "<p>I Am Added Dynamically </p>";
                            // $("#cek").html(myHeading + x);
                            // $('#myModal').modal('show');
                            pie()
                        }
                    }
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
                tooltip: {
                    shared: true
                },
                series: data,
                    // [
                    //     {
                    //         name: 'Tidak kumuh',
                    //         data: [43934, 52503, 57177, 69658, 97031]
                    //     },
                    //     {
                    //         name: 'Kumuh ringan',
                    //         data: [24916, 24064, 29742, 29851, 32490]
                    //     },
                    //     {
                    //         name: 'Kumuh sedang',
                    //         data: [11744, 17722, 16005, 19771, 20185]
                    //     },
                    //     {
                    //         name: 'Kumuh berat',
                    //         data: [null, null, 7988, 12169, 15112]
                    //     }
                    // ],
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






            function pie() {
                $('#myModal').modal('show');

                Highcharts.chart('pieChart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Browser market shares. January, 2018'
                    },
                    subtitle: {
                        text: 'Click the slices to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
                    },

                    accessibility: {
                        announceNewData: {
                            enabled: true
                        },
                        point: {
                            valueSuffix: '%'
                        }
                    },

                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    },

                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
                    },

                    series: [
                        {
                            name: "Browsers",
                            colorByPoint: true,
                            data: [
                                {
                                    name: "Chrome",
                                    y: 62.74,
                                    drilldown: "Chrome"
                                },
                                {
                                    name: "Firefox",
                                    y: 10.57,
                                    drilldown: "Firefox"
                                },
                                {
                                    name: "Internet Explorer",
                                    y: 7.23,
                                    drilldown: "Internet Explorer"
                                },
                                {
                                    name: "Safari",
                                    y: 5.58,
                                    drilldown: "Safari"
                                }
                            ]
                        }
                    ],
                    drilldown: {
                        series: [
                            {
                                name: "Chrome",
                                id: "Chrome",
                                data: [
                                    [
                                        "v65.0",
                                        0.1
                                    ],
                                    [
                                        "v64.0",
                                        1.3
                                    ],
                                    [
                                        "v63.0",
                                        53.02
                                    ],
                                    [
                                        "v62.0",
                                        1.4
                                    ],
                                    [
                                        "v61.0",
                                        0.88
                                    ],
                                    [
                                        "v60.0",
                                        0.56
                                    ],
                                    [
                                        "v59.0",
                                        0.45
                                    ],
                                    [
                                        "v58.0",
                                        0.49
                                    ],
                                    [
                                        "v57.0",
                                        0.32
                                    ],
                                    [
                                        "v56.0",
                                        0.29
                                    ],
                                    [
                                        "v55.0",
                                        0.79
                                    ],
                                    [
                                        "v54.0",
                                        0.18
                                    ],
                                    [
                                        "v51.0",
                                        0.13
                                    ],
                                    [
                                        "v49.0",
                                        2.16
                                    ],
                                    [
                                        "v48.0",
                                        0.13
                                    ],
                                    [
                                        "v47.0",
                                        0.11
                                    ],
                                    [
                                        "v43.0",
                                        0.17
                                    ],
                                    [
                                        "v29.0",
                                        0.26
                                    ]
                                ]
                            },
                            {
                                name: "Firefox",
                                id: "Firefox",
                                data: [
                                    [
                                        "v58.0",
                                        1.02
                                    ],
                                    [
                                        "v57.0",
                                        7.36
                                    ],
                                    [
                                        "v56.0",
                                        0.35
                                    ],
                                    [
                                        "v55.0",
                                        0.11
                                    ],
                                    [
                                        "v54.0",
                                        0.1
                                    ],
                                    [
                                        "v52.0",
                                        0.95
                                    ],
                                    [
                                        "v51.0",
                                        0.15
                                    ],
                                    [
                                        "v50.0",
                                        0.1
                                    ],
                                    [
                                        "v48.0",
                                        0.31
                                    ],
                                    [
                                        "v47.0",
                                        0.12
                                    ]
                                ]
                            },
                            {
                                name: "Internet Explorer",
                                id: "Internet Explorer",
                                data: [
                                    [
                                        "v11.0",
                                        6.2
                                    ],
                                    [
                                        "v10.0",
                                        0.29
                                    ],
                                    [
                                        "v9.0",
                                        0.27
                                    ],
                                    [
                                        "v8.0",
                                        0.47
                                    ]
                                ]
                            },
                            {
                                name: "Safari",
                                id: "Safari",
                                data: [
                                    [
                                        "v11.0",
                                        3.39
                                    ],
                                    [
                                        "v10.1",
                                        0.96
                                    ],
                                    [
                                        "v10.0",
                                        0.36
                                    ],
                                    [
                                        "v9.1",
                                        0.54
                                    ],
                                    [
                                        "v9.0",
                                        0.13
                                    ],
                                    [
                                        "v5.1",
                                        0.2
                                    ]
                                ]
                            },
                        ]
                    },



                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                            // chartOptions: {
                            //     legend: {
                            //         layout: 'horizontal',
                            //         align: 'center',
                            //         verticalAlign: 'bottom'
                            //     }
                            // }
                        }]
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
