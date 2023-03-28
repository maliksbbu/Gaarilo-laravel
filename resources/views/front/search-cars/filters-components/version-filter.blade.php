<div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand6">
    <h3 class="m-0">VERSION</h3>
    <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
</div>
<div class="collapse exbody show" id="expand6">
    <div class="innerbody">
        @foreach($versions->slice(0, 5) as $version)
        <div class="d-flex align-items-center mb-2">
            <label class="custom-checkbox">
                {{$version->name}}
                <input type="checkbox" name="filter_version"
                    id="filter_version_{{$version->id}}" value="{{$version->id}}" />
                <span class="checkmark"></span>
            </label>
            <div class="salecounter ms-auto">
                {{car_version_count($version->id)}}
            </div>
        </div>
        @endforeach

        @if($versions->count() > 5)
        <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup-version">
            <a>More Versions</a>
        </div>


        @include('front.search-cars.version-more-popup', [
        'versions' => $versions,
        'div_id' => 'more-detail-popup-version'
        ])
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
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
    });
</script>
