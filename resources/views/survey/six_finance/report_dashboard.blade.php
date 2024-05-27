@php
    $page_title="six_form";
@endphp

@extends('layouts.app_user')

@section('custom_css')
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('dashboard')}}">Sixth Assam State Finance</a></li>
            <li class="active">Report</li>
        </ol>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row text-center mt20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <a href="{{route('survey.six_finance.report.view_submitted_list')}}" class="thumbnail text-uppercase">
                        <p>
                            <i class="fa fa-hand-o-right fa-2x"></i>
                        </p>
                        <p>View List</p>
                        @if($cardCount['ZP'])
                            <p>Total ZP: {{$cardCount['ZP']['T']}}, Submitted ZP: {{$cardCount['ZP']['S']}}</p>
                        @endif
                        @if($cardCount['AP'])
                            <p>Total AP: {{$cardCount['AP']['T']}}, Submitted AP: {{$cardCount['AP']['S']}}</p>
                        @endif
                        @if($cardCount['GP'])
                            <p>Total GP: {{$cardCount['GP']['T']}}, Submitted GP: {{$cardCount['GP']['S']}}</p>
                        @endif
                    </a>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row text-center mt20" style="margin-bottom: 50px">
            @if($zpGraph)
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <h4 style="margin-bottom: 40px">[ ZP, AP Submitted Progress Chart ]</h4>
                    <canvas id="myZPAP"></canvas>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12" id="graphGP">
                    <h4>[ GP Submitted Progress Chart AP wise ]</h4>
                    <p class="text-right">
                        Select AP to view chart
                        <select name="ap_id" id="ap_id">
                            <option value="">--Select AP--</option>
                            @if($anchalikList)
                                @foreach ($anchalikList AS $anchalik)
                                    <option value="{{$anchalik->id}}">{{$anchalik->anchalik_parishad_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('custom_js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <script type="application/javascript">

        @if($zpGraph)
        var ctx = document.getElementById('myZPAP').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @php echo json_encode($zpGraph['labels']) @endphp,
                datasets: [
                    {
                        label: 'Basic Info',
                        data: @php echo json_encode($zpGraph['basics']) @endphp,
                        backgroundColor: '#98b2e9',
                    },
                    {
                        label: 'Staff Info',
                        data: @php echo json_encode($zpGraph['staffs']) @endphp,
                        backgroundColor: '#13ebcc',
                    },
                    {
                        label: 'Revenue Info',
                        data: @php echo json_encode($zpGraph['revenues']) @endphp,
                        backgroundColor: '#a06aeb',
                    },
                    {
                        label: 'Expenditure Info',
                        data: @php echo json_encode($zpGraph['expenditures']) @endphp,
                        backgroundColor: '#eaebac',
                    },
                    {
                        label: 'Balance Info',
                        data: @php echo json_encode($zpGraph['balances']) @endphp,
                        backgroundColor: '#eb9bbc',
                    },
                    {
                        label: 'Other Info',
                        data: @php echo json_encode($zpGraph['others']) @endphp,
                        backgroundColor: '#7dd2eb',
                    },
                    {
                        label: 'Next 5 Year Info',
                        data: @php echo json_encode($zpGraph['nexts']) @endphp,
                        backgroundColor: '#2b3c40',
                    },
                    {
                        label: 'Final Submit',
                        data: @php echo json_encode($zpGraph['finals']) @endphp,
                        backgroundColor: '#109618',
                    }
                ]
            },
            options: {
				responsive: true,
				maintainAspectRatio:true,
                scales: {
                    xAxes: [{ stacked: true }],
                    yAxes: [{ stacked: true }]
                }
            }
        });

        @endif

        $('#ap_id').on('change', function(e){
            e.preventDefault();

            var ap_id = $('#ap_id').val();
            $('#myGP').remove();
            if(ap_id){
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: '{{route('survey.six_finance.report.getAP')}}',
                    dataType: "json",
                    data: {"ap_id": ap_id},
                    cache: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            $('#graphGP').append('<canvas id="myGP"></canvas>');
                            var ctx = document.getElementById('myGP').getContext('2d');
                            var chart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: data.data.labels,
                                    datasets: [
                                        {
                                            label: 'Basic Info',
                                            data: data.data.basics,
                                            backgroundColor: '#98b2e9',
                                        },
                                        {
                                            label: 'Staff Info',
                                            data: data.data.staffs,
                                            backgroundColor: '#13ebcc',
                                        },
                                        {
                                            label: 'Revenue Info',
                                            data: data.data.revenues,
                                            backgroundColor: '#a06aeb',
                                        },
                                        {
                                            label: 'Expenditure Info',
                                            data: data.data.expenditures,
                                            backgroundColor: '#eaebac',
                                        },
                                        {
                                            label: 'Balance Info',
                                            data: data.data.balances,
                                            backgroundColor: '#eb9bbc',
                                        },
                                        {
                                            label: 'Other Info',
                                            data: data.data.others,
                                            backgroundColor: '#7dd2eb',
                                        },
                                        {
                                            label: 'Next 5 Year Info',
                                            data: data.data.nexts,
                                            backgroundColor: '#2b3c40',
                                        },
                                        {
                                            label: 'Final Submit',
                                            data: data.data.finals,
                                            backgroundColor: '#109618',
                                        },
                                        {
                                            label: 'Verify',
                                            data: data.data.verify,
                                            backgroundColor: '#960406',
                                        }
                                    ]
                                },
                                options: {
                                    scales: {
                                        xAxes: [{ stacked: true }],
                                        yAxes: [{ stacked: true }]
                                    }
                                }
                            });

                        }else{


                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }else{

            }
        });

    </script>
@endsection
