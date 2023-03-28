@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')
    <div class="inner-content videos-reviews-page">

        <form method="POST" action="{{route('admin.video.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="d-flex align-items-center">
            <h4 class="page-heading my-4">Videos & Reviews</h4>
            <div class="ms-auto">
                <div class="d-flex align-items-center">
                    <button class="btn btn-primary ms-2" type="submit"> Upload</button>
                </div>
            </div>
        </div>


        <div class="pending-ads-box mb-3">
            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="row">
                        <div class="col-xl-3 text-xl-end">
                            <label class="mt-10 white-space-nowrap">Select Model <sup class="required">*</sup></label>
                        </div>
                        <div class="col-xl-9">
                            <div class="postad-select">
                                <select class="form-control" id="models" name="model_id">
                                    <option value="" selected disabled>Select Model</option>
                                    @foreach ($models as $model)
                                        <option value="{{$model->id}}">{{$model->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="row">
                        <div class="col-xl-3 text-xl-end">
                            <label class="mt-10 white-space-nowrap">Select Version</label>
                        </div>
                        <div class="col-xl-9">
                            <div class="postad-select">
                                <select class="form-control" id="versions" name="version_id">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="row">
                        <div class="col-xl-3 text-xl-end">
                            <label class="mt-10 white-space-nowrap">Title <sup class="required">*</sup></label>
                        </div>
                        <div class="col-xl-9">
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="row">
                        <div class="col-xl-3 text-xl-end">
                            <label class="mt-10 white-space-nowrap">Youtube URL<sup class="required">*</sup></label>
                        </div>
                        <div class="col-xl-9">
                            <input type="text" class="form-control" name="url">
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="row">
                        <div class="col-xxl-3 col-xl-4 text-xl-end">
                            <label class="mt-3 white-space-nowrap">Thumbnail Image <sup class="required">*</sup></label>
                        </div>
                        <div class="col-xxl-9 col-xl-8">
                            <div class="d-flex">
                                <div class="uploaded-img me-3">
                                    <img src="{{URL::asset('admin-panel/images/img-icon.png')}}" id="preview_image">
                                </div>
                                <div class="uploaded-action">
                                    <label class="browsbtn btn btn-primary mb-2"> + Add Icon
                                        <input type="file" size="60" name="image" id="image">
                                    </label>
                                </div>
                            </div>

                            <!-- .. -->
                            <div class="uploaded-box mx-1 d-none">
                                <img src="{{URL::asset('admin-panel/images/car.svg')}}" class="w-100">
                                <a class="ubcross"><i class="fa fa-times"></i></a>
                            </div>
                            <!-- .. -->
                        </div>
                    </div>
                </div>


            </div>


        </div>

        </form>


    </div>
@endsection

@section('scripts')
<script>
   $(document).ready(function() {
        $("#models").change(function(){
            var model_id = $("#models").val();
            $.ajax({
            url: '{{ route('webapi.versions.all') }}',
            type: 'POST',
            data: {
            model_id: model_id,
            _token: '{{ csrf_token() }}'
            },
            success: function(data) {
            if (data.result == 1) {
            var html = '';
            html +=
            '<option value="" disabled selected>Select Version</option>';
            data.data.forEach(element => {
            html += '<option value="' + element.name + '">' + element
                .name + '</option>'
            });
            $("#versions").html('');
            $("#versions").html(html);

            } else {
            ShowToaster("Error", data.message);
            }
            },
            error: function(error) {
            console.log(error);
            }
            });
        });
   });
   $(document).ready(function (e) {
    $('#image').change(function(){
     let reader = new FileReader();
     reader.onload = (e) => {
       $('#preview_image').attr('src', e.target.result);
       $('#preview_image').attr('width', '100%');
     }
     reader.readAsDataURL(this.files[0]);
    });
 });
</script>
@endsection
