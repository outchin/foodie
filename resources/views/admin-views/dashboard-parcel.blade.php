@extends('layouts.admin.app')

@section('title',\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value??translate('messages.dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        @if(auth('admin')->user()->role_id == 1)
        @php($mod = \App\Models\Module::find(Config::get('module.current_module_id')))
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center py-2">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="d-flex align-items-center">
                        <img onerror="this.src='{{asset('/public/assets/admin/img/parcel.svg')}}'" src="{{asset('storage/app/public/module')}}/{{$mod->icon}}" width="38" alt="img">
                        <div class="w-0 flex-grow pl-2">
                            <h1 class="page-header-title mb-0">{{translate($mod->module_name)}} {{translate('messages.Dashboard')}}.</h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Page Header -->
{{-- {{ dd($data) }} --}}
        <!-- Stats -->
        <div class="card mb-3">
            <div class="card-body pt-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between statistics--title-area">
                    <div class="statistics--title pr-sm-3" id="stat_zone">
                        @include('admin-views.partials._zone-change',['data'=>$data])
                    </div>

                </div>
                <div class="row g-2" id="order_stats">
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-1" href="{{route('admin.parcel.list',['searching_for_deliverymen'])}}">
                        <h4 class="title">{{$data['searching_for_dm']}}</h4>
                            <br>

                        <span class="subtitle">{{translate('unassigned_orders')}}</span>
                        </a>
                        <!-- End Card -->
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-2" href="{{route('admin.parcel.orders',['item_on_the_way'])}}">

                        <h4 class="title">{{$data['picked_up']}}</h4> <br>
                        <span class="subtitle">{{translate('out_for_delivery')}}</span>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-3" href="{{route('admin.parcel.orders',['delivered'])}}">

                        <h4 class="title">{{$data['delivered']}}</h4><br>
                        <span class="subtitle">{{translate('delivered')}}</span>
                        </a>
                        <!-- End Card -->
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="resturant-card dashboard--card __dashboard-card card--bg-4" href="{{route('admin.parcel.orders',['failed'])}}">
                        <h4 class="title">{{$data['refund_requested']}}</h4><br>
                        <span class="subtitle">{{translate('Failed Orders')}}</span>
                        </a>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats -->


        @else
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('messages.welcome')}}, {{auth('admin')->user()->f_name}}.</h1>
                    <p class="page-header-text">{{translate('messages.employee_welcome_message')}}</p>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        @endif
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>

    <!-- Apex Charts -->
    <script src="{{asset('/public/assets/admin/js/apex-charts/apexcharts.js')}}"></script>
    <!-- Apex Charts -->

@endpush


@push('script_2')

    <!-- Dognut Pie Chart -->
    <script>
        var options = {
            series: [{{ $data['customer']}}, {{$data['delivery_man']}}],
            chart: {
                width: 320,
                type: 'donut',
            },
            labels: ['{{ translate('Customer') }}', '{{ translate('Delivery man') }}'],
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#005555', '#b9e0e0',]
                }
            },
            responsive: [{
                breakpoint: 1650,
                options: {
                    chart: {
                        width: 250
                    },
                }
            }],
            colors: ['#005555','#111'],
            fill: {
                colors: ['#005555','#b9e0e0']
            },
            legend: {
                show: false
            },
        };

        var chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
        chart.render();

    </script>

    <script>
    var options = {
          series: [{
          name: '{{ translate('Gross Sale') }}',
          data: [{{$total_sell[1]}},{{$total_sell[2]}},{{$total_sell[3]}},{{$total_sell[4]}},{{$total_sell[5]}},{{$total_sell[6]}},{{$total_sell[7]}},{{$total_sell[8]}},{{$total_sell[9]}},{{$total_sell[10]}},{{$total_sell[11]}},{{$total_sell[12]}}]
        },{
          name: '{{ translate('Admin Comission') }}',
          data: [{{$commission[1]}},{{$commission[2]}},{{$commission[3]}},{{$commission[4]}},{{$commission[5]}},{{$commission[6]}},{{$commission[7]}},{{$commission[8]}},{{$commission[9]}},{{$commission[10]}},{{$commission[11]}},{{$commission[12]}}]
        },{
          name: '{{ translate('Delivery Comission') }}',
          data: [{{$delivery_commission[1]}},{{$delivery_commission[2]}},{{$delivery_commission[3]}},{{$delivery_commission[4]}},{{$delivery_commission[5]}},{{$delivery_commission[6]}},{{$delivery_commission[7]}},{{$delivery_commission[8]}},{{$delivery_commission[9]}},{{$delivery_commission[10]}},{{$delivery_commission[11]}},{{$delivery_commission[12]}}]
        }],
          chart: {
          height: 350,
          type: 'area',
          toolbar: {
            show:false
        },
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        dataLabels: {
          enabled: false,
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        stroke: {
          curve: 'smooth',
          width: 2,
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        fill: {
            type: 'gradient',
            colors: ['#76ffcd','#ff6d6d', '#005555'],
        },
        xaxis: {
        //   type: 'datetime',
          categories: ["{{ translate('Jan') }}", "{{ translate('Feb') }}", "{{ translate('Mar') }}", "{{ translate('Apr') }}", "{{ translate('May') }}", "{{ translate('Jun') }}", "{{ translate('Jul') }}", "{{ translate('Aug') }}", "{{ translate('Sep') }}", "{{ translate('Oct') }}", "{{ translate('Nov') }}", "{{ translate('Dec') }}" ]
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#grow-sale-chart"), options);
        chart.render();
    </script>

    <!-- Dognut Pie Chart -->
    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function () {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));
    </script>

    <script>
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.dashboard-stats.order')}}',
                data: {
                    statistics_type: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    insert_param('statistics_type',type);
                    $('#order_stats').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }

        function fetch_data_zone_wise(zone_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.dashboard-stats.zone')}}',
                data: {
                    zone_id: zone_id
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    insert_param('zone_id', zone_id);
                    $('#order_stats').html(data.order_stats);
                    $('#user-overview-board').html(data.user_overview);
                    $('#monthly-earning-graph').html(data.monthly_graph);
                    $('#popular-restaurants-view').html(data.popular_restaurants);
                    $('#top-deliveryman-view').html(data.top_deliveryman);
                    $('#top-rated-foods-view').html(data.top_rated_foods);
                    $('#top-restaurants-view').html(data.top_restaurants);
                    $('#top-selling-foods-view').html(data.top_selling_foods);
                    $('#stat_zone').html(data.stat_zone);
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }

        function user_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.dashboard-stats.user-overview')}}',
                data: {
                    user_overview: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    insert_param('user_overview',type);
                    $('#user-overview-board').html(data.view)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
        function commission_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.dashboard-stats.commission-overview')}}',
                data: {
                    commission_overview: type
                },
                beforeSend: function () {
                    $('#loading').show()
                },
                success: function (data) {
                    insert_param('commission_overview',type);
                    $('#commission-overview-board').html(data.view)
                    $('#gross_sale').html(data.gross_sale)
                },
                complete: function () {
                    $('#loading').hide()
                }
            });
        }
    </script>

    <script>
        function insert_param(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);
            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i = 0;

            for (; i < kvp.length; i++) {
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }
            if (i >= kvp.length) {
                kvp[kvp.length] = [key, value].join('=');
            }
            // can return this or...
            let params = kvp.join('&');
            // change url page with new params
            window.history.pushState('page2', 'Title', '{{url()->current()}}?' + params);
        }
    </script>
@endpush
