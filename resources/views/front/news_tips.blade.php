@extends('front.layout.main')
@section('content')
    <section class="section-space py-5 review-header">
        <div class="container-xl">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <h3 class="mb-2">News & Tips</h3>
            </div>
        </div>
    </section>



    <section id="top-pics" class="section-space pb-3">
        <div class="container-xl">
            <h2 class="reviewheading mb-4">Latest Updates</h2>

            <div class="nt-box">

                @foreach ($news as $key => $new)
                    <div class="mb-3">
                        <div class="mb-1">
                            <a>
                                {{$new->title}}
                            </a>
                        </div>
                        <p class="mb-1">{{date('F d, Y', strtotime($new->created_at))}}</p>
                        <p>{{$new->text}} </p>
                    </div>

                    @if($key != ($news->count() - 1))
                    <hr>
                    @endif
                @endforeach




            </div>


        </div>
    </section>






    <a href="#" id="scroll" style="display: none;" title="Back to Top"><span></span></a>
@endsection
