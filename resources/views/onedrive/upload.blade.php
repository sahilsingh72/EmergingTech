<!DOCTYPE html>
<html>
<head>
    <title>Upload to OneDrive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

    <h2>Upload File to OneDrive</h2>

    @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif

    <form action="{{ route('onedrive.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="upload" class="form-label">Choose file</label>
            <input type="file" name="upload" id="upload" class="form-control" required>
        </div>
        <button class="btn btn-primary">Upload</button>
    </form>

</body>
</html>
