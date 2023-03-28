<div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#cityexpand">
    <h3 class="m-0">CITY</h3>
    <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
</div>
<div class="collapse exbody show" id="cityexpand">
    <div class="innerbody">
        @foreach ($cities->slice(0, 5) as $city)
            <div class="d-flex align-items-center mb-2">
                <label class="custom-checkbox">
                    {{strtoupper($city->name)}}
                    {{-- <input type="checkbox" name="search_cars_filter_city[]"
                    id="search_cars_filter_city_home_{{$city->id}}" value="{{$city->id}}"
                    onclick="search_by_left_filters();DisplayFilter('{{$city->name}}');" /> --}}
                    <input type="checkbox" name="filter_city" id="filter_city_{{ $city->id }}"
                        value="{{ $city->id }}" />
                    <span class="checkmark"></span>
                </label>
                <div class="salecounter ms-auto">
                    @if($for == "cars")
                    {{ car_in_city($city->id) }}
                    @else
                    {{showroom_in_city($city->id)}}
                    @endif
                </div>
            </div>
        @endforeach

        @if ($cities->count() > 5)
            <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup-cities">
                <a style="cursor:pointer;">More Cities</a>
            </div>

            @include('front.search-cars.cities-more-popup', [
                'cities' => $cities,
                'div_id' => 'more-detail-popup-cities',
                'for' => $for,
            ])
        @endif
    </div>
</div>


<script>
    $(document).ready(function() {
        $("input[name=filter_city]").change(function() {
            if ($(this).is(":checked")) {
                $("#province-expandbox").hide();
                RemoveDisplayFilter('fil_city');
                $("input[name=filter_city]").prop('checked', false);
                $(this).prop('checked', true);
                DisplayFilter('fil_city', $(this).parent().text());
                SearchFilterMain()
            } else {
                RemoveDisplayFilter('fil_city');
                $(this).prop('checked', false);
                $("#province-expandbox").show();
                SearchFilterMain()
            }
        });
    });
</script>
