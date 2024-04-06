@extends('layouts.admin.app')

@section('title', translate('business_setup'))


@section('content')
    <div class="content container-fluid">
        <!-- End Page Header -->
        <div class="row g-3">
            <div class="col-lg-12">
                <h4 class="card-title mb-3 mt-1">
                    <span class="card-header-icon mr-2"><i class="tio-user"></i></span>
                    <span>{{ translate('sender_information') }}</span>
                </h4>

                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="form-group mb-0">
                                    <label class="form-label"
                                           for="exampleFormControlInput1">{{ translate('messages.sender_name') }}</label>
                                    <input type="text" name="sender_name" id="sender_name"
                                           class="form-control"
                                           placeholder="{{ translate('messages.sender_name') }}"
                                           required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="form-group mb-0">
                                    <label class="form-label"
                                           for="exampleFormControlInput1">{{ translate('messages.phone') }}</label>
                                    <input type="tel" value="" name="sender_phone" id="sender_phone"
                                           class="form-control"
                                           placeholder="{{ translate('messages.Ex: +959 xxxx') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="form-group mb-0">
                                    <label class="form-label"
                                           for="exampleFormControlInput1">{{ translate('messages.street') }}</label>
                                    <input type="text" value="  " name="sender_street" id="sender_street"
                                           class="form-control"
                                           placeholder="{{ translate('messages.Ex_: 41x42 Mandalay') }}" required>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="form-group mb-0">
                                    <label class="form-label text-capitalize"
                                           for="category">{{ translate('messages.category') }}</label>
                                    <select id="category" name="category" class="form-control  js-select2-custom">
                                        @foreach($parcel_categories as $key=>$category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                   for="exampleFormControlInput1">{{ translate('messages.house') }}</label>
                                            <input type="text" value="  " name="sender_house" id="sender_house"
                                                   
                                                   class="form-control"
                                                   placeholder="{{ translate('messages.Ex_: 1') }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label"
                                                   for="exampleFormControlInput1">{{ translate('messages.floor') }}</label>
                                            <input type="text" value="  " name="sender_floor" id="sender_floor"
                                                   
                                                   class="form-control"
                                                   placeholder="{{ translate('messages.Ex_: 2nd floor') }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                                        @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                                        <div class="form-group mb-0">
                                            <label class="form-label text-capitalize"
                                                   for="sender_latitude">{{ translate('messages.latitude') }}<span
                                                        class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"><img
                                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"></span></label>
                                            <input type="text" id="sender_latitude" name="sender_latitude"
                                                   class="form-control"
                                                   placeholder="{{ translate('messages.Ex:') }} -94.22213"
                                                   value="{{ $default_location ? $default_location['lat'] : 0 }}"
                                                   required
                                                   readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label text-capitalize"
                                                   for="sender_longitude">{{ translate('messages.longitude') }}<span
                                                        class="form-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"><img
                                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"></span></label>
                                            <input type="text" name="sender_longitude" class="form-control"
                                                   placeholder="{{ translate('messages.Ex:') }} 103.344322"
                                                   id="sender_longitude"
                                                   value="{{ $default_location ? $default_location['lng'] : 0 }}"
                                                   required
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mt-md-4">
                                    <input id="pac-input" class="controls rounded" data-toggle="tooltip"
                                           data-placement="right"
                                           data-original-title="{{ translate('messages.search_your_location_here') }}"
                                           type="text" placeholder="{{ translate('messages.search_here') }}"/>
                                    <div id="location_map_canvas"
                                         class="overflow-hidden rounded height-285px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="content container-fluid">
        <!-- End Page Header -->

        <div class="row g-3">


                <div class="col-lg-12">
                    <h4 class="card-title mb-3 mt-1">
                        <span class="card-header-icon mr-2"><i class="tio-user"></i></span>
                        <span>{{ translate('receiver_information') }}</span>
                    </h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                               for="exampleFormControlInput1">{{ translate('messages.receiver_name') }}</label>
                                        <input type="text" name="receiver_name" id="receiver_name"
                                               class="form-control"
                                               placeholder="{{ translate('messages.receiver_name') }}"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                               for="exampleFormControlInput1">{{ translate('messages.phone') }}</label>
                                        <input type="tel" value="" name="receiver_phone" id="receiver_phone"
                                               class="form-control"
                                               placeholder="{{ translate('messages.Ex: +959 xxxx') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label"
                                               for="exampleFormControlInput1">{{ translate('messages.street') }}</label>
                                        <input type="text" value="  " name="receiver_street" id="receiver_street"
                                               
                                               class="form-control"
                                               placeholder="{{ translate('messages.Ex_: 41x42 Mandalay') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="form-label"
                                                       for="exampleFormControlInput1">{{ translate('messages.house') }}</label>
                                                <input type="text" value="  " name="receiver_house" id="receiver_house"
                                                       
                                                       class="form-control"
                                                       placeholder="{{ translate('messages.Ex_: 1') }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="form-label"
                                                       for="exampleFormControlInput1">{{ translate('messages.floor') }}</label>
                                                <input type="text" value="  " name="receiver_floor" id="receiver_floor"
                                                       
                                                       class="form-control"
                                                       placeholder="{{ translate('messages.Ex_: 2nd floor') }}"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                                            @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                                            <div class="form-group mb-0">
                                                <label class="form-label text-capitalize"
                                                       for="receiver_latitude">{{ translate('messages.latitude') }}<span
                                                            class="form-label-secondary" data-toggle="tooltip"
                                                            data-placement="right"
                                                            data-original-title="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"></span></label>
                                                <input type="text" id="receiver_latitude" name="receiver_latitude"
                                                       class="form-control"
                                                       placeholder="{{ translate('messages.Ex:') }} -94.22213"
                                                       value="{{ $default_location ? $default_location['lat'] : 0 }}"
                                                       required
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="form-label text-capitalize"
                                                       for="receiver_longitude">{{ translate('messages.longitude') }}
                                                    <span
                                                            class="form-label-secondary" data-toggle="tooltip"
                                                            data-placement="right"
                                                            data-original-title="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('messages.click_on_the_map_select_your_defaul_location') }}"></span></label>
                                                <input type="text" name="receiver_longitude" class="form-control"
                                                       placeholder="{{ translate('messages.Ex:') }} 103.344322"
                                                       id="receiver_longitude"
                                                       value="{{ $default_location ? $default_location['lng'] : 0 }}"
                                                       required
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="form-label"
                                                       for="exampleFormControlInput1">{{ translate('messages.delivery_fees') }}</label>
                                                <input type="text" value="" name="delivery_fees" id="delivery_fees"
                                                       class="form-control" readonly
                                                >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group mb-0">
                                                <label class="form-label"
                                                       for="exampleFormControlInput1">{{ translate('messages.distance') }}</label>
                                                <input type="text" value="" name="distance" id="distance"
                                                       class="form-control"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <button id="button_calculate"
                                                    class="btn btn--reset">
                                                <span id="button_normal_text">{{ translate('messages.calculate') }}</span>
                                                <span id="button_loading_text" style="display: none">Calculating...</span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-md-4">
                                        <input id="pac-input2" class="controls rounded" data-toggle="tooltip"
                                               data-placement="right"
                                               data-original-title="{{ translate('messages.search_your_location_here') }}"
                                               type="text" placeholder="{{ translate('messages.search_here') }}"/>
                                        <div id="location_map_canvas2"
                                             class="overflow-hidden rounded height-285px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" id="button_reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                    <button  id="button_create_order" class="btn btn--primary">{{ translate('create_order') }}</button>
                </div>

        </div>

    </div>

@endsection


@push('script_2')
    <script>
        @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
        @php($language = $language->value ?? null)
        let language = <?php echo $language; ?>;
        $('[id=language]').val(language);

        function maintenance_mode() {
            @if (env('APP_MODE') == 'demo')
            toastr.warning('Sorry! You can not enable maintainance mode in demo!');
            @else
            Swal.fire({
                title: '{{ translate('messages.Are you sure?') }}',
                text: '{{ translate('messages.all_your_apps_and_customer_website_will_be_disabled_until_you_‘Turn_Off’ _maintenance_mode.') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#00868F',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: '{{ route('admin.maintenance-mode') }}',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })
            @endif
        };

        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + viewer).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });

        $("#favIconUpload").change(function () {
            readURL(this, 'iconViewer');
        });
    </script>
    <script
            src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&v=3.45.8">
    </script>
    <script>
        var senderAddress = "";
        var receiverAddress = "";
        function initAutocomplete() {
            var myLatLng = {
                lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                    lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('sender_latitude').value = coordinates['lat'];
                document.getElementById('sender_longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            senderAddress = results[1].formatted_address;
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('sender_latitude').value = this.position.lat();
                        document.getElementById('sender_longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };

        function initAutocomplete2() {
            var myLatLng = {
                lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas2"), {
                center: {
                    lat: {{ $default_location ? $default_location['lat'] : '-33.8688' }},
                    lng: {{ $default_location ? $default_location['lng'] : '151.2195' }}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('receiver_latitude').value = coordinates['lat'];
                document.getElementById('receiver_longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            receiverAddress = results[1].formatted_address;
                            console.log("here is address " + results[1].formatted_address)
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input2");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('receiver_latitude').value = this.position.lat();
                        document.getElementById('receiver_longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();
            initAutocomplete2();
            @php($country = \App\Models\BusinessSetting::where('key', 'country')->first())

            @if ($country)
            $("#country option[value='{{ $country->value }}']").attr('selected', 'selected').change();
            @endif
        });

        $(document).on("keydown", "input", function (e) {
            if (e.which == 13) e.preventDefault();
        });
    </script>
    <script>
        var deliveryFee = 0;
        var realDistanceValue;
        const btnCalculate = document.getElementById("button_calculate");
        const btnCreateOrder = document.getElementById("button_create_order");
        const btnReset = document.getElementById("button_reset");

        btnCalculate.addEventListener('click', calculateDeliveryFee);
        btnCreateOrder.addEventListener('click', createOrder);
        btnReset.addEventListener('click', resetFields);

        function resetFields(){

            var inputs = document.querySelectorAll('input[required]');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
            }
            document.getElementById("distance").value = '';
            document.getElementById("delivery_fees").value = '';
        }
        function calculateDeliveryFee()
        {

            var inputs = document.querySelectorAll('input[required]');

            // Check each input for emptiness
            for (var i = 0; i < inputs.length; i++) {
                if (!inputs[i].value) {
                    // Show the validation message for empty fields
                    alert(inputs[i].id + ' is required.');
                    return;
                }
            }
            var button_normal_text = document.getElementById("button_normal_text");
            var button_loading_text = document.getElementById("button_loading_text");
            button_normal_text.style.display = 'none';
            button_loading_text.style.display = 'inline';
            var button_loading_text = document.getElementById("button_loading_text");

            // Get values from input fields
            var senderLatitude = document.getElementById("sender_latitude").value;
            const senderLongitude =
                document.getElementById("sender_longitude").value;
            const receiverLatitude =
                document.getElementById("receiver_latitude").value;
            const receiverLongitude =
                document.getElementById("receiver_longitude").value;
            // ... get other input values ...
            console.log("======");
            // API Call 1
            const distanceAPI = `https://www.foodie-mandalay.click/api/v1/config/distance-api?origin_lat=${senderLatitude}&origin_lng=${senderLongitude}&destination_lat=${receiverLatitude}&destination_lng=${receiverLongitude}&mode=walking`;

            makeAPICall(distanceAPI, function (response1) {
                const distanceValue = response1.rows[0].elements[0].distance.value;
                realDistanceValue = distanceValue / 1000;
                console.log(" distance is  " + distanceValue);
                console.log(" distance is real  " + realDistanceValue);
                // API Call 2
                const extraChargeAPI = `https://www.foodie-mandalay.click/api/v1/vehicle/extra_charge?distance=${realDistanceValue}`;

                makeAPICall(extraChargeAPI, function (response2) {
                    const extraCharge = response2;

                    console.log(" here is  es 2 " + response2);
                    // Calculate final delivery fee
                    deliveryFee = realDistanceValue * 200 + extraCharge;
                    console.log(deliveryFee + "here is deliveryffee");
                    // Set the value to the HTML inputs
                    document.getElementById("distance").value = realDistanceValue;
                    document.getElementById("delivery_fees").value = deliveryFee;
                    button_normal_text.style.display = 'inline';
                    button_loading_text.style.display = 'none';
                });
            });
        }

        function makeAPICall(url, callback) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                }
            };
            xhr.open("GET", url, true);
            xhr.send();
        }

        function createOrder() {
            var inputs = document.querySelectorAll('input[required]');
            var delivery_fees = document.getElementById("delivery_fees").value;
            var distance = document.getElementById("distance").value;
            if(!delivery_fees.trim() || !distance.trim()) {
                alert("please calculate distance") ;
                return;
            }

            // Check each input for emptiness
            for (var i = 0; i < inputs.length; i++) {
                if (!inputs[i].value) {
                    // Show the validation message for empty fields
                    alert(inputs[i].id + ' is required.');
                    return;
                }
            }
            var sender_name = document.getElementById("sender_name").value;
            var sender_phone = document.getElementById("sender_phone").value;
            var sender_street = document.getElementById("sender_street").value;
            var category = document.getElementById("category").value;
            var sender_house = document.getElementById("sender_house").value;
            var sender_floor = document.getElementById("sender_floor").value;

            var receiver_name = document.getElementById("receiver_name").value;
            var receiver_phone = document.getElementById("receiver_phone").value;
            var receiver_street = document.getElementById("receiver_street").value;
            var receiver_house = document.getElementById("receiver_house").value;
            var receiver_floor = document.getElementById("receiver_floor").value;
            var senderLatitude = document.getElementById("sender_latitude").value;
            var senderLongitude = document.getElementById("sender_longitude").value;

            var receiverLatitude = document.getElementById("receiver_latitude").value;
            var receiverLongitude = document.getElementById("receiver_longitude").value;
            var receiver_details = {
                "id": null,
                "address_type": "others",
                "contact_person_number": receiver_phone,
                "address": receiverAddress,
                "additional_address": null,
                "latitude": receiverLatitude,
                "longitude":receiverLongitude,
                "zone_id": 2,
                "zone_ids": [
                    2
                ],
                "_method": null,
                "contact_person_name": receiver_name,
                "road": receiver_street,
                "house": receiver_house,
                "floor": receiver_floor,
                "area_ids": null
            };
            console.log("======");
            var receiver_detailsString = JSON.stringify(receiver_details);
            var senderAddress = document.getElementById("sender_longitude").value;
            // Additional: Call the next API with POST method
            const placeOrderAPI =
                "https://www.foodie-mandalay.click/api/v1/customer/order/place";

            const orderPayload = {
                cart: [],
                order_amount: deliveryFee,
                order_type: "parcel",
                payment_method: "cash_on_delivery",
                distance: realDistanceValue,
                discount_amount: 0,
                tax_amount: 0,
                address: senderAddress,
                receiver_details: receiver_detailsString,
                latitude: senderLatitude,
                longitude: senderLongitude,
                contact_person_name: sender_name,
                contact_person_number: sender_phone,
                address_type: "others",
                parcel_category_id: category,
                charge_payer: "receiver",
                road: sender_street,
                house: sender_house,
                floor: sender_floor,
                dm_tips: 0,
                unavailable_item_note: "",
                delivery_instruction: " ",
                cutlery: 0,
                partial_payment: 0,
                guest_id: 11,
                is_buy_now: 0,
            };
            const headers = {
                moduleId: 2,
                zoneId: [2],
            };
            makePostAPICall(
                placeOrderAPI,
                orderPayload,
                headers,
                function (response3) {
                    Swal.fire({
                        title: "Good job!",
                        text: "Your order is created!",
                        icon: "success"
                    });
                    location.reload();

                }
            );
        }

        function makePostAPICall(url, data, headers, callback) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        callback(response);
                    } else {
                        console.error("Error during API call:", xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.open('POST', url, true);

            // Set headers
            for (const header in headers) {
                xhr.setRequestHeader(header, headers[header]);
            }

            // Set the request body
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(data));
        }
    </script>
@endpush