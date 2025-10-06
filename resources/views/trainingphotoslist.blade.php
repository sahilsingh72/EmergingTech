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
                            <h1 class="m-0 text-dark">Training Photos</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Training Photos</a></li>
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
                                <h2 class="text-2xl font-semibold text-center mb-6">Training Photos</h2>

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
                                         @if(in_array($upload->file_type, ['training_photo']))
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


<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg max-h-[80vh] overflow-y-auto shadow-lg relative">
        <h2 class="text-xl font-semibold mb-4 ">Edit Training Photos</h2>
        
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <label class="block text-sm font-medium">Select Image to Delete</label>
            <div id="photoList" class="mb-4 space-y-2">
                
                <!-- Existing images will be loaded here -->
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Add New Photos</label>
                <input type="file" name="new_training_photo[]" multiple accept="image/*" class="mt-2 border p-2 w-full rounded">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 rounded">Cancel</button>
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
function openEditModal(id) {
    fetch(`/trainingphotos-list/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.file_name.forEach((name, index) => {
                html += `
                    <div class="flex items-center space-x-3 border p-2 rounded">
                        <img src="/preview-image?path=${encodeURIComponent(data.onedrive_path[index])}" class="w-16 h-16 rounded object-cover">

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="remove_files[]" value="${name}">
                            <span>${name}</span>
                        </label>
                    </div>
                `;
            });
            document.querySelector('#photoList').innerHTML = html;
            document.querySelector('#editForm').action = `/trainingphotos-list/${id}`;
            document.querySelector('#editModal').classList.remove('hidden');
        });
}

function closeModal() {
    document.querySelector('#editModal').classList.add('hidden');
}
</script>


<script>


</script>

</body>
@include('components.footer')