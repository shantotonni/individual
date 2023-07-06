@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">KPI Entry List</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">KPI Entry List</a></li>
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
                                        <div class="col-md-6">
                                            <form action="{{ route('kpi.data.import') }}" method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group">
                                                            <input type="file" class="form-control" name="file">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <button class="btn btn-success" type="submit">Import</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-tools">
                                    <a href="{{ route('create.kpi.entry') }}" class="btn btn-success btn-sm" style="color: white">
                                        <i class="fas fa-plus"></i>
                                        Add KPI Data
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Business</th>
                                            <th>EmpCode</th>
                                            <th>Employee Name</th>
                                            <th>KPI Code</th>
                                            <th>KPI Name</th>
                                            <th>Period</th>
                                            <th>Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($result as $key => $kpi)
                                        <tr>
                                            <th scope="row">{{ ++$key }}</th>
                                            <td>{{ $kpi['Business'] }}</td>
                                            <td>{{ $kpi['EmpCode'] }}</td>
                                            <td>{{ $kpi['EmpName'] }}</td>
                                            <td>{{ $kpi['KPICode'] }}</td>
                                            <td>{{ $kpi['KPIName'] }}</td>
                                            <td>{{ $kpi['Period'] }}</td>
                                            <td class="text-right">{{ $kpi['Value'] }}</td>
                                            <td>
                                                <a href="{{ route('kpi.entry.edit',$kpi['KPIEntryMasterCode']) }}" class="btn btn-success btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush


