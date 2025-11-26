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
                    <div class="max-w-8xl mx-auto space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Attendance Record</h2>

                                @if(session('info'))
                                    <div class="alert alert-info">{{ session('info') }}</div>
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
                                            class="border rounded p-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                </div>
                                <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                    <table id="filterTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>School</th>
                                                <th>File</th>
                                                @php
                                                    $roleId = Auth::user()->role_id;
                                                @endphp
                                                @if($roleId == 3 || $roleId == 6)
                                                    <th>Edit</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $sno = 1; @endphp
                                            @forelse($uploads as $upload)
                                                @if(in_array($upload->file_type, ['attendance_sheet', 'trainer_photo']))
                                                    <tr>
                                                        <td>{{ $sno++ }}</td>
                                                        <td>{{$upload->school->scm_name}} -
                                                            {{ $upload->school->scm_udise_code }},
                                                            {{ $upload->school->scm_dist }}</td>
                                                        <td>
                                                            @if($upload->onedrive_path)
                                                                @foreach($upload->onedrive_path as $index => $path)
                                                                    <button type="button" class="btn btn-sm btn-success"
                                                                        onclick="openFileModal('{{ urlencode($path) }}', '{{ $upload->file_type }}')">
                                                                        Open
                                                                    </button>
                                                                    @if(isset($upload->file_name[$index]))
                                                                        {{ $upload->file_name[$index] }} ({{ ucwords(str_replace('_', ' ', $upload->file_type)) }})
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
                                                    <td colspan="4">No uploads yet.</td>
                                                </tr>

                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>

                                <!-- File Preview Modal -->
                                <div id="fileModal"
                                    class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50 overflow-y-auto">
                                    <div
                                        class="relative bg-white rounded-lg shadow-lg p-4  h-auto w-auto flex flex-col">
                                        <button onclick="closeFileModal()"
                                            class="absolute top-2 right-3 text-gray-600 hover:text-gray-900 text-3xl">&times;</button>

                                        <!-- PDF Viewer -->
                                        <iframe id="pdfViewer" class="hidden w-full flex-1 rounded"
                                            frameborder="0"></iframe>

                                        <!-- Image Viewer -->
                                        <img id="imageViewer"
                                            class="hidden max-h-[80vh] h-[20rem] mx-auto rounded object-contain"
                                            alt="Preview">
                                    </div>
                                </div>

                                <!-- Edit File Modal -->
                                <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="editForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Upload File</h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close">&times;</button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3" id="attendanceFileGroup" style="display:none;">
                                                        <label>Replace Attendance File</label>
                                                        <input type="file" name="attendance_file" class="form-control"
                                                            accept="application/pdf,image/*">
                                                    </div>

                                                    <div class="mb-3" id="trainerImageGroup" style="display:none;">
                                                        <label>Replace Trainer Image</label>
                                                        <input type="file" name="trainer_image" class="form-control"
                                                            accept="image/*">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Update File</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
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
        function openFileModal(path, fileType) {
            const modal = document.getElementById('fileModal');
            const pdfViewer = document.getElementById('pdfViewer');
            const imageViewer = document.getElementById('imageViewer');

            // Reset both viewers
            pdfViewer.classList.add('hidden');
            imageViewer.classList.add('hidden');
            pdfViewer.src = '';
            imageViewer.src = '';

            // Set the appropriate source
            const previewUrl = `/preview-file?path=${path}`;

            if (fileType === 'attendance_sheet') {
                // Detect whether it's a PDF or image based on extension
                if (path.endsWith('.pdf')) {
                    pdfViewer.src = previewUrl;
                    pdfViewer.classList.remove('hidden');
                } else {
                    imageViewer.src = previewUrl;
                    imageViewer.classList.remove('hidden');
                }
            } else if (fileType === 'trainer_photo') {
                imageViewer.src = previewUrl;
                imageViewer.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeFileModal() {
            const modal = document.getElementById('fileModal');
            const pdfViewer = document.getElementById('pdfViewer');
            const imageViewer = document.getElementById('imageViewer');

            pdfViewer.src = '';
            imageViewer.src = '';
            modal.classList.add('hidden');
        }

        // Optional: close modal by clicking background
        document.getElementById('fileModal').addEventListener('click', (e) => {
            if (e.target.id === 'fileModal') closeFileModal();
        });
    </script>

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


</body>
@include('components.footer')