@include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Student Attendance Record</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Student Attendance Record</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white p-8 rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Attendance Record</h2>

                                @if(session('info'))
                                    <div class="alert alert-info">{{ session('info') }}</div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>File Type</th>
                                            <th>File Name</th>
                                            <th>Uploaded At</th>
                                            <th>File</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($uploads as $upload)
                                         @if(in_array($upload->file_type, ['attendance_sheet', 'trainer_photo']))
                                            <tr>
                                                <td>{{ $upload->file_type }}</td>
                                                <td>
                                                    @foreach($upload->file_name as $name)
                                                        {{ $name }} <br>
                                                    @endforeach
                                                </td>
                                                <td>{{ $upload->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if($upload->onedrive_url)
                                                        @foreach($upload->onedrive_url as $url)
                                                            <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-success">Open</a><br>
                                                        @endforeach
                                                    @endif

                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary"
                                                            onclick="openEditModal({{ $upload->upload_id }}, '{{ $upload->file_type }}')">
                                                        Edit
                                                    </button>
                                                </td>
                                            </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="4">No uploads yet.</td>
                                            </tr>
                                            
                                        @endforelse
                                    </tbody>
                                </table>


                                <!-- Edit File Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Upload File</h5>
          
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
        </div>

        <div class="modal-body">
          <div class="mb-3" id="attendanceFileGroup" style="display:none;">
            <label>Replace Attendance File</label>
            <input type="file" name="attendance_file" class="form-control" accept="application/pdf,image/*">
          </div>

          <div class="mb-3" id="trainerImageGroup" style="display:none;">
            <label>Replace Trainer Image</label>
            <input type="file" name="trainer_image" class="form-control" accept="image/*" >
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update File</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>


                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openEditModal(id, fileType) {
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    // Show only the relevant file input
    if (fileType === 'attendance_sheet') {
        document.getElementById('attendanceFileGroup').style.display = 'block';
        document.getElementById('trainerImageGroup').style.display = 'none';
    } else if (fileType === 'trainer_photo') {
        document.getElementById('attendanceFileGroup').style.display = 'none';
        document.getElementById('trainerImageGroup').style.display = 'block';
    }

    // Set form action
    document.getElementById('editForm').action = `/attendance-list/${id}`;

    editModal.show();
}
</script>


<script>


</script>

</body>
@include('components.footer')