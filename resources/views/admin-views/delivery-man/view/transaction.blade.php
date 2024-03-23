@extends('layouts.admin.app')

@section('title',translate('messages.Delivery Man Preview'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-break">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/delivery-man.png')}}" class="w--26" alt="">
                </span>
                <span>{{$dm['f_name'].' '.$dm['l_name']}}</span>
            </h1>
            <div class="row">
                <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
                    <!-- Nav -->
                    <ul class="nav nav-tabs nav--tabs border-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('admin.users.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'info'])}}"  aria-disabled="true">{{translate('messages.info')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('admin.users.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transaction')}}</a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{route('admin.users.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'conversation'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{route('admin.users.delivery-man.preview', ['id'=>$dm->id, 'tab'=> 'disbursement'])}}"  aria-disabled="true">{{translate('messages.disbursements')}}</a>--}}
{{--                        </li>--}}
                    </ul>
                    <!-- End Nav -->
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5 mt-2">
            <div class="card-header py-2 border-0 gap-2">
                <div class="search--button-wrapper">
                    <h4 class="card-title">{{ translate('messages.order_transactions')}}</h4>
                    <a class="btn action-btn btn--primary btn-outline-primary" href="javascript:void(0);" onclick="searchWithDates({{$dm->id}})" title="Edit"><i class="tio-search"></i></a>



                    </a>
                    <div class="min--260">
                        <input  id="from_date" type="date" class="form-control" placeholder="{{ translate('mm/dd/yyyy') }}"  value="{{ empty($from_date) ? date('Y-m-d', strtotime('-7 days')) : $from_date }}">
                    </div>
                    <div class="min--260">
                        <input  id="to_date" type="date" class="form-control" placeholder="{{ translate('mm/dd/yyyy') }}"  value="{{ empty($to_date) ? date('Y-m-d') : $to_date }}">
                    </div>
                    <script>
                        function searchWithDates(date) {
                            var fromDate = document.getElementById('from_date').value;
                            var toDate = document.getElementById('to_date').value;

                            // Construct the URL with the date values
                            var url = "?from_date=" + fromDate + "&to_date=" + toDate;

                            // Redirect to the constructed URL
                            window.location.href = url;
                        }
                        // Function to extract URL parameters
                        function getParameterByName(name, url) {
                            if (!url) url = window.location.href;
                            name = name.replace(/[\[\]]/g, "\\$&");
                            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                                results = regex.exec(url);
                            if (!results) return null;
                            if (!results[2]) return '';
                            return decodeURIComponent(results[2].replace(/\+/g, " "));
                        }

                        // Get the values of from_date and to_date parameters from the URL
                        var fromDateParam = getParameterByName('from_date');
                        var toDateParam = getParameterByName('to_date');

                        // Set the default values of the input date fields
                        document.getElementById('from_date').value = fromDateParam;
                        document.getElementById('to_date').value = toDateParam;
                    </script>
                </div>

{{--                <!-- Unfold -->--}}
{{--                <div class="hs-unfold mr-2">--}}
{{--                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"--}}
{{--                        data-hs-unfold-options='{--}}
{{--                                "target": "#usersExportDropdown",--}}
{{--                                "type": "css-animation"--}}
{{--                            }'>--}}
{{--                        <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}--}}
{{--                    </a>--}}

{{--                    <div id="usersExportDropdown"--}}
{{--                        class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">--}}
{{--                        <span class="dropdown-header">{{ translate('messages.download_options') }}</span>--}}
{{--                        <a id="export-excel" class="dropdown-item" href="{{route('admin.delivery-man.earning-export', ['type'=>'excel','id'=>$dm->id,request()->getQueryString()])}}">--}}
{{--                            <img class="avatar avatar-xss avatar-4by3 mr-2"--}}
{{--                                src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"--}}
{{--                                alt="Image Description">--}}
{{--                            {{ translate('messages.excel') }}--}}
{{--                        </a>--}}
{{--                        <a id="export-csv" class="dropdown-item" href="{{route('admin.delivery-man.earning-export', ['type'=>'csv','id'=>$dm->id,request()->getQueryString()])}}">--}}
{{--                            <img class="avatar avatar-xss avatar-4by3 mr-2"--}}
{{--                                src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"--}}
{{--                                alt="Image Description">--}}
{{--                            .{{ translate('messages.csv') }}--}}
{{--                        </a>--}}
{{--                        --}}{{-- <a id="export-pdf" class="dropdown-item" href="javascript:;">--}}
{{--                            <img class="avatar avatar-xss avatar-4by3 mr-2"--}}
{{--                                src="{{ asset('public/assets/admin') }}/svg/components/pdf.svg"--}}
{{--                                alt="Image Description">--}}
{{--                            {{ translate('messages.pdf') }}--}}
{{--                        </a> --}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- End Unfold -->--}}
            </div>
            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-borderless table-thead-bordered table-nowrap justify-content-between table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">{{translate('sl')}}</th>
                                <th class="border-0">{{translate('messages.order_id')}}</th>
                                <th class="border-0">{{translate('messages.amount')}}</th>
                                <th class="border-0">{{translate('messages.delivery_fee_earned')}}</th>
                                <th class="border-0">{{translate('messages.distance')}}</th>
                                <th class="border-0">{{translate('messages.customer_information')}}</th>
                                <th class="border-0">Receiver Information</th>
{{--                                <th class="border-0">{{translate('messages.parcel_category')}}</th>--}}

{{--                                <th class="border-0">{{translate('messages.delivery_tips')}}</th>--}}
                                <th class="border-0">{{translate('messages.date')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
                        $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;
                        ?>
                        @php($digital_transaction = \App\Models\OrderTransaction::where('delivery_man_id', $dm->id)
                        ->when($from_date && $to_date, function($query) use ($from_date, $to_date) {
                            return $query->whereDate('created_at', '>=', $from_date)
                                         ->whereDate('created_at', '<=', $to_date);
                        })->paginate(5000))
                        @foreach($digital_transaction as $k=>$dt)
                            <tr>
                                <td scope="row">{{$k+$digital_transaction->firstItem()}}</td>
                                <td><a href="{{route((isset($dt->order) && $dt->order->order_type=='parcel')?'admin.parcel.order.details':'admin.order.details',[$dt->order_id,'module_id'=>$dt->order->module_id])}}">{{$dt->order_id}}</a></td>
                                <td>{{$dt->order_amount}}</td>
                                <td>{{$dt->original_delivery_charge}}</td>
                                <td>{{ $dt->order->distance}}</td>
                                <td>
                                    @if($dt->order->is_guest)
                                        @php($delivery_details = $dt->order->delivery_address)
                                        @php($delivery_details_data = json_decode($delivery_details, true))
                                        <strong>{{$delivery_details_data['contact_person_name']}}</strong>
                                        <div>{{$delivery_details_data['contact_person_number']}}</div>
                                    @else
                                        <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                    @endif
                                </td>
                                <td>
                                    @if($dt->order->is_guest)

                                        @php($receiver = $dt->order)
                                        <?php $data = json_decode($receiver, true);
                                              $receiverDetails = $data['receiver_details']; ?>
                                        <strong>{{$receiverDetails['contact_person_name']}}</strong>
                                        <div>{{$receiverDetails['contact_person_number']}}</div>
                                    @else
                                        <label class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                    @endif
                                </td>
{{--                                <td>Category</td>--}}
{{--                                <td>{{$dt->dm_tips}}</td>--}}
                                <td>{{$dt->created_at->format('Y-m-d')}}</td>
                            </tr>
                        @endforeach
                        <?php
                        $totalAmount = 0;
                        $totalDeliveryFeeEarned = 0;
                        $totalDistance = 0;
                        $totalTips = 0;

                        foreach ($digital_transaction as $dt) {
                            $totalAmount += $dt->order_amount;
                            $totalDeliveryFeeEarned += $dt->original_delivery_charge;
                            $totalDistance += $dt->order->distance;
                            $totalTips += $dt->dm_tips;
                        }
                        ?>
                        <thead class="thead-light">
                        <tr>
                            <th class="border-0">Total</th>
                            <th class="border-0"></th>
                            <th class="border-0">{{$totalAmount}} Ks</th>
                            <th class="border-0">{{$totalDeliveryFeeEarned}} Ks</th>
                            <th class="border-0">{{$totalDistance}} Km</th>
{{--                            <th class="border-0"></th>--}}
                            <th class="border-0"></th>
                            <th class="border-0"></th>
                            <th class="border-0"></th>
                            <th class="border-0"></th>
                        </tr>
                        </thead>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Body -->
            <div class="card-footer">
                {!!$digital_transaction->links()!!}
            </div>
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
<script>
    function searchWithDates(date) {
        var fromDate = document.getElementById('from_date').value;
        var toDate = document.getElementById('to_date').value;
// Check if fromDate or toDate is null
        if (!fromDate || !toDate) {
            alert("Please select both a 'from' date and a 'to' date.");
            return;
        }
        // Construct the URL with the date values
        var url = "date" + from_date=" + fromDate + "&to_date=" + toDate;
        // Redirect to the constructed URL
        window.location.href = url;
    }
</script>
<script>
    function request_alert(url, message) {
        Swal.fire({
            title: '{{ translate('Are you sure?') }}' ,
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{translate('messages.no')}}',
            confirmButtonText: '{{translate('messages.yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = url;
            }
        })
    }
</script>
@endpush
