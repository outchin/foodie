@extends('layouts.admin.app')

@section('title', translate('messages.transaction_report'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/report.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ translate('messages.transection_report') }}
                    @if (isset($filter) && $filter != 'all_time')
                        <span class="mb-0 h6 badge badge-soft-success ml-2"
                              id="itemCount">( {{ session('from_date') }} - {{ session('to_date') }} )</span>
                    @endif
                </span>
            </h1>
        </div>

        @php
            $from = session('from_date') . ' 00:00:00';
            $to = session('to_date') . ' 23:59:59';
            $total = \App\Models\Order::when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($key), function ($query) use ($key) {
                    return $query->where(function ($q) use ($key) {
                            foreach ($key as $value) {
                                $q->orWhere('id', 'like', "%{$value}%");
                            }
                        });
                })
                ->when(request('module_id'), function ($query) {
                    return $query->module(request('module_id'));
                })
                ->when(isset($store), function ($query) use ($store) {
                    return $query->where('store_id', $store->id);
                })
                ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                    return $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                })
                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                    return $query->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                })
                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                    return $query->whereYear('created_at', date('Y') - 1);
                })
                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                    return $query->whereBetween('created_at', [
                        now()
                            ->startOfWeek()
                            ->format('Y-m-d H:i:s'),
                        now()
                            ->endOfWeek()
                            ->format('Y-m-d H:i:s'),
                    ]);
                })
                ->Notpos()
                ->count();
            if ($total == 0) {
                $total = 0.01;
            }
        @endphp
        <div class="mb-20">
            <div class="row g-3">
                <div class="col-lg-12 __card-vertical">

                    <div class="col-md-3 d--none">
                        @php
                            $delivered = \App\Models\Order::when(isset($zone), function ($query) use ($zone) {
                                return $query->where('zone_id', $zone->id);
                            })
                            ->when(isset($key), function ($query) use ($key) {
                                    return $query->where(function ($q) use ($key) {
                                            foreach ($key as $value) {
                                                $q->orWhere('id', 'like', "%{$value}%");
                                            }
                                        });
                                })
                                ->when(request('module_id'), function ($query) {
                                    return $query->module(request('module_id'));
                                })
                                ->whereIn('order_status', ['delivered','refund_requested','refund_request_canceled'])
                                ->when(isset($store), function ($query) use ($store) {
                                    return $query->where('store_id', $store->id);
                                })
                                ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                                    return $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                                })
                                ->when(isset($filter) && $filter == 'this_year', function ($query) {
                                    return $query->whereYear('created_at', now()->format('Y'));
                                })
                                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                                })
                                ->when(isset($filter) && $filter == 'this_month', function ($query) {
                                    return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                                })
                                ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                                    return $query->whereYear('created_at', date('Y') - 1);
                                })
                                ->when(isset($filter) && $filter == 'this_week', function ($query) {
                                    return $query->whereBetween('created_at', [
                                        now()
                                            ->startOfWeek()
                                            ->format('Y-m-d H:i:s'),
                                        now()
                                            ->endOfWeek()
                                            ->format('Y-m-d H:i:s'),
                                    ]);
                                })
                                ->Notpos()
                                ->sum('order_amount');
                        @endphp
                    </div>
                    <div class="col-md-4">
                        <div class="__card-vertical">
                            <div class="__card-vertical-img">

                                <h4 class="name">Completed Transaction</h4>

                            </div>
                            <h4 class="earning text-0661CB">
                                {{ \App\CentralLogics\Helpers::number_format_short($delivered) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="__card-vertical">
                            <div class="__card-vertical-img">

                                <h4 class="name">{{ translate('Admin Earning') }}</h4>

                            </div>
                            <h4 class="earning" style=" color: green">
                                {{ \App\CentralLogics\Helpers::number_format_short($admin_earned) }}</h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="__card-vertical">
                            <div class="__card-vertical-img">

                                <h4 class="name">{{ translate('Deliveryman Earning') }}</h4>
                                <div class="info-icon" data-toggle="tooltip" data-placement="right"
                                     data-original-title="{{ translate('Deducting the admin commission on the delivery fee, the delivery fee & tips amount goes to earning section.') }}">

                                </div>
                            </div>
                            <h4 class="earning text-FF7500">
                                {{ \App\CentralLogics\Helpers::number_format_short($deliveryman_earned) }}</h4>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- End Stats -->
        <!-- Card -->
        <div class="card mt-3">
            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable" class="table table-thead-bordered table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th class="border-0">{{ translate('sl') }}</th>
                            <th class="border-0">{{ translate('messages.order_id') }}</th>

                            <th class="border-0">{{ translate('messages.delivery_charge') }}</th>
                            <th class="border-0">{{ translate('messages.order_amount') }}</th>
                            <th class="border-0">{{ translate('messages.admin_commission') }}</th>
                            <th class="border-0">{{ \App\CentralLogics\Helpers::get_business_data('additional_charge_name')??translate('messages.additional_charge') }}</th>
                            <th class="min-w-140 text-capitalize">{{ translate('admin_net_income') }}</th>
                            <th class="border-0 min-w-120">{{ translate('messages.amount_received_by') }}</th>
                            <th class="border-0">{{ translate('messages.payment_status') }}</th>
                        </tr>
                        </thead>
                        <tbody id="set-rows">
                        @foreach ($order_transactions as $k => $ot)
                            <tr scope="row">
                                <td>{{ $k + $order_transactions->firstItem() }}</td>
                                @if ($ot->order->order_type == 'parcel')
                                    <td><a
                                                href="{{ route('admin.transactions.parcel.order.details', $ot->order_id) }}">{{ $ot->order_id }}</a>
                                    </td>
                                @else
                                    <td><a
                                                href="{{ route('admin.transactions.order.details', $ot->order_id) }}">{{ $ot->order_id }}</a>
                                    </td>
                                @endif
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->delivery_charge) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency($ot->order_amount) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->admin_commission + $ot->admin_expense) - $ot->delivery_fee_comission -$ot->additional_charge - $ot->order['flash_admin_discount_amount']) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->additional_charge)) }}</td>
                                <td class="white-space-nowrap">{{ \App\CentralLogics\Helpers::format_currency(($ot->admin_commission - $ot->order['flash_admin_discount_amount'])) }}</td>
                                @if ($ot->received_by == 'admin')
                                    <td class="text-capitalize white-space-nowrap">{{ translate('messages.admin') }}</td>
                                @elseif ($ot->received_by == 'deliveryman')
                                    <td class="text-capitalize white-space-nowrap">
                                        <div>{{ translate('messages.delivery_man') }}</div>
                                        <div class="text-right mw--85px">
                                            @if (isset($ot->delivery_man) && $ot->delivery_man->earning == 1)
                                                <span class="badge badge-soft-primary">
                                                    {{translate('messages.freelance')}}
                                                </span>
                                            @elseif (isset($ot->delivery_man) && $ot->delivery_man->earning == 0 && $ot->delivery_man->type == 'restaurant_wise')
                                                <span class="badge badge-soft-warning">
                                                    {{translate('messages.restaurant')}}
                                                </span>
                                            @elseif (isset($ot->delivery_man) && $ot->delivery_man->earning == 0 && $ot->delivery_man->type == 'zone_wise')
                                                <span class="badge badge-soft-success">
                                                    {{translate('messages.admin')}}
                                                    </span>
                                            @endif
                                        </div>
                                    </td>
                                @elseif ($ot->received_by == 'store')
                                    <td class="text-capitalize white-space-nowrap">{{ translate('messages.store') }}</td>
                                @endif
                                {{--                                <td class="mw--85px text-capitalize min-w-120 ">--}}
                                {{--                                    {{ translate(str_replace('_', ' ', $ot->order['payment_method'])) }}--}}
                                {{--                                </td>--}}
                                <td class="text-capitalize white-space-nowrap">
                                    @if ($ot->status)
                                        <span class="badge badge-soft-danger">
                                            {{translate('messages.refunded')}}
                                          </span>
                                    @else
                                        <span class="badge badge-soft-success">
                                            {{translate('messages.completed')}}
                                          </span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{--            remove table columsn and filters removed by zg--}}
            <!-- End Body -->
            @if (count($order_transactions) !== 0)
                <hr>
            @endif
            <div class="page-area">
                {!! $order_transactions->links() !!}
            </div>
            @if (count($order_transactions) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js">
    </script>
    <script src="{{ asset('public/assets/admin') }}/js/hs.chartjs-matrix.js"></script>

    <script>
        $(document).on('ready', function () {

            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });


            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format(
                    'MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CHARTJS
            // =======================================================
            $('.js-chart').each(function () {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            // Call when tab is clicked
            $('[data-toggle="chart"]').click(function (e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function (dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })


            // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
            // =======================================================
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function () {
                                return '';
                            },
                            label: function (item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() +
                                        ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function () {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });


            // INITIALIZATION OF CIRCLES
            // =======================================================
            $('.js-circle').each(function () {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/store/get-stores',
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            // all:true,
                            @if (isset($zone))
                            zone_ids: [{{ $zone->id }}],
                            @endif
                                    @if (request('module_id'))
                            module_id: {{ request('module_id') }},
                            @endif
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    __port: function (params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.transactions.report.day-wise-report-search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('#countItems').html(data.count);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
