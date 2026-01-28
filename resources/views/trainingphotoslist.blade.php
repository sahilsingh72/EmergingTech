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
                    <div class="max-w-8xl mx-auto space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Training Photos</h2>

                                @if(session('info'))
                                    <div class="alert alert-info">{{ session('info') }}</div>
                                @endif
                                @php
                                    $roleId = Auth::user()->role_id;
                                @endphp
                                @if($roleId == 1 || $roleId == 2 || $roleId == 8)

                                    <div class="row mb-2">
                                        {{-- District --}}
                                        <div class="col-md-6">
                                            <label>District</label>
                                            <select id="filterDistrict" class="form-control">
                                                <option value="">-- Select District --</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->DSM_DSCD }}">
                                                        {{ $district->DSM_DSNM }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- School --}}
                                        <div class="col-md-6">
                                            <label>School</label>
                                            <select id="filterSchool" class="form-control">
                                                <option value="">-- Select School --</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex justify-between items-center mb-4">
                                    <!-- Rows per page -->
                                    <div>
                                        <label for="rowsPerPage" class="mr-2">Shows:</label>
                                        <select id="rowsPerPage" class="border rounded  pl-2 pr-5">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>

                                    <!-- Search -->
                                    <div>
                                        <input type="text" id="searchInput"
                                            placeholder="Search(School/Udise Code/District)"
                                            class="border rounded p-2 w-40 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                </div>
                                <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                    <table id="filterTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>School</th>
                                                {{-- <th>Uploaded At</th> --}}
                                                <th>File</th>
                                                @php
                                                    $roleId = Auth::user()->role_id;
                                                @endphp
                                                @if($roleId == 3 || $roleId == 6)
                                                    <th>Edit</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="trainingTableBody">
                                            @php $sno = 1; @endphp
                                            @forelse($uploads as $upload)
                                                @if(in_array($upload->file_type, ['training_photo']))
                                                    <tr>
                                                        <td>{{ $sno++ }}</td>
                                                        <td>
                                                            {{$upload->school->scm_name}} -
                                                            {{ $upload->school->scm_udise_code }},
                                                            {{ $upload->school->scm_dist }}
                                                        </td>
                                                        <td>
                                                            @if($upload->onedrive_path)
                                                                @foreach($upload->onedrive_path as $index => $path)
                                                                    <a href="{{ route('preview.files', [
                                                                            'path' => $path, 
                                                                            'filename' => $upload->school->scm_name . '_' . $upload->file_type
                                                                        ]) }}"
                                                                        target="_blank" class="btn btn-sm btn-success">
                                                                        Open
                                                                    </a>
                                                                    @if(isset($upload->file_name[$index]))
                                                                        {{ $upload->file_name[$index] }}
                                                                    @endif
                                                                    <br>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        @php
                                                            $roleId = Auth::user()->role_id;
                                                        @endphp
                                                        @if($roleId == 3 || $roleId == 6)
                                                            <td>
                                                                <button class="btn btn-sm btn-primary"
                                                                    onclick="openEditModal({{ $upload->upload_id }}, '{{ $upload->file_type }}')">
                                                                    Edit
                                                                </button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No records found.</td>
                                                </tr>

                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>

                                <!-- Edit Modal -->
                                <div id="editModal"
                                    class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
                                    <div
                                        class="bg-white rounded-lg p-6 w-full max-w-lg max-h-[80vh] overflow-y-auto shadow-lg relative">
                                        <h2 class="text-xl font-semibold mb-4 ">Edit Training Photos</h2>

                                        <form id="editForm" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <label class="block text-sm font-medium">Select Image to Delete</label>
                                            <div id="photoList" class="mb-4 space-y-2">

                                                <!-- Existing images will be loaded here -->
                                            </div>

                                            <div class="mb-2">
                                                <label class="block text-sm font-medium">Add New Photos</label>
                                                <input type="file" name="new_training_photo[]" multiple accept="image/*"
                                                    class="mt-2 border p-2 w-full rounded">
                                            </div>
                                            <div id="photoError" class="text-red-600 text-sm mb-2"></div>

                                            <div class="flex justify-end space-x-2">
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                                                <button type="button" onclick="closeModal()"
                                                    class="px-4 py-2 bg-gray-400 rounded">Cancel</button>
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
        $(document).ready(function () {
            let rowsPerPage = parseInt($("#rowsPerPage").val());
            let currentPage = 1;
            let sortDirection = {}; // keep track of each column's sorting state

            function renderTable() {
                let searchText = $("#searchInput").val().toLowerCase();
                let rows = $("#filterTable tbody tr");

                // Filter rows
                rows.each(function () {
                    let rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(searchText) > -1);
                });

                // Pagination
                let visibleRows = rows.filter(":visible");
                let totalRows = visibleRows.length;
                let totalPages = Math.ceil(totalRows / rowsPerPage);

                visibleRows.hide();
                let start = (currentPage - 1) * rowsPerPage;
                let end = start + rowsPerPage;
                visibleRows.slice(start, end).show();

                // Render pagination buttons
                let pagination = $("#pagination");
                pagination.empty();

                for (let i = 1; i <= totalPages; i++) {
                    pagination.append(
                        `<button class="px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-white'} page-btn">${i}</button>`
                    );
                }
            }

            // Change rows per page
            $("#rowsPerPage").on("change", function () {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1;
                renderTable();
            });

            // Search filter
            $("#searchInput").on("keyup", function () {
                currentPage = 1;
                renderTable();
            });

            // Pagination click
            $(document).on("click", ".page-btn", function () {
                currentPage = parseInt($(this).text());
                renderTable();
            });

            // 🔽 Sorting click
            $(document).on("click", ".sort", function () {
                let columnIndex = $(this).data("column");
                sortDirection[columnIndex] = !sortDirection[columnIndex]; // toggle asc/desc
                let asc = sortDirection[columnIndex];

                let rows = $("#filterTable tbody tr").get();

                rows.sort(function (a, b) {
                    let A = $(a).children("td").eq(columnIndex).text().toLowerCase();
                    let B = $(b).children("td").eq(columnIndex).text().toLowerCase();

                    // numeric check
                    if ($.isNumeric(A) && $.isNumeric(B)) {
                        return asc ? A - B : B - A;
                    } else {
                        return asc ? A.localeCompare(B) : B.localeCompare(A);
                    }
                });

                $.each(rows, function (index, row) {
                    $("#filterTable tbody").append(row);
                });

                currentPage = 1; // reset pagination after sort
                renderTable();
            });

            // Initial render
            renderTable();
        });
    </script>

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
        document.getElementById('editForm').addEventListener('submit', function (event) {

            let checkboxes = document.querySelectorAll('#photoList input[type="checkbox"]');
            let total = checkboxes.length;
            let selected = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) selected++;
            });

            let newFiles = document.querySelector('input[name="new_training_photo[]"]').files.length;

            let errorBox = document.getElementById('photoError');
            errorBox.innerHTML = ""; // Clear old message

            if (selected === total && newFiles === 0) {
                event.preventDefault();

                errorBox.innerHTML = "You are deleting all photos. Please upload at least one new photo.";

                return false;
            }
        });
    </script>
    <script>
$(document).on("change", "#filterDistrict", function () {
    let districtId = $(this).val();

    $("#filterSchool").html('<option value="">Loading...</option>');

    if (!districtId) {
        $("#filterSchool").html('<option value="">-- Select School --</option>');
        return;
    }

    $.ajax({
        url: "{{ route('schools.byDistrict') }}",
        method: "GET",
        data: { district_id: districtId },
        success: function (schools) {
            let options = '<option value="">-- Select School --</option>';
            schools.forEach(school => {
                options += `
                    <option value="${school.scm_id}">
                        ${school.scm_name} (${school.scm_udise_code})
                    </option>`;
            });
            $("#filterSchool").html(options);
        }
    });
});
</script>
<script>
$(document).on("change", "#filterSchool", function () {

    let schoolId = $(this).val();

    if (!schoolId) return;

    $.ajax({
        url: "{{ route('trainingvideos.list') }}",
        method: "GET",
        data: { school_id: schoolId },
        success: function (response) {
            let html = $(response).find("#trainingTableBody").html();
            $("#trainingTableBody").html(html);

            // reset pagination
            $("#pagination").empty();
        },
        error: function () {
            alert("Failed to load attendance records");
        }
    });
});
</script>

</body>
@include('components.footer')