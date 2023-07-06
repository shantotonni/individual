@extends('layouts.master')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h4 class="page-title">KPI Info</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">KPI Info</a></li>
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
                                    <a href="{{ route('create.kpi.info') }}" class="btn btn-success btn-sm" style="color: white">
                                        <i class="fas fa-plus"></i>
                                        Add KPI
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>KPI Code</th>
                                            <th>KPI Name</th>
                                            <th>Business Name</th>
                                            <th>Unit Of Measure</th>
                                            <th>Goodness Indicator</th>
                                            <th>Weightage</th>
                                            <th>Active</th>
                                            <th>ResponsiblePerson</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($kpi_list as $key => $kpi)
                                        <tr>
                                            <th scope="row">{{ ++$key }}</th>
                                            <td>{{ $kpi->KPICode }}</td>
                                            <td>{{ $kpi->KPIName }}</td>
                                            <td>{{ $kpi->BusinessName }}</td>
                                            <td>{{ $kpi->UnitOfMeasure }}</td>
                                            <td>{{ $kpi->GoodnessIndictaor }}</td>
                                            <td>{{ $kpi->Weightage }}</td>
                                            <td>{{ $kpi->Active }}</td>
                                            <td>{{ $kpi->ResponsiblePerson }}</td>
                                            <td>
                                                <a href="{{ route('edit.kpi.info',$kpi->KPICode) }}" class="btn btn-success btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $kpi_list->links() }}
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


