@extends('front.layout.main')
@section('content')
    <section class="section-space py-5 review-header">
        <div class="container-xl">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <h3 class="mb-0">Offers</h3>
            </div>
        </div>
    </section>



    <section class="section-space pb-3 offers-page">
        <div class="container-xl">


            <div class="tabs-box mb-5">
                <a class="active w-50" id="received_click" href="javascript:ViewChange('Received');">Recieved Offers</a>
                <a class="w-50" id="sent_click" href="javascript:ViewChange('Sent');">Sent Offers</a>
            </div>



            <div id="dynamic-content">
                @include('front.offers.offer-dynamic', [
                    'cars' => $cars,
                    'view_type' => 'Received',
                ])
            </div>


    </section>


@endsection

@section('scripts')
    <script>
        function ViewChange(type) {

            if(type == "Received")
            {
                $("#received_click").addClass('active');
                $("#sent_click").removeClass('active');

            }
            else
            {
                $("#received_click").removeClass('active');
                $("#sent_click").addClass('active');
            }
            PreLoader();
            $.ajax({
                url: '{{ route('webapi.offer.switch') }}',
                type: 'POST',
                data: {
                    view_type: type,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {

                        PreLoader("hide");
                        $("#dynamic-content").html(data.offer_html);

                    }
                },
                error: function(error) {
                    PreLoader("hide");
                    console.log(error);
                }
            });
        }
    </script>
@endsection
