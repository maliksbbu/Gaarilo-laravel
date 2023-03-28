@extends('front.layout.main')
@section('content')
    <section class="section-space py-5 review-header">
        <div class="container-xl">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <h3 class="mb-5">Video & Reviews</h3>

                <div class="row w-100">
                    <div class="col-sm-8 offset-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Honda Civic"
                                aria-label="Recipient's username" aria-describedby="basic-addon2" id="search_key">
                            <span class="input-group-text" id="basic-addon2" onclick="SearchVideo()"><i class="fa fa-search"></i></span>
                        </div>
                    </div>

                </div>



            </div>
        </div>
    </section>



    <section id="top-pics" class="section-space pb-3">
        <div class="container-xl">
            <div id="main-player" style="display: none">
                <h2 class="reviewheading mb-4" id="main-name"></h2>
                <div class="camber-selected mb-4">
                    <iframe id="main-iframe" class="w-100 h-100" src="">
                    </iframe>
                </div>
            </div>

            <h2 class="reviewheading mb-4">Search Results:</h2>

            <div class="row" id="main-list">
                @foreach ($videos as $key => $video)

                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="javascript:MakeMain('{{$key}}')">
                        <div class="detail-box">
                            <div class="db-top">
                                <img src="{{$video->image}}" class="w-100">
                            </div>
                            <div class="db-body">
                                <h5 class="m-0 mb-3 text-light-primary">
                                    {{$video->name}}
                                </h5>
                            </div>
                        </div>
                        </a>
                    </div>

                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    var videos = {!! json_encode($videos) !!};

    function MakeMain (key)
    {
        $("#main-name").html(videos[key].name);
        $("#main-iframe").attr('src', videos[key].url);
        $("#main-player").show();
    }

    function SearchVideo() {
        $("#main-player").hide();
        var keyWord = $("#search_key").val();

        PreLoader();
        $.ajax({
        url: '{{ route('webapi.search.videos') }}',
        type: 'POST',
        data: {
        key_word: keyWord,
        _token: '{{ csrf_token() }}'
        },
        success: function(data) {
        PreLoader("hide");
        if ($.isEmptyObject(data.error)) {

        videos = data.videos;
        $("#main-list").html(RenderMainList(data.videos));

        }
        },
        error: function(error) {
        PreLoader("hide");
        console.log(error);
        }
        });
    }

    function RenderMainList(data)
    {
        html = "";
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
            var name = element.name;
            html += '<div class="col-lg-3 col-md-6 mb-3">';
            html += "<a href=javascript:MakeMain('"+index+"')>";
            html += '<div class="detail-box"><div class="db-top">';
            html += '<img src="'+element.image+'" class="w-100">';
            html += '</div><div class="db-body"><h5 class="m-0 mb-3 text-light-primary">';
            html += element.name;
            html += '</h5></div></div></a></div>';
        }
        return html;
    }
</script>
@endsection
