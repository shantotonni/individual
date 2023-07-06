@extends('layouts.master')
@push('css')
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
@endpush
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">KPI Info Edit</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">KPI info Edit</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <div class="row">
                                        <div class="col-md-2">

                                        </div>
                                    </div>
                                </div>
                                <div class="card-tools">
                                    <a href="{{ route('kpiData.list') }}" class="btn btn-info btn-sm" style="color: white">
                                        <i class="fas fa-arrow-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('update.kpi.info',$kpi_info->KPICode) }}" method="post">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>KPI Name</label>
                                            <input class="form-control" name="KPIName" type="text" value="{{ $kpi_info->KPIName }}" id="KPIName" placeholder="Enter KPI Name">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Business</label>
                                            <input class="form-control" type="text" readonly value="{{ $kpi_info->BusinessName }}" id="KPIName" placeholder="Enter KPI Name">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Unit Of Measure</label>
                                            <select name="UnitOfMeasure" id="UnitOfMeasure" class="form-control">
                                                @foreach($UnitOfMeasure as $key => $Unit)
                                                    @if($kpi_info->UnitOfMeasure == $Unit->UnitOfMeasure)
                                                        <option value="{{ $Unit->UnitOfMeasure }}" selected>{{ $Unit->UnitOfMeasure }}</option>
                                                    @else
                                                        <option value="{{ $Unit->UnitOfMeasure }}">{{ $Unit->UnitOfMeasure }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Goodness Indicator</label>
                                            <select name="GoodnessIndictaor" id="GoodnessIndictaor" class="form-control">
                                                @if($kpi_info->GoodnessIndictaor == 'H')
                                                    <option value="H" selected>High</option>
                                                    <option value="L">Low</option>
                                                @else
                                                    <option value="H">High</option>
                                                    <option value="L" selected>Low</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Weightage</label>
                                            <input class="form-control" name="Weightage" type="text" value="{{ $kpi_info->Weightage }}" placeholder="Enter Weightage">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Active</label>
                                            <select name="Active" id="Active" class="form-control">
                                                @if($kpi_info->Active == 'Y')
                                                    <option value="Y" selected>Active</option>
                                                    <option value="N">Inactive</option>
                                                @else
                                                    <option value="Y">Active</option>
                                                    <option value="N" selected>Inactive</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>

    <script>
        $(document).ready(function() {

            $(function() {
                $( "#my_date_picker" ).datepicker({
                    dateFormat: 'yymm',
                });
            });
        })
    </script>
@endpush


