<?php
use App\Models\Favourite;
use App\Models\CarOffer;
?>
<div class="row mb-5">
    @foreach ($cars as $car)
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="detail-box" onclick="car_detail({{ $car->id }})">
            <div class="db-top">
                <img src="{{ $car->image }}" class="w-100" style="cursor: pointer; rotate:{{$car->rotation}}deg;">
                <div class="imgcounter" style="cursor: pointer;">
                    <span class="me-2">{{ $car->exterior_images->count() + $car->interior_images->count() + $car->hotspot_images->count() }}</span>
                    <img src="{{ URL::asset('front/images/images-icon.png') }}">
                </div>
                @if(Session::has('user'))
                    <?php $response = CarOffer::OfferOnCar(Session::get('user')->id ,  $car->id); ?>
                    @if(count((array)$response) != 0)
                    @switch($response['color'])
                        @case("blue")
                            <div class="dbprice bg-blue" style="cursor: pointer" >
                                <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                                <p class="m-0">{{$response['message']}}</p>
                            </div>
                            @break
                        @case("green")
                            <div class="dbprice" style="cursor: pointer; background-color: green">
                                <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                                <p class="m-0">{{$response['message']}}</p>
                            </div>
                            @break
                        @case("red")
                            <div class="dbprice" style="cursor: pointer">
                                <h5 class="m-0">PKR. {{number_format($response['price'])}}</h5>
                                <p class="m-0">{{$response['message']}}</p>
                            </div>
                            @break

                        @default
                    @endswitch
                    @endif
                    @endif
                @if ($car->video)
                <a href="javascript:OpenEditModal({{ $car->id }})" class="Pause-Redirect">
                    <div class="vdo-icon">
                        <i class="fa fa-play"></i>
                    </div>
                </a>
                @endif
            </div>
            <div class="modal fade show popup-small" id="edit-vtype-popup_{{ $car->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ car_name($car->id) }}</h5>
                            <button type="button" onclick="closemodel({{ $car->id }})" class="close Pause-Redirect" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <video width="100%" controls type="*" src="{{ $car->video }}"></video>
                    </div>
                </div>
            </div>
            <div class="db-body">
                <h5 class="m-0 mb-3" style="cursor: pointer;" title="{{car_name($car->id)}}">
                    <div style="overflow:hidden; text-overflow:ellipsis; width:275px; white-space:nowrap;">
                        {{ car_name($car->id) }}
                    </div>

                </h5>
                <h6 class="mb-2">{{ city_name($car->city_id) }}</h6>
                <div class="d-flex align-items-center mb-3">
                    <h3 class="m-0" style="cursor: pointer;">PKR.
                        {{ number_format($car->price_range) }}
                    </h3>
                    <a target="_blank" href="{{ url('showroom-detail/' . $car->showroom_id) }}" style="cursor: pointer;" class="ms-auto Pause-Redirect"><img src="@if ($car->showroom != null) {{ $car->showroom->logo }} @endif" class="pkrimg"></a>
                </div>


                <div class="m-0 mb-2 sepreator-text d-flex flex-wrap" style="cursor: pointer;">
                    <div><span class="me-3">{{ $car->car_year }}</span> |</div>
                    <div><span class="mx-3 ps-2">{{ $car->mileage }} km </span> </div>
                    <div>|<span class="ms-3">{{ $car->engine_type }}</span></div>
                </div>
                <div class="m-0 mb-3 sepreator-text d-flex flex-wrap" style="cursor: pointer;">
                    <div></div>
                    <div><span class="me-3">{{ $car->engine_capacity }} cc </span> |</div>
                    <div><span class="ms-3">{{ $car->transmission }}</span></div>
                </div>
                <?php $new_date = strtotime($car->updated_at); ?>
                <div class="align-items-center d-flex mb-3 mb-4 text-muted" style="cursor: pointer;">Last Updated:
                    {{ date('d-m-Y', $new_date) }}
                    @if (!Session::has('user'))
                    <i class="fa fa-heart fs-18 ms-auto Pause-Redirect" onclick="ShowToaster('Error', 'Sign To Proceed')"></i>
                    @else
                    @if (Favourite::CheckFavourite(Session('user')->id, $car->id) == false)
                    <i class="fa fa-heart fs-18 ms-auto Pause-Redirect" id="heart_{{$car->id}}" onclick="FavouriteMethod('{{$car->id}}')"></i>
                    @else
                    <i class="fa fa-heart fs-18 ms-auto text-danger Pause-Redirect" id="heart_{{$car->id}}" onclick="FavouriteMethod('{{$car->id}}')"></i>
                    @endif
                    @endif
                </div>

                <button id="b_id{{ $car->id }}" @if ($car->showroom != null) value="{{ $car->showroom->user->business_phone_number ? $car->showroom->user->business_phone_number : $car->showroom->user->phone }}" onclick="change_text_to_phone({{ $car->id }})" @endif
                    class="btn btn-success w-100 Pause-Redirect" >
                    <i class="fa fa-phone-alt me-2"></i>
                    <span id="change_text{{ $car->id }}">Show Phone No.</span>
                </button>
            </div>
        </div>
        </a>
    </div>
    <script>

        $(document).ready(function(){
            $(".Pause-Redirect").data('click', false);
        $(".Pause-Redirect").on('click', function()
        {
        $(".Pause-Redirect").data('click', true);
        });
        });

        function change_text_to_phone($car_id) {
            var $phone_number = document.getElementById('b_id' + $car_id).value;
            var x = document.getElementById("change_text" + $car_id);
            if (x.innerHTML === "Show Phone No.") {
                x.innerHTML = $phone_number;
            } else {
                x.innerHTML = "Show Phone No.";
            }
        }

        function car_detail($car_id) {
            if($(".Pause-Redirect").data('click') == false)
            {
                window.location.href = "{{ 'car-detail/' }}" + $car_id;
            }
            $(".Pause-Redirect").data('click', false);
        }

        function OpenEditModal(id) {

            $("#edit-vtype-popup_" + id).modal("show");
        }

        function closemodel(id) {
            $("#edit-vtype-popup_" + id).modal("hide");
        }
    </script>
    @endforeach
</div>
