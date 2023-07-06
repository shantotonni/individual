<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>KPI Report</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" crossorigin="anonymous">
    <script src="{{ asset('js/bootstrap.min.js') }}" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body style="background-color: #e9e9e9" id="wrapper">
<div class="container-fluid">
    <br>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered table-hover  table-striped">
                <tr>
                    <th class="bold" style="font-size: 20px;text-align: center">
                        KPI Dashboard
                    </th>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">&nbsp;</div>
    </div>
    <form method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <select class="form-control" id="business" name="business">
                        @foreach($businesses as $business)
                            <option value="{{ $business->BusinessName }}">{{ $business->BusinessName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <button id="submit_data" type="button" class="btn btn-success">Submit</button>
            </div>

            <div class=" col-sm-6">
                <table class="table table-bordered table-hover  table-striped">
                    <tr>
                        <th class="bold" style="font-size: 16px;text-align: center">
                            Responsible Person : <span id="person"></span>
                        </th>
                        <th class="bold" style="font-size: 16px;text-align: center">
                            Operation Unit : <span id="businessName"></span>
                        </th>
                    </tr>
                </table>
            </div>

        </div>
    </form>

    <div class="col-md-12">
        <div class="row" style="padding-top: 15px" id="allChart">

        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        let business = $('#business').val();
        $.ajax({
            url: '{{ route('get.kpi.dashboard.data') }}',
            type: 'POST',
            data:{business: business},
            success: function (response) {
                console.log(response)
                loadChart(response);
            }
        });

        $("#submit_data").on('click', function () {
            let business = $('#business').val();
            $.ajax({
                url: '{{ route('get.kpi.dashboard.data') }}',
                type: 'POST',
                data:{business: business},
                success: function (response) {
                    $('#allChart').html('')
                    loadChart(response);
                }
            });
        });

        function loadChart(response) {
            $('#person').html(response.user.ResponsiblePerson)
            $('#businessName').html(response.user.BusinessName)
            let outPut = '';
            response.record_one.forEach ((item, key) => {
                if (key !== 0) {
                    outPut += `<div class="co1-md-6" style="background: white;padding: 10px;;margin-bottom: 20px;margin-left: 10px">
                                    <canvas style="height: 500px;width: 900px" id="mychart${key}"></canvas>
                                </div>`;
                } else {
                    var num = parseFloat(item[0].OverAllScore);
                    var result = num.toFixed(2);

                    outPut += `<div class="co1-md-6" style="background: white;padding: 10px;;margin-bottom: 20px;position: relative">
                                    <h3 style="position: absolute;top: 46%;left: 46%;" id="valset">`+ result +`%</h3>
                                   <canvas style="height: 500px;width: 900px" id="myChartZero${key}"></canvas>
                                </div>`;
                }
            })
            $("#allChart").append(outPut) ;
            setTimeout(()=>{
                getAllChart(response)
            },1000)
        }
        function getAllChart(response) {
            response.record_one.forEach ((item, key) => {
                if(key !== 0) {
                    let recordOne = item;

                    let chart_one_period = [];
                    let chart_one_cm = [];
                    let chart_one_cm_line = [];
                    let chart_one_lm = [];
                    let target_value = [];
                    let title = '';
                    let barMax = [];
                    let lineMax = [];

                    recordOne.forEach((getRecord, index) => {
                        title = getRecord.Title;
                        chart_one_period.push(getRecord.Period);
                        if (getRecord.ChartType === 'Bar'){
                            chart_one_cm.push(getRecord.CM);
                            chart_one_lm.push(null);
                            target_value.push(null);
                            chart_one_cm_line.push(null);
                            barMax.push(getRecord.TopScaleBar);
                            //lineMax.push(0);
                        }else{
                            chart_one_cm.push(0);
                            chart_one_lm.push(getRecord.LM);
                            target_value.push(getRecord.TargetValue);
                            chart_one_cm_line.push(getRecord.CM);
                            lineMax.push(getRecord.TopScaleLine);
                            //barMax.push(0);
                        }
                    })
                    console.log(chart_one_lm)

                    new Chart(document.getElementById(`mychart${key}`), {
                        type: 'bar',
                        data: {
                            labels: chart_one_period,
                            datasets: [
                                {
                                    label: '',
                                    type: 'bar',
                                    data: chart_one_cm,
                                    backgroundColor: '#00FFFF',
                                    borderColor: '#41e551',
                                    borderWidth: 1,
                                    yAxisID: 'y',
                                    order: 1
                                },
                                {
                                    label: '2022',
                                    type: 'line',
                                    data: chart_one_lm,
                                    backgroundColor: '#6e583b',
                                    borderColor: '#0dd1ea',
                                    borderWidth: 1,
                                    yAxisID: 'y1',
                                    order: 1
                                },
                                {
                                    label: '2023',
                                    type: 'line',
                                    data: chart_one_cm_line,
                                    backgroundColor: '#733f96',
                                    borderColor: '#7198b4',
                                    borderWidth: 1,
                                    yAxisID: 'y1',
                                    order: 1
                                },
                                {
                                    label: 'Target',
                                    type: 'line',
                                    data: target_value,
                                    backgroundColor: '#733f96',
                                    borderColor: '#7198b4',
                                    borderWidth: 1,
                                    yAxisID: 'y1',
                                    order: 1
                                }
                            ]
                        },
                        options: {
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            responsive: false,
                            stacked: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                    text: title,
                                    font: {
                                        size: 24,
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    max: barMax[0],
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                    //min: 0,
                                    max: lineMax[0],
                                },
                            }
                        },
                    });
                } else {
                    let record_zero = item[0];

                    let chart_zero_level = [];
                    let chart_zero_value = [];
                    chart_zero_level = Object.keys(record_zero)

                    var myChartZero = document.getElementById(`myChartZero${key}`);
                    var myChart = new Chart(myChartZero, {
                        type: 'doughnut',
                        data: {
                            labels: '',
                            datasets: [{
                                data: [parseFloat(record_zero[chart_zero_level[0]]), parseFloat(record_zero[chart_zero_level[1]])],
                                backgroundColor: [
                                    'rgb(70, 191, 189)',
                                    'rgb(252, 180, 92)'
                                ]
                            }]
                        },
                        options: {
                            responsive: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                },
                                title: {
                                    display: true,
                                    text: 'Overall Score',
                                    font: {
                                        size: 24,
                                    }
                                }
                            },
                        },
                    });
                }
            });
        }

        // function chartZero(response) {
        //     document.querySelector("#chartReportZero").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChartZero"></canvas>';
        //     //Start chart Three
        //     let record_zero = response.record_zero;
        //
        //     let chart_zero_level = [];
        //     let chart_zero_value = [];
        //     chart_zero_level = Object.keys(record_zero)
        //
        //     var myChartZero = document.getElementById('myChartZero');
        //     $('#valset').html(record_zero.OverAllScore + '%')
        //     var myChart = new Chart(myChartZero, {
        //         type: 'doughnut',
        //         data: {
        //             labels: '',
        //             datasets: [{
        //                 data: [parseFloat(record_zero[chart_zero_level[0]]), parseFloat(record_zero[chart_zero_level[1]])],
        //                 backgroundColor: [
        //                     'rgb(70, 191, 189)',
        //                     'rgb(252, 180, 92)'
        //                 ]
        //             }]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: 'Overall Score',
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //         },
        //     });
        // }
        // function chartTwo(response) {
        //     document.querySelector("#chartReport2").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart2"></canvas>';
        //     //Start chart Three
        //     let recordTwo = response.record_two;
        //
        //     let chart_two_period = [];
        //     let chart_two_cm = [];
        //     let chart_two_cm_line = [];
        //     let chart_two_lm = [];
        //     let target_value = [];
        //     let target_value_line = [];
        //
        //     recordTwo.forEach((getRecord, index) => {
        //         chart_two_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_two_cm.push(getRecord.CM);
        //             chart_two_lm.push(0);
        //             target_value.push(0);
        //             chart_two_cm_line.push(0);
        //         }else{
        //             chart_two_cm.push(0);
        //             chart_two_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_two_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart2 = document.getElementById('myChart2');
        //     var myChart = new Chart(myChart2, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_two_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_two_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_two_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_two_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: 'Delivery compliance ( % )',
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartThree(response) {
        //     document.querySelector("#chartReport3").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart3"></canvas>';
        //     //Start chart Three
        //     let recordThree = response.record_three;
        //
        //     let chart_three_period = [];
        //     let chart_three_cm = [];
        //     let chart_three_cm_line = [];
        //     let chart_three_lm = [];
        //     let target_value = [];
        //
        //     recordThree.forEach((getRecord, index) => {
        //         chart_three_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_three_cm.push(getRecord.CM);
        //             chart_three_lm.push(0);
        //             target_value.push(0);
        //             chart_three_cm_line.push(0);
        //         }else{
        //             chart_three_cm.push(0);
        //             chart_three_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_three_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart3 = document.getElementById('myChart3');
        //     var myChart = new Chart(myChart3, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_three_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_three_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_three_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_three_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: 'Yield ( % )',
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartFour(response) {
        //     document.querySelector("#chartReport4").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart4"></canvas>';
        //     //Start chart Three
        //     let recordFour = response.record_four;
        //
        //     let chart_four_period = [];
        //     let chart_four_cm = [];
        //     let chart_four_cm_line = [];
        //     let chart_four_lm = [];
        //     let target_value = [];
        //
        //     recordFour.forEach((getRecord, index) => {
        //         chart_four_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_four_cm.push(getRecord.CM);
        //             chart_four_lm.push(0);
        //             target_value.push(0);
        //             chart_four_cm_line.push(0);
        //         }else{
        //             chart_four_cm.push(0);
        //             chart_four_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_four_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart3 = document.getElementById('myChart4');
        //     var myChart = new Chart(myChart4, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_four_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_four_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_four_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_four_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "Downtime ( Hrs )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartFive(response) {
        //     document.querySelector("#chartReport5").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart5"></canvas>';
        //     //Start chart Three
        //     let recordFive = response.record_five;
        //
        //     let chart_five_period = [];
        //     let chart_five_cm = [];
        //     let chart_five_cm_line = [];
        //     let chart_five_lm = [];
        //     let target_value = [];
        //
        //     recordFive.forEach((getRecord, index) => {
        //         chart_five_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_five_cm.push(getRecord.CM);
        //             chart_five_lm.push(0);
        //             target_value.push(0);
        //             chart_five_cm_line.push(0);
        //         }else{
        //             chart_five_cm.push(0);
        //             chart_five_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_five_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart5 = document.getElementById('myChart5');
        //     var myChart = new Chart(myChart5, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_five_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_five_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_five_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_five_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "Cost- Direct Labor ( BDT / Kg )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartSix(response) {
        //     document.querySelector("#chartReport6").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart6"></canvas>';
        //     //Start chart Three
        //     let recordSix = response.record_six;
        //     console.log(recordSix)
        //
        //     let chart_six_period = [];
        //     let chart_six_cm = [];
        //     let chart_six_cm_line = [];
        //     let chart_six_lm = [];
        //     let target_value = [];
        //
        //     recordSix.forEach((getRecord, index) => {
        //         chart_six_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_six_cm.push(getRecord.CM);
        //             chart_six_lm.push(0);
        //             target_value.push(0);
        //             chart_six_cm_line.push(0);
        //         }else{
        //             chart_six_cm.push(0);
        //             chart_six_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_six_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart6 = document.getElementById('myChart6');
        //     var myChart = new Chart(myChart6, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_six_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_six_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_six_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_six_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "Environmental engagements (Internal, external activities done)( Nos )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartSeven(response) {
        //     document.querySelector("#chartReport7").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart7"></canvas>';
        //     //Start chart Three
        //     let recordSeven = response.record_seven;
        //
        //     let chart_seven_period = [];
        //     let chart_seven_cm = [];
        //     let chart_seven_cm_line = [];
        //     let chart_seven_lm = [];
        //     let target_value = [];
        //
        //     recordSeven.forEach((getRecord, index) => {
        //         chart_seven_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_seven_cm.push(getRecord.CM);
        //             chart_seven_lm.push(0);
        //             target_value.push(0);
        //             chart_seven_cm_line.push(0);
        //         }else{
        //             chart_seven_cm.push(0);
        //             chart_seven_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_seven_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart7 = document.getElementById('myChart7');
        //     var myChart = new Chart(myChart7, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_seven_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_seven_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_seven_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_seven_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "Local Mgmt (issues)( Nos )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                     min: 0,
        //                     max: 1.1,
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                     min: 0,
        //                     max: 1.1,
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartEight(response) {
        //     document.querySelector("#chartReport8").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart8"></canvas>';
        //     //Start chart Three
        //     let recordEight = response.record_eight;
        //
        //     let chart_eight_period = [];
        //     let chart_eight_cm = [];
        //     let chart_eight_cm_line = [];
        //     let chart_eight_lm = [];
        //     let target_value = [];
        //
        //     recordEight.forEach((getRecord, index) => {
        //         chart_eight_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_eight_cm.push(getRecord.CM);
        //             chart_eight_lm.push(0);
        //             target_value.push(0);
        //             chart_eight_cm_line.push(0);
        //         }else{
        //             chart_eight_cm.push(0);
        //             chart_eight_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_eight_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart8 = document.getElementById('myChart8');
        //     var myChart = new Chart(myChart8, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_eight_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_eight_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_eight_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_eight_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "Capacity utilization ( % )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartNine(response) {
        //     document.querySelector("#chartReport9").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart9"></canvas>';
        //     //Start chart Three
        //     let recordNine = response.record_nine;
        //
        //     let chart_nine_period = [];
        //     let chart_nine_cm = [];
        //     let chart_nine_cm_line = [];
        //     let chart_nine_lm = [];
        //     let target_value = [];
        //
        //     recordNine.forEach((getRecord, index) => {
        //         chart_nine_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_nine_cm.push(getRecord.CM);
        //             chart_nine_lm.push(0);
        //             target_value.push(0);
        //             chart_nine_cm_line.push(0);
        //         }else{
        //             chart_nine_cm.push(0);
        //             chart_nine_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_nine_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart9 = document.getElementById('myChart9');
        //     var myChart = new Chart(myChart9, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_nine_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_nine_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_nine_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_nine_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "OEE (Planned time, calendar time, actual time)( % )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                 },
        //             }
        //         },
        //     });
        // }
        // function chartTen(response) {
        //     document.querySelector("#chartReport10").innerHTML = '<canvas style="height: 500px;width: 900px" id="myChart10"></canvas>';
        //     //Start chart Three
        //     let recordTen = response.record_ten;
        //
        //     let chart_ten_period = [];
        //     let chart_ten_cm = [];
        //     let chart_ten_cm_line = [];
        //     let chart_ten_lm = [];
        //     let target_value = [];
        //
        //     recordTen.forEach((getRecord, index) => {
        //         chart_ten_period.push(getRecord.Period);
        //         if (getRecord.ChartType === 'Bar'){
        //             chart_ten_cm.push(getRecord.CM);
        //             chart_ten_lm.push(0);
        //             target_value.push(0);
        //             chart_ten_cm_line.push(0);
        //         }else{
        //             chart_ten_cm.push(0);
        //             chart_ten_lm.push(getRecord.LM);
        //             target_value.push(getRecord.TargetValue);
        //             chart_ten_cm_line.push(getRecord.CM);
        //         }
        //     })
        //
        //     var myChart10 = document.getElementById('myChart10');
        //     var myChart = new Chart(myChart10, {
        //         type: 'bar',
        //         data: {
        //             labels: chart_ten_period,
        //             datasets: [
        //                 {
        //                     label: '',
        //                     type: 'bar',
        //                     data: chart_ten_cm,
        //                     backgroundColor: '#00FFFF',
        //                     borderColor: '#41e551',
        //                     borderWidth: 1,
        //                     yAxisID: 'y',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2022',
        //                     type: 'line',
        //                     data: chart_ten_lm,
        //                     backgroundColor: '#6e583b',
        //                     borderColor: '#0dd1ea',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: '2023',
        //                     type: 'line',
        //                     data: target_value,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 },
        //                 {
        //                     label: 'Target',
        //                     type: 'line',
        //                     data: chart_ten_cm_line,
        //                     backgroundColor: '#733f96',
        //                     borderColor: '#7198b4',
        //                     borderWidth: 1,
        //                     yAxisID: 'y1',
        //                     order: 1
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: false,
        //             plugins: {
        //                 legend: {
        //                     position: 'bottom',
        //                 },
        //                 title: {
        //                     display: true,
        //                     text: "Incidents , accidents ( Nos )",
        //                     font: {
        //                         size: 24,
        //                     }
        //                 }
        //             },
        //             scales: {
        //                 y: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'left',
        //                     min: 0,
        //                     max: 6,
        //                 },
        //                 y1: {
        //                     type: 'linear',
        //                     display: true,
        //                     position: 'right',
        //                     grid: {
        //                         drawOnChartArea: false,
        //                     },
        //                     min: 0,
        //                     max: 2.2,
        //                 },
        //             }
        //         },
        //     });
        // }
    });

</script>
</body>
</html>
<style type="text/css">
    .table td, .table th {
        padding: 5px;
        font-size: 12px;
    }
</style>
