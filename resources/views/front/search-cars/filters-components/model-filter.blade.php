<div class="exheader d-flex align-items-center" data-bs-toggle="collapse" href="#expand5">
    <h3 class="m-0">MODEL</h3>
    <a class="ms-auto"><i class="fa fa-caret-down ms-auto"></i></a>
</div>
<div class="collapse exbody show" id="expand5">
    <div class="innerbody">
        @foreach ($models->slice(0, 5) as $model)
            <div class="d-flex align-items-center mb-2">
                <label class="custom-checkbox">
                    {{ $model->name }}
                    <input type="checkbox" name="filter_model"
                        id="filter_model_{{ $model->id }}" value="{{ $model->id }}" />
                    <span class="checkmark"></span>
                </label>
                <div class="salecounter ms-auto">
                    {{ car_model_count($model->id) }}
                </div>
            </div>
        @endforeach

        @if($models->count() > 5)
        <div class="mt-3" data-bs-toggle="modal" data-bs-target="#more-detail-popup-model">
            <a>More Models</a>
        </div>

        @include('front.search-cars.model-more-popup', [
            'models' => $models,
            'div_id' => 'more-detail-popup-brands',
        ])
        @endif
    </div>
</div>


<script>
    $(document).ready(function() {
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
    });
</script>
