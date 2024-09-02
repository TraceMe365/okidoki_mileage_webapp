<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Import</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card mb-4   ">
        <div class="card-body">
        <div class="card-title"><h2>Upload Excel File For Mileage</h2></div>
        <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input class="form-control" type="file" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
        <div class="card-title"><h2>Upload Excel File For Distance</h2></div>
        <form action="{{ route('distance.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input class="form-control" type="file" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
        <div class="card-title"><h2>Upload Excel File For Distance (Multiple) </h2></div>
        <form action="{{ route('distance.import-multiple') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input class="form-control" type="file" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
        </form>
        </div>
    </div>
</div>

</body>
</html>