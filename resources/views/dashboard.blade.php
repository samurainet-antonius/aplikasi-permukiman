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
                                    <select class="select2-single form-control" name="district_code" onchange="submit()">
                                        @if ($select['district'] == 1)
                                            <option value="semua">Semua Kecamatan</option>
                                        @endif
                                        @foreach ($district as $val)
                                            <option value="{{$val->code}}" {{ (Request::get('district_code') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>Desa</label>
                                    <select class="select2-single form-control" name="village_code" onchange="submit()">
                                        @if ($select['village'] == 1)
                                            <option value="semua">Semua Desa</option>
                                        @endif
                                        @if ($village)
                                            @foreach ($village as $val)
                                                <option value="{{$val->code}}"  {{ (Request::get('village_code') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="form-group">
                                    <label>Rentang Tahun</label>
                                    <select class="select2-single form-control" name="years" onchange="submit()">
                                        <option value="5" {{ (Request::get('years') == 5) ? 'selected' : ''}}>5 Tahun</option>
                                        <option value="4" {{ (Request::get('years') == 4) ? 'selected' : ''}}>4 Tahun</option>
                                        <option value="3" {{ (Request::get('years') == 3) ? 'selected' : ''}}>3 Tahun</option>
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
                            <h6>Dinas Perkim Deli Serdang Sumatera Utara</h6>
                        </div>
                        <div id="container"></div>
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
            // var userData = <?php echo json_encode(['5','10']) ?>;
            var data = <?php echo json_encode($data); ?>;
            var pieData = <?php echo json_encode($pie); ?>;
            var years = <?php echo json_encode($years); ?>;
            console.log(data);
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
                            pie(pieData[this.series[0].searchPoint(e, true).category], this.series[0].searchPoint(e, true).category)
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






            function pie(data, tahun) {
                var district = <?php echo json_encode($req['district']); ?>;
                var village = <?php echo json_encode($req['village']); ?>;
                var years = <?php echo json_encode($req['years']); ?>;


                $('#myModal').modal('show');

                Highcharts.chart('pieChart', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: ''
                    },
                    subtitle: {
                        text: 'Tahun '+tahun
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
                        },
                        series: {
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function (event) {
                                        var link = '{{ route("dashboard.detail", ["district_code" => ":district", "village_code" => ":village", "years" => ":tahun", "status_id" => ":id"]) }}';
                                        link = link.replace(':district', district);
                                        link = link.replace(':village', village);
                                        link = link.replace(':tahun', tahun);
                                        link = link.replace(':id', this.id);
                                        location.href = link
                                    }
                                }
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                    },

                    series: [
                        {
                            name: "Status Kumuh",
                            colorByPoint: true,
                            data: data
                        }
                    ],
                    responsive: {
                        rules: [{
                            condition: {
                                maxWidth: 500
                            },
                        }]
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
