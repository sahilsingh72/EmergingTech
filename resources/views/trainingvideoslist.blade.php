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
                            <h1 class="m-0 text-dark">Training Videos</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Training Videos</a></li>
                                <li class="breadcrumb-item active">Training Videos</li>
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
                                <h2 class="text-2xl font-semibold text-center mb-6">Training Videos</h2>

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
                                         @if(in_array($upload->file_type, ['training_video']))
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
                                                            onclick="openVideoEditModal({{ $upload->upload_id }}, '{{ $upload->file_type }}')">
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


<!-- Edit Modal -->
<div id="editVideoModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg max-h-[80vh] overflow-y-auto shadow-lg relative">
        <h2 class="text-xl font-semibold mb-4 ">Edit Training Videos</h2>
        
        <form id="editVideoForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <label class="block text-sm font-medium">Select Video to Delete</label>

            <div id="videoList" class="mb-4 space-y-2">
                <!-- Existing videos loaded dynamically -->
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Add New Videos</label>
                <input type="file" name="new_training_video[]" multiple accept="video/*" class="mt-2 border p-2 w-full rounded">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeVideoModal()" class="px-4 py-2 bg-gray-400 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            </div>
        </form>
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
function openVideoEditModal(id) {
    fetch(`/trainingvideos-list/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.file_name.forEach((name, index) => {
                html += `
                    <div class="flex items-center space-x-3 border p-2 rounded">
                        <video src="${data.onedrive_url[index]}" class="w-32 h-20 rounded" controls></video>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="remove_files[]" value="${name}">
                            <span>${name}</span>
                        </label>
                    </div>
                `;
            });
            document.querySelector('#videoList').innerHTML = html;
            document.querySelector('#editVideoForm').action = `/trainingvideos-list/${id}`;
            document.querySelector('#editVideoModal').classList.remove('hidden');
        });
}

function closeVideoModal() {
    document.querySelector('#editVideoModal').classList.add('hidden');
}
</script>


<script>


</script>

</body>
@include('components.footer')