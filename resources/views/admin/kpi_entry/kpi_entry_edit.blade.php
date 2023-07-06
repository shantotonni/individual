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
                        <h4 class="page-title">KPI Entry</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">KPI Entry</a></li>
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
                                    <a href="{{ route('kpi.entry.list') }}" class="btn btn-info btn-sm" style="color: white">
                                        <i class="fas fa-arrow-left"></i>
                                        Back
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('kpi.entry.update',$kpi_entry_data->KPIEntryMasterCode) }}" method="post">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <div class="offset-4 col-md-4">
                                        <div class="form-group">
                                            <label>Business</label>
                                            <select name="Business" id="KPICode" class="form-control">
                                                <option value="">Select Business</option>
                                                @foreach($businesses as $key => $business)
                                                    @if($business->BusinessName == $kpi_entry_data->BusinessName)
                                                        <option value="{{ $business->BusinessName }}" selected>{{ $business->BusinessName }}</option>
                                                    @else
                                                        <option value="{{ $business->BusinessName }}">{{ $business->BusinessName }}</option>
                                                    @endif

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="offset-4 col-md-4">
                                        <div class="form-group">
                                            <label>KPI</label>
                                            <select name="KPICode" id="KPICode" class="form-control">
                                                <option value="">Select KPI</option>
                                                @foreach($kpi_list as $key => $kpi)
                                                    @if($kpi->KPICode == $kpi_entry_data->kpi_details->KPICode)
                                                        <option value="{{ $kpi->KPICode }}" selected>{{ $kpi->KPIName }}</option>
                                                    @else
                                                        <option value="{{ $kpi->KPICode }}">{{ $kpi->KPIName }}</option>
                                                    @endif

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="offset-4 col-md-4">
                                        <div class="form-group">
                                            <label>Period</label>
                                            <input class="form-control" name="Period" value="{{ $kpi_entry_data->Period }}" type="text" id="my_date_picker">
                                        </div>
                                    </div>
                                    <div class="offset-4 col-md-4">
                                        <div class="form-group">
                                            <label>Value</label>
                                            <input type="number" class="form-control" name="Value" value="{{ $kpi_entry_data->kpi_details->Value }}" required="" placeholder="Enter Value">
                                        </div>
                                    </div>
                                    <div class="offset-4 col-md-4">
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


