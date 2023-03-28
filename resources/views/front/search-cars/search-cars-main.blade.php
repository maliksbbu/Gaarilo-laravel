@extends('front.layout.main')
@section('content')
    <div id="carforsale">

        <section class="section-space" id="filtersec">
            <div class="container-xl">
                <h2 class="page-heading mb-5">Cars For Sale In Pakistan</h2>

                <div class="row">
                    @include('front.search-cars.left-search-filter', [
                        'cities' => $cities,
                        'proviences' => $proviences,
                        'brands' => $brands,
                        'models' => $models,
                        'versions' => $versions,
                        'colors' => $colors,
                        'vehicle_types' => $vehicle_types,
                        'engine_types' => $engine_types,
                        'manual_cars' => $manual_cars,
                        'automatic_cars' => $automatic_cars,
                        'local_assembly' => $local_assembly,
                        'importad_assembly' => $importad_assembly,
                    ])
                    <div class="col-xl-9 col-lg-8 col-md-7">


                        <div class="sort-header mb-4">
                            <div class="d-flex align-items-center">
                                <label class="me-3">Sort By</label>
                                <div class="postad-select w-50 select-all">
                                    <select>
                                        <option value="updated_at|desc" selected>Updated Date: Recent First</option>
                                        <option value="updated_at|asc">Update Date: Oldest First</option>
                                        <option value="price_range|asc">Price: Low to High</option>
                                        <option value="price_range|desc">Price: High to Low</option>
                                        <option value="mileage|asc">Mileage : Low To High</option>
                                        <option value="mileage|desc">Mileage: High To Low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="btn-group ms-auto sort-btngroup" role="group"
                                aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="search_car_list_radio"
                                    id="search_car_list_radio" autocomplete="off">
                                <label class="btn btn-outline-primary search-car-list-cl" for="btnradio1"> <i
                                        class="fa fa-list me-2"></i>
                                    List</label>
                                <input type="radio" class="btn-check" name="search_car_grid_radio"
                                    id="search_car_grid_radio" autocomplete="off" checked>
                                <label class="btn btn-outline-primary search-car-grid-cl" for="btnradio2"> <i
                                        class="fa fa-th-large me-2"></i>
                                    Grid</label>
                            </div>
                        </div>
                        <input type="hidden" name="select_car_orders" id="select_car_orders" value="updated_at|desc" />
                        <input type="hidden" name="search_view_selected" id="search_view_selected" value="grid" />
                        <input type="hidden" name="search_filter_showroom" id="search_filter_showroom" />
                        <input type="hidden" name="search_filter_condition" id="search_filter_condition" />
                        <div class="search-cars-main-view">
                            @include('front.search-cars.search-cars-grid', [
                                'cars' => $cars,
                            ])
                        </div>

                        <div class="d-flex align-items-center justify-content-center">

                        <nav aria-label="Page navigation example">
                            <ul class="pagination" id="pagination-column">
                            </ul>
                        </nav>

                        <input type="hidden" id="page" name="page" value="1"/>

                        </div>

                    </div>
                </div>


            </div>
        </section>

    </div>
@endsection

@section('scripts')
    <script>
        /*
                            Reference: http://jsfiddle.net/BB3JK/47/
                            */

        $('select').each(function() {
            var $this = $(this),
                numberOfOptions = $(this).children('option').length;

            $this.addClass('select-hidden');
            $this.wrap('<div class="select"></div>');
            $this.after('<div class="select-styled"></div>');

            var $styledSelect = $this.next('div.select-styled');
            $styledSelect.text($this.children('option').eq(0).text());

            var $list = $('<ul />', {
                'class': 'select-options'
            }).insertAfter($styledSelect);

            for (var i = 0; i < numberOfOptions; i++) {
                $('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
                //if ($this.children('option').eq(i).is(':selected')){
                //  $('li[rel="' + $this.children('option').eq(i).val() + '"]').addClass('is-selected')
                //}
            }

            var $listItems = $list.children('li');
            //console.log($this.val());
            $styledSelect.click(function(e) {
                e.stopPropagation();
                $('div.select-styled.active').not(this).each(function() {
                    $(this).removeClass('active').next('ul.select-options').hide();
                });
                $(this).toggleClass('active').next('ul.select-options').toggle();
            });

            $listItems.click(function(e) {
                $("#select_car_orders").val($(this).attr('rel'));
                PreLoader();
                SearchFilterMain();
                e.stopPropagation();
                $styledSelect.text($(this).text()).removeClass('active');
                $this.val($(this).attr('rel'));
                $list.hide();
                //console.log($this.val());
            });

            $(document).click(function() {
                //console.log($this.val());
                $styledSelect.removeClass('active');
                $list.hide();
            });

        });
    </script>

    <script>
        var paginationCount = {{$pagination_count}};
        var showroomsArray = {!! json_encode($showrooms) !!};

        $(document).ready(function() {
            $(".Pause-Redirect").data('click', false);
            $(".Pause-Redirect").on('click', function()
            {
                $(".Pause-Redirect").data('click', true);
            });

            RenderPagination(paginationCount);

            $("input[name=filter_city]").change(function() {
                if ($(this).is(":checked")) {
                    $("#province-expandbox").hide();
                    RemoveDisplayFilter('fil_city');
                    $("input[name=filter_city]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_city', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_city');
                    $(this).prop('checked', false);
                    $("#province-expandbox").show();
                    SearchFilterMain();
                }
            });

            $("input[name=filter_provience]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_province');
                    $("input[name=filter_provience]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_province', $(this).parent().text());
                    PopulateCityView($(this).val());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_province');
                    $(this).prop('checked', false);
                    PopulateCityView(0);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_brand]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_brand');
                    $("input[name=filter_brand]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_brand', $(this).parent().text());
                    PopulateModelView($(this).val());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_brand');
                    $(this).prop('checked', false);
                    PopulateModelView(0);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_model]").change(function() {
                if ($(this).is(":checked")) {
                    $("#make_expandbox").hide();
                    RemoveDisplayFilter('fil_model');
                    $("input[name=filter_model]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_model', $(this).parent().text());
                    PopulateVersionView($(this).val());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_model');
                    $(this).prop('checked', false);
                    $("#make_expandbox").show();
                    PopulateVersionView(0);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_version]").change(function() {
                if ($(this).is(":checked")) {
                    $("#make_expandbox").hide();
                    $("#model_expandbox").hide();
                    RemoveDisplayFilter('fil_version');
                    $("input[name=filter_version]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_version', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_version');
                    $(this).prop('checked', false);
                    $("#model_expandbox").show();
                    $("#make_expandbox").show();
                    SearchFilterMain();
                }
            });

            $("input[name=filter_registered]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_registered');
                    $("input[name=filter_registered]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_registered', "Registered: "+$(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_registered');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_transmission]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_transmission');
                    $("input[name=filter_transmission]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_transmission', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_transmission');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_engine]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_engine');
                    $("input[name=filter_engine]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_engine', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_engine');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_assembly]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_assembly');
                    $("input[name=filter_assembly]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_assembly', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_assembly');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_verified]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_verified');
                    $("input[name=filter_verified]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_verified', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_verified');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_vehicle]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_vehicle');
                    $("input[name=filter_vehicle]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_vehicle', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_vehicle');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

            $("input[name=filter_color]").change(function() {
                if ($(this).is(":checked")) {
                    RemoveDisplayFilter('fil_color');
                    $("input[name=filter_color]").prop('checked', false);
                    $(this).prop('checked', true);
                    DisplayFilter('fil_color', $(this).parent().text());
                    SearchFilterMain();
                } else {
                    RemoveDisplayFilter('fil_color');
                    $(this).prop('checked', false);
                    SearchFilterMain();
                }
            });

        });

        window.onload = (event) => {
            PreLoader("hide");
            var Query = (new URL(location.href));
            var QueryString = Query.searchParams.get('verified');
            if(QueryString && QueryString != 0)
            {
                PreLoader();
                $("#verified").prop('checked', true);
                DisplayFilter('fil_verified', $("#verified").parent().text());
                SearchFilterMain();
            }
            var QueryString = Query.searchParams.get('city');
            if(QueryString && QueryString != 0)
            {
                PreLoader();
                $("#province-expandbox").hide();
                $("#filter_city_"+QueryString).prop('checked', true);
                DisplayFilter('fil_city', $("#filter_city_"+QueryString).parent().text());
                SearchFilterMain();
            }
            var QueryString = Query.searchParams.get('model_id');
            if(QueryString && QueryString != 0)
            {
                PreLoader();
                $("#make_expandbox").hide();
                $("#filter_model_"+QueryString).prop('checked', true);
                DisplayFilter('fil_model', $("#filter_model_"+QueryString).parent().text());
                PopulateVersionView($("#filter_model_"+QueryString).val());
                SearchFilterMain();
            }
            var QueryString = Query.searchParams.get('brand_id');
            if(QueryString && QueryString != 0)
            {
                PreLoader();
                $("#filter_brand_"+QueryString).prop('checked', true);
                DisplayFilter('fil_brand', $("#filter_brand_"+QueryString).parent().text());
                PopulateModelView($("#filter_brand_"+QueryString).val());
                SearchFilterMain();
            }
            var QueryString = Query.searchParams.get('vehicle_type');
            if(QueryString && QueryString != 0)
            {
                PreLoader();
                $("#filter_vehicle_"+QueryString).prop('checked', true);
                DisplayFilter('fil_vehicle', $("#filter_vehicle_"+QueryString).parent().text());
                SearchFilterMain();
            }
            var QueryString = Query.searchParams.get('price_range');
            if(QueryString && QueryString != 0)
            {
                $("#price_from").val(0);
                $("#price_to").val(QueryString);
                ApplyAlphaNumericFilters("fil_price");
            }
            var QueryString = Query.searchParams.get('showroom');
            if(QueryString && QueryString != 0)
            {
                $("#search_filter_showroom").val(QueryString);
                ApplyAlphaNumericFilters("fil_showroom");
            }
            var QueryString = Query.searchParams.get('condition');
            if(QueryString && QueryString != 0)
            {
                $("#search_filter_condition").val(QueryString);
                ApplyAlphaNumericFilters("fil_condition");
            }
        };

        $(document).on('click', '.search-car-list-cl', function(e) {
            PreLoader();
            $("#search_view_selected").val('list');
            $("input[name=search_car_grid_radio]").prop('checked', false);
            $("input[name=search_car_list_radio]").prop('checked', true);
            SearchFilterMain();
            e.preventDefault();
        });

        $(document).on('click', '.search-car-grid-cl', function(e) {
            PreLoader();
            $("#search_view_selected").val('grid');
            $("input[name=search_car_list_radio]").prop('checked', false);
            $("input[name=search_car_grid_radio]").prop('checked', true);
            SearchFilterMain();
            e.preventDefault();
        });

        function DisplayFilter(id, name) {
            var html = "";
            html += '<div class="d-flex align-items-center mb-2" id="' + id + '">';
            html += '<label>' + name + '</label>';
            html += '<a class="ms-auto filter-cross" href=javascript:RemoveFilter("' + id +
                '")><i class="fa fa-times"></i></a></div>';
            $("#filterToShow").append(html);
        }

        function ClosingCityDialog(submit) {
            if (submit) {
                $('#more-detail-popup-cities').modal('hide');
                var selectedValue = $("input[name=filter_city]:checked").val();
                $("#filter_city_" + selectedValue).prop('checked', true);
            } else {
                $('#more-detail-popup-cities').modal('hide');
                $("input[name=filter_city]").prop('checked', false);
                $("#province-expandbox").show();
                RemoveDisplayFilter("fil_city");
                SearchFilterMain();
            }
        }

        function ClosingBrandDialog(submit) {
            if (submit) {
                $('#more-detail-popup-brands').modal('hide');
                var selectedValue = $("input[name=filter_brand]:checked").val();
                $("#filter_brand_" + selectedValue).prop('checked', true);
            } else {
                $('#more-detail-popup-brands').modal('hide');
                $("input[name=filter_brand]").prop('checked', false);
                RemoveDisplayFilter("fil_brand");
                PopulateModelView(0);
                SearchFilterMain();
            }
        }

        function ClosingModelDialog(submit) {
            if (submit) {
                $('#more-detail-popup-model').modal('hide');
                var selectedValue = $("input[name=filter_model]:checked").val();
                $("#filter_model_" + selectedValue).prop('checked', true);
            } else {
                $('#more-detail-popup-model').modal('hide');
                $("input[name=filter_model]").prop('checked', false);
                $("#make_expandbox").show();
                RemoveDisplayFilter("fil_model");
                PopulateVersionView(0);
                SearchFilterMain();
            }
        }

        function ClosingVersionDialog(submit) {
            if (submit) {
                $('#more-detail-popup-version').modal('hide');
                var selectedValue = $("input[name=filter_version]:checked").val();
                $("#filter_version_" + selectedValue).prop('checked', true);
            } else {
                $('#more-detail-popup-version').modal('hide');
                $("input[name=filter_version]").prop('checked', false);
                $("#model_expandbox").show();
                $("#make_expandbox").show();
                RemoveDisplayFilter("fil_version");
                SearchFilterMain();
            }
        }

        function RemoveDisplayFilter(id) {
            PreLoader();
            $("#" + id).remove();
        }

        function RemoveFilter(id) {
            RemoveDisplayFilter(id);
            switch (id) {
                case "fil_city":
                    $("input[name=filter_city]").prop('checked', false);
                    $("#province-expandbox").show();
                    SearchFilterMain();
                    break;
                case "fil_province":
                    $("input[name=filter_provience]").prop('checked', false);
                    RemoveFilter("fil_city");
                    PopulateCityView(0);
                    SearchFilterMain();
                    break;
                case "fil_brand":
                    $("input[name=filter_brand]").prop('checked', false);
                    RemoveFilter("fil_model");
                    PopulateModelView(0);
                    SearchFilterMain();
                    break;
                case "fil_model":
                    $("input[name=filter_model]").prop('checked', false);
                    $("#make_expandbox").show();
                    RemoveFilter("fil_version");
                    PopulateVersionView(0);
                    SearchFilterMain();
                    break;
                case "fil_version":
                    $("input[name=filter_version]").prop('checked', false);
                    $("#model_expandbox").show();
                    $("#make_expandbox").show();
                    SearchFilterMain();
                    break;
                case "fil_price":
                    $("#search_cars_price_from").val("");
                    $("#search_cars_price_to").val("");
                    $("#price_from").val("");
                    $("#price_to").val("");
                    SearchFilterMain();
                    break;
                case "fil_year":
                    $("#search_cars_year_from").val("");
                    $("#search_cars_year_to").val("");
                    $("#year_from").val("");
                    $("#year_to").val("");
                    SearchFilterMain();
                    break;
                case "fil_mileage":
                    $("#search_cars_mileage_from").val("");
                    $("#search_cars_mileage_to").val("");
                    $("#mileage_from").val("");
                    $("#mileage_to").val("");
                    SearchFilterMain();
                    break;
                case "fil_capacity":
                    $("#search_cars_capacity_from").val("");
                    $("#search_cars_capacity_to").val("");
                    $("#capacity_from").val("");
                    $("#capacity_to").val("");
                    SearchFilterMain();
                    break;
                case "fil_registered":
                    $("input[name=filter_registered]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_transmission":
                    $("input[name=filter_transmission]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_engine":
                    $("input[name=filter_engine]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_assembly":
                    $("input[name=filter_assembly]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_key":
                    $("#search_cars_filter_keword").val("");
                    $("#filter_keyword").val("");
                    SearchFilterMain();
                    break;
                case "fil_verified":
                    $("input[name=filter_verified]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_vehicle":
                    $("input[name=filter_vehicle]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_color":
                    $("input[name=filter_color]").prop('checked', false);
                    SearchFilterMain();
                    break;
                case "fil_showroom":
                    $("#search_filter_showroom").val("");
                    SearchFilterMain();
                    break;
                case "fil_condition":
                    $("#search_filter_condition").val("");
                    SearchFilterMain();
                    break;
                default:
                    break;
            }

        }

        function PopulateCityView(province_id) {
            $.ajax({
                url: '{{ route('filterapi.get_cities') }}',
                type: 'POST',
                data: {
                    province_id: province_id,
                    for: 'cars',
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {

                        $("#city_expandbox").html(data.html);

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function PopulateModelView(brand_id) {
            $.ajax({
                url: '{{ route('filterapi.get_models') }}',
                type: 'POST',
                data: {
                    brand_id: brand_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {

                        $("#model_expandbox").html(data.html);

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function PopulateVersionView(model_id) {
            $.ajax({
                url: '{{ route('filterapi.get_versions') }}',
                type: 'POST',
                data: {
                    model_id: model_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {

                        $("#version_expandbox").html(data.html);

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function SearchFilterMain(view_update = true) {
            var search_view_selected = $('#search_view_selected').val();
            var search_cars_filter_city_val = $("input[name=filter_city]:checked").val();
            var search_cars_filter_provience_val = $("input[name=filter_provience]:checked").val();
            var search_cars_filter_brand_val = $("input[name=filter_brand]:checked").val();
            var search_cars_filter_model_val = $("input[name=filter_model]:checked").val();
            var search_cars_filter_version_val = $("input[name=filter_version]:checked").val();
            var search_cars_filter_registered_val = $("input[name=filter_registered]:checked").val();
            var search_cars_filter_transmission_val = $("input[name=filter_transmission]:checked").val();
            var search_cars_filter_engine_type_val = $("input[name=filter_engine]:checked").val();
            var search_cars_filter_assembly_val = $("input[name=filter_assembly]:checked").val();
            var search_cars_filter_vehicle_val = $("input[name=filter_vehicle]:checked").val();
            var search_cars_filter_verified_val = $("input[name=filter_verified]:checked").val();
            var search_cars_filter_color_val = $("input[name=filter_color]:checked").val();

            var search_cars_price_from_val = $('#search_cars_price_from').val();
            var search_cars_price_to_val = $('#search_cars_price_to').val();
            var search_cars_year_from_val = $('#search_cars_year_from').val();
            var search_cars_year_to_val = $('#search_cars_year_to').val();
            var search_cars_mileage_from_val = $('#search_cars_mileage_from').val();
            var search_cars_mileage_to_val = $('#search_cars_mileage_to').val();
            var search_cars_capacity_from_val = $('#search_cars_capacity_from').val();
            var search_cars_capacity_to_val = $('#search_cars_capacity_to').val();
            var search_cars_filter_showroom_id = $("#search_filter_showroom").val();
            var search_cars_filter_condition = $("#search_filter_condition").val();

            var search_cars_filter_keword_val = $("#search_cars_filter_keword").val();

            var select_car_orders_val = $('#select_car_orders').val();
            var page = $('#page').val();

            $.ajax({
                url: '{{ route('filterapi.search-cars-by-view') }}',
                type: 'POST',
                data: {
                    search_view_selected: search_view_selected,
                    search_cars_filter_city_val: search_cars_filter_city_val,
                    search_cars_filter_provience_val: search_cars_filter_provience_val,
                    search_cars_filter_brand_val: search_cars_filter_brand_val,
                    search_cars_filter_model_val: search_cars_filter_model_val,
                    search_cars_filter_version_val: search_cars_filter_version_val,
                    search_cars_price_from_val: search_cars_price_from_val,
                    search_cars_price_to_val: search_cars_price_to_val,
                    search_cars_year_from_val: search_cars_year_from_val,
                    search_cars_year_to_val: search_cars_year_to_val,
                    search_cars_mileage_from_val: search_cars_mileage_from_val,
                    search_cars_mileage_to_val: search_cars_mileage_to_val,
                    search_cars_capacity_from_val: search_cars_capacity_from_val,
                    search_cars_capacity_to_val: search_cars_capacity_to_val,
                    search_cars_filter_registered_val: search_cars_filter_registered_val,
                    search_cars_filter_transmission_val: search_cars_filter_transmission_val,
                    search_cars_filter_engine_type_val: search_cars_filter_engine_type_val,
                    search_cars_filter_assembly_val: search_cars_filter_assembly_val,
                    search_cars_filter_keword_val: search_cars_filter_keword_val,
                    select_car_orders_val: select_car_orders_val,
                    search_cars_filter_vehicle_val:search_cars_filter_vehicle_val,
                    search_cars_filter_verified_val: search_cars_filter_verified_val,
                    search_cars_filter_color_val: search_cars_filter_color_val,
                    search_cars_filter_showroom_id: search_cars_filter_showroom_id,
                    search_cars_filter_condition: search_cars_filter_condition,
                    page: page,
                    view_update: view_update,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {

                        $('.search-cars-main-view').html(data.html);
                        RenderPagination(data.pagination_count, data.pageCounter);
                        paginationCount = data.pagination_count;
                        PreLoader("hide");
                    }
                },
                error: function(error) {
                    console.log(error);
                    PreLoader("hide");
                }
            });
        }

        function ApplyAlphaNumericFilters(filter)
        {

            switch (filter) {

                case "fil_price":
                    var price_from, price_to;
                    price_from = $("#price_from").val();
                    price_to = $("#price_to").val();

                    if(price_to != "" && price_to != null &&
                    $("#search_cars_price_from").val() == "" && $("#search_cars_price_to").val() == "" )
                    {
                        PreLoader();
                        if(price_from == "" || price_from == null)
                        {
                            price_from = "0";
                            $("#price_from").val(price_from);
                        }
                        $("#search_cars_price_from").val(price_from);
                        $("#search_cars_price_to").val(price_to);
                        var message = "Price: "+price_from+" - "+price_to;
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;
                case "fil_year":
                    var year_from, year_to;
                    year_from = $("#year_from").val();
                    year_to = $("#year_to").val();

                    if(year_from != "" && year_from != null && year_to != "" && year_to != null &&
                    $("#search_cars_year_from").val() == ""  && $("#search_cars_year_to").val() == "")
                    {
                        PreLoader();
                        $("#search_cars_year_from").val(year_from);
                        $("#search_cars_year_to").val(year_to);
                        var message = "Year: "+year_from+" - "+year_to;
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;
                case "fil_mileage":
                    var mileage_from, mileage_to;
                    mileage_from = $("#mileage_from").val();
                    mileage_to = $("#mileage_to").val();

                    if(mileage_from != "" && mileage_from != null && mileage_to != "" && mileage_to != null &&
                    $("#search_cars_mileage_from").val() == "" && $("#search_cars_mileage_to").val() == "" )
                    {
                        PreLoader();
                        $("#search_cars_mileage_from").val(mileage_from);
                        $("#search_cars_mileage_to").val(mileage_to);
                        var message = "Mileage: "+mileage_from+" - "+mileage_to;
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;
                case "fil_capacity":
                    var capacity_from, capacity_to;
                    capacity_from = $("#capacity_from").val();
                    capacity_to = $("#capacity_to").val();

                    if(capacity_from != "" && capacity_from != null && capacity_to != "" && capacity_to != null &&
                        $("#search_cars_capacity_from").val() == "" && $("#search_cars_capacity_to").val() == "" )
                        {
                        PreLoader();
                        $("#search_cars_capacity_from").val(capacity_from);
                        $("#search_cars_capacity_to").val(capacity_to);
                        var message = "CC: "+capacity_from+" - "+capacity_to;
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;

                case "fil_key":
                    var keyword = $("#filter_keyword").val();

                    if(keyword != "" && keyword != null &&
                    $("#search_cars_filter_keword").val() == "" )
                    {
                        PreLoader();
                        $("#search_cars_filter_keword").val(keyword);
                        var message = "Keyword: "+keyword;
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;
                case "fil_showroom":
                    var keyword = $("#search_filter_showroom").val();

                    if(keyword != "" && keyword != null)
                    {
                        PreLoader();
                        var message = "Showroom: "+GetShowroomName(keyword);
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;
                case "fil_condition":
                    var keyword = $("#search_filter_condition").val();

                    if(keyword != "" && keyword != null)
                    {
                        PreLoader();
                        var message = "Condition: "+keyword;
                        DisplayFilter(filter, message);
                        SearchFilterMain();
                    }
                    break;

                default:
                    break;
            }
        }

        function FavouriteMethod (id)
        {
            var selectedElement = $("#heart_"+id);
            if(!selectedElement.hasClass("text-danger"))
            {
                Favourite(id);
            }
            else
            {
                UnFavourite(id);
            }
        }

        function Favourite (id)
        {
            PreLoader();
            $.ajax({
                url: "{{ route('ad-favourite','') }}/"+id,
                type: 'GET',

                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        if(data.result == 1)
                        {
                            $("#heart_"+data.data).addClass("text-danger");
                            ShowToaster('Success', data.message);
                            PreLoader("hide");
                        }
                        else
                        {
                            ShowToaster('Error', data.message);
                            PreLoader("hide");
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                    PreLoader("hide");
                }
            });
        }

        function UnFavourite (id)
        {
            PreLoader();
            $.ajax({
                url: '{{ route('ad-unfavourite', '') }}/'+id,
                type: 'GET',

                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        if(data.result == 1)
                        {
                            $("#heart_"+data.data).removeClass("text-danger");
                            ShowToaster('Success', data.message);
                            PreLoader("hide");
                        }
                        else
                        {
                            ShowToaster('Error', data.message);
                            PreLoader("hide");
                        }

                    }
                },
                error: function(error) {
                    console.log(error);
                    PreLoader("hide");
                }
            });
        }

        function RenderPagination (number = 1, selectedValue = 1)
        {
            if(number == 0)
            {
                $("#pagination-column").hide();
                return;
            }
            var html = "";
            for (let index = 0; index < number; index++)
            {
                var numberInConsideration = index + 1;
                if(numberInConsideration == selectedValue)
                {
                    html += '<li class="page-item"><a class="page-link active" id="page_'+numberInConsideration+'" href=javascript:TurnPage("'+numberInConsideration+'")>'+numberInConsideration+'</a></li>';
                }
                else
                {
                    html += '<li class="page-item"><a class="page-link" id="page_'+numberInConsideration+'" href=javascript:TurnPage("'+numberInConsideration+'")>'+numberInConsideration+'</a></li>';
                }
            }
            html += '<li class="page-item"><a class="page-link" id="page_next" href=javascript:TurnPage("NEXT")>Next ></a></li>';
            html += '<li class="page-item"><a class="page-link" id="page_last" href=javascript:TurnPage("LAST")>Last >></a></li>';

            $("#pagination-column").html(html);
            $("#pagination-column").show();
            $("#page").val(selectedValue);
        }

        function TurnPage(input)
        {

            if(input == "NEXT")
            {
                var currentCount = parseInt($("#page").val());
                if(currentCount < paginationCount)
                {
                    PreLoader();
                    currentCount = currentCount + 1;
                    $(".page-link").removeClass("active");
                    $("#page_"+currentCount).addClass("active");
                    $("#page").val(currentCount);
                    SearchFilterMain(false);
                }

            }
            else if(input == "LAST")
            {
                PreLoader();
                $(".page-link").removeClass("active");
                $("#page_"+paginationCount).addClass("active");
                $("#page").val(paginationCount);
                SearchFilterMain(false);
            }
            else
            {
                PreLoader();
                $(".page-link").removeClass("active");
                $("#page_"+input).addClass("active");
                $("#page").val(input);
                SearchFilterMain(false);
            }

        }

        function GetShowroomName(id)
        {
            var name = "";
            showroomsArray.forEach(element => {
                if(element.id == id)
                {
                    name = element.name;
                }
            });
            return name;
        }

    </script>
@endsection
