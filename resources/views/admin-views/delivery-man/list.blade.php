@extends('layouts.admin.app')

@section('title',translate('messages.deliverymen'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/delivery-man.png')}}" class="w--26" alt="">
                </span>
                <span>{{translate('messages.deliveryman')}}</span>
            </h1>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">

                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false
                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0 text-capitalize">#</th>
                        <th class="border-0 text-capitalize">{{translate('messages.name')}}</th>
                        <th class="border-0 text-capitalize">{{translate('messages.contact_info')}}</th>

                        <th class="border-0 text-capitalize">{{translate('messages.total_orders')}}</th>
                        <th class="border-0 text-capitalize">{{translate('messages.availability_status')}}</th>
                        <th class="border-0 text-center text-capitalize">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($delivery_men as $key=>$dm)
                        <tr>
                            <td>{{$key+$delivery_men->firstItem()}}</td>
                            <td>
                                <a class="table-rest-info" href="{{route('admin.users.delivery-man.preview',[$dm['id']])}}">

                                    <div class="info">
                                        <h5 class="text-hover-primary mb-0">{{$dm['f_name'].' '.$dm['l_name']}}</h5>
                                        <span class="d-block text-body">

                                        </span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a class="deco-none" href="tel:{{$dm['phone']}}">{{$dm['phone']}}</a>
                            </td>

                            <td>
                                <a class="deco-none" href="tel:{{$dm['phone']}}">{{count($dm['orders'])}}</a>
                            </td>
                            <td>

                                <div>

                                    @if($dm->application_status == 'approved')
                                        @if($dm->active)
                                        <strong class="text-capitalize text-primary">{{translate('messages.online')}}</strong>
                                        @else
                                        <strong class="text-capitalize text-secondary">{{translate('messages.offline')}}</strong>
                                        @endif
                                    @elseif ($dm->application_status == 'denied')
                                        <strong class="text-capitalize text-danger">{{translate('messages.denied')}}</strong>
                                    @else
                                        <strong class="text-capitalize text-info">{{translate('messages.pending')}}</strong>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--warning btn-outline-warning"
                                            href="{{route('admin.users.delivery-man.preview',[$dm['id']])}}"
                                            title="{{ translate('messages.view') }}">View
                                        </a>
                                    <a class="btn action-btn btn--primary btn-outline-primary" href="{{route('admin.users.delivery-man.edit',[$dm['id']])}}" title="{{translate('messages.edit')}}">Edit</i>
                                        </a>
                                        <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:" onclick="form_alert('delivery-man-{{$dm['id']}}','{{ translate('Want to remove this deliveryman ?') }}')" title="{{translate('messages.delete')}}">Delete</i>
                                    </a>
                                    <form action="{{route('admin.users.delivery-man.delete',[$dm['id']])}}" method="post" id="delivery-man-{{$dm['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($delivery_men) !== 0)
                <hr>
                @endif
                <div class="page-area">
                    {!! $delivery_men->links() !!}
                </div>
                @if(count($delivery_men) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.users.delivery-man.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
