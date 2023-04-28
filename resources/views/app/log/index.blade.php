<x-app-layout>

    @push('styles')
    <style>
        .timeline {
            margin: 0 0 45px;
            padding: 0;
            position: relative;
        }

        .timeline::before {
            border-radius: 0.25rem;
            background-color: #dee2e6;
            bottom: 0;
            content: "";
            left: 31px;
            margin: 0;
            position: absolute;
            top: 0;
            width: 4px;
        }

        .timeline > div {
            margin-bottom: 15px;
            margin-right: 10px;
            position: relative;
        }

        .timeline > div::before, .timeline > div::after {
            content: "";
            display: table;
        }

        .timeline > div > .timeline-item {
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
            border-radius: 0.25rem;
            background-color: #fff;
            color: #495057;
            margin-left: 60px;
            margin-right: 15px;
            margin-top: 0;
            padding: 0;
            position: relative;
        }

        .timeline > div > .timeline-item > .time {
            color: #999;
            float: right;
            font-size: 12px;
            padding: 10px;
        }

        .timeline > div > .timeline-item > .timeline-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            color: #495057;
            font-size: 16px;
            line-height: 1.1;
            margin: 0;
            padding: 10px;
        }

        .timeline > div > .timeline-item > .timeline-header > a {
            font-weight: 600;
        }

        .timeline > div > .timeline-item > .timeline-body,
        .timeline > div > .timeline-item > .timeline-footer {
            padding: 10px;
        }

        .timeline > div > .timeline-item > .timeline-body > img {
            margin: 10px;
        }

        .timeline > div > .timeline-item > .timeline-body > dl,
        .timeline > div > .timeline-item > .timeline-body ol,
        .timeline > div > .timeline-item > .timeline-body ul {
            margin: 0;
        }

        .timeline > div > .timeline-item > .timeline-footer > a {
        color: #fff;
        }

        .timeline > div > .fa,
        .timeline > div > .fas,
        .timeline > div > .far,
        .timeline > div > .fab,
        .timeline > div > .fal,
        .timeline > div > .fad,
        .timeline > div > .svg-inline--fa,
        .timeline > div > .ion {
            background-color: #adb5bd;
            border-radius: 50%;
            font-size: 16px;
            height: 30px;
            left: 18px;
            line-height: 30px;
            position: absolute;
            text-align: center;
            top: 0;
            width: 30px;
        }

        .timeline > div > .svg-inline--fa {
            padding: 7px;
        }

        .timeline > .time-label > span {
            border-radius: 4px;
            background-color: #fff;
            display: inline-block;
            font-weight: 600;
            padding: 5px;
        }

        .timeline-inverse > div > .timeline-item {
            box-shadow: none;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }

        .timeline-inverse > div > .timeline-item > .timeline-header {
            border-bottom-color: #dee2e6;
        }

        .dark-mode .timeline::before {
            background-color: #6c757d;
        }

        .dark-mode .timeline > div > .timeline-item {
            background-color: #343a40;
            color: #fff;
            border-color: #6c757d;
        }

        .dark-mode .timeline > div > .timeline-item > .timeline-header {
            color: #ced4da;
            border-color: #6c757d;
        }

        .dark-mode .timeline > div > .timeline-item > .time {
            color: #ced4da;
        }

        .timeline > .time-label > span {
            border-radius: 4px;
            background-color: #fff;
            display: inline-block;
            font-weight: 600;
            padding: 5px;
        }
    </style>
    @endpush

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Log Aktivitas</h1>
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="">Home</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active" aria-current="page">Log Aktivitas</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header py-3">
                    <form class="">
                        <div class="row">

                            <div class="col">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select class="select2-single form-control" name="district" onchange="submit()">
                                        @if ($select['district'] == 1)
                                            <option value="semua">Semua Kecamatan</option>
                                        @endif
                                        @foreach ($district as $val)
                                            <option value="{{$val->code}}" {{ (Request::get('district') == $val->code) ? 'selected' : ''}}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Desa</label>
                                    <select class="select2-single form-control" name="village" onchange="submit()">
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

                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Awal</label>
                                    <input type="date" name="start_date" value="{{ $select['start_date'] }}" class="form-control">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" name="end_date" value="{{ $select['end_date'] }}" class="form-control" onchange="submit()">
                                </div>
                            </div>

                        </div>


                    </form>
                </div>
                <div class="card-body">

                    @forelse ($log as $item)

                    <div class="timeline">

                        <div class="time-label">
                            <span class="bg-primary text-white px-3">{{$item->year}}</span>
                        </div>

                        @foreach ($item->data as $val)

                        <div>
                            <i class="fas fa-circle text-white bg-primary"></i>
                            <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{$val->tanggal}}</span>
                            <h3 class="timeline-header"><a href="#">{{$val->name}}</a></h3>

                            <div class="timeline-body">
                                {{$val->keterangan}}
                            </div>
                            <div class="timeline-footer">
                                <span><i class="fa fa-id-badge text-primary"></i> <a style="font-size: 14px">Petugas {{ $val->petugas }}</a></span>
                            </div>
                            </div>
                        </div>

                        @endforeach

                    </div>

                    @empty

                    <div class="text-center">
                        <h4>Data belum ada</h4>
                    </div>

                    @endforelse


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
