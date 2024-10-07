@extends('layout.home')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Mileage
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Get distance and time for multiple locations <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="card mt-4">
        <div class="card-body">
            <div class="card-title"><h2>Upload Excel File For Distance (Multiple) </h2></div>
                <form action="{{ route('distance.import-multiple') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input class="form-control" type="file" name="file">
                    </div>
                    <div class="row">
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-danger" onclick="deleteData()" type="button">
                                Clear
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<script>
    function deleteData(){
        $.ajax({
            url: "{{ route('clear.distancem') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                toastr.success('Cleared past records');
            },
            error: function(xhr) {
                console.log(xhr)
            }
        });
    }
</script>
@endsection