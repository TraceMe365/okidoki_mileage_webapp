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
                    <div class="row">
                        <div class="col-4">
                            <input class="form-control" type="file" name="file">
                        </div>
                        <div class="col-4">
                            <a href="{{ route('download.distancem') }}" class="btn btn-success mb-2">Download Distance</a>
                        </div>
                    </div>
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
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif
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