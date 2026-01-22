@include('components.navbar')
@include('components.sidebar')
<style>
    .sort::after {
        content: " ⇅";
        font-size: 0.7rem;
        color: gray;
    }

    #studentTable {
        table-layout: auto;
        /* allow natural sizing */
        width: 100%;
        /* still stretch full table */
    }

    #studentTable th,
    #studentTable td {
        white-space: nowrap;
        /* prevent text wrapping */
    }


</style>
<body class="hold-transition sidebar-mini layout-fixed">

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Student Feedback</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Feedback</a></li>
                                <li class="breadcrumb-item active">Student Feedback</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content relative">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-8xl mx-auto space-y-6">
                            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">

                                    <h2 class="text-2xl font-semibold text-center mb-6">Student Feedback</h2>
                                    
                                    <!-- Flash Messages -->
                                    @if (session('success'))
                                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- Student Table -->
                                    <div class="flex justify-between items-center mb-2">
                                        <!-- Rows per page -->
                                        <div>
                                            <label for="rowsPerPage" class="mr-2">Shows:</label>
                                            <select id="rowsPerPage" class="border rounded  pl-2 pr-5">
                                                <option value="5">5</option>
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div>
                                            <input type="text" id="searchInput" placeholder="Search..."
                                                class="border rounded p-2 w-40">
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-2">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                                            <label for="filterSchool" class="font-semibold text-gray-700 whitespace-nowrap">Select School:</label>
                                            <select id="filterSchool" class="border rounded p-2 w-full">
                                                <option value="">-- Select School --</option>
                                                @foreach ($schools as $school)
                                                    <option value="{{ $school->scm_id }}" 
                                                        {{ isset($schoolId) && $schoolId == $school->scm_id ? 'selected' : '' }}>
                                                        {{ $school->scm_name }} ({{ $school->scm_udise_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <a href="{{ asset('feedbackform/training_camp_feedback_form_image.pdf') }}" 
                                            class="btn btn-success w-full sm:w-40 text-center" target="_blank"><i class="fas fa-download"></i> Feedback Form</a>
                                        </div>
                                    </div>
                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        <table id="studentTable" class="w-full border-collapse">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="0">S.No</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="1">Student Name</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="7">Father's Name</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="6">DOB</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="5">Gender</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="2">Roll Number</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="3">Class</th>
                                                    {{-- <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="10">Address</th> --}}
                                                    {{-- <th class="border px-4 py-2 text-center">Upload Feedback</th> --}}
                                                    @php
                                                        $roleId = Auth::user()->role_id;
                                                    @endphp
                                                        <th class="border px-4 py-2 text-center">Feedback Entry</th>
                                                    {{-- @if($roleId == 3 || $roleId == 6)
                                                        <th class="border px-4 py-2 text-center">Actions</th>
                                                    @endif --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($students as $index => $student)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_name }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_fathername }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_dob }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_gender }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_roll_number }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_class }}</td>
                                                        {{-- <td class="border px-4 py-2">{{ $student->stu_address}}</td> --}}
                                                        {{-- <td class="border px-4 py-2 text-center">
                                                            @if($student->feedback_file_url)
                                                                <a href="{{ route('student.feedback.preview', ['path' => $student->feedback_file_path]) }}" target="_blank"
                                                                class="text-blue-600 hover:text-blue-800 mx-1" title="View PDF">
                                                                    <i class="fas fa-file-pdf"></i>
                                                                </a>
                                                                <span class="text-green-600 ml-2" title="Uploaded successfully">
                                                                    <i class="fas fa-check-circle"></i>
                                                                </span>
                                                            @else
                                                                @php
                                                                    $roleId = Auth::user()->role_id;
                                                                @endphp
                                                                @if($roleId == 3 || $roleId == 6)
                                                                    <button class="text-green-600 hover:text-green-800 mx-1 btn-upload"
                                                                        data-id="{{ $student->stu_id }}" data-name="{{ $student->stu_name }}">
                                                                        <i class="fas fa-upload"></i>
                                                                    </button>
                                                                @endif
                                                                <span class="text-red-600 ml-2" title="Upload failed">
                                                                    <i class="fas fa-times-circle"></i>
                                                                </span>
                                                            @endif
                                                        </td> --}}
                                                        <td class="border px-4 py-2 text-center">
                                                            @php
                                                                $roleId = Auth::user()->role_id;
                                                            @endphp
                                                            @if($roleId == 2 || $roleId == 3 || $roleId == 6)
                                                                <a href="{{ route('student.feedback.entryPage', $student->stu_id) }}"
                                                                    class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-pen"></i> Feedback Entry
                                                                </a>
                                                            @endif
                                                            @if($student->has_feedback_entry)
                                                            <span class="text-green-600 ml-2" title="Feedback completed">
                                                                <i class="fas fa-check-circle"></i>
                                                            </span>
                                                            @else
                                                            <span class="text-red-600 ml-2" title="Feedback pending">
                                                                <i class="fas fa-times-circle"></i>
                                                            </span>
                                                            @endif
                                                        </td>
                                                        {{-- @php
                                                            $roleId = Auth::user()->role_id;
                                                        @endphp
                                                        @if($roleId == 3 || $roleId == 6)
                                                            <td class="border px-4 py-2 text-center">
                                                                <button class="btn btn-sm btn-warning edit-feedback-btn"
                                                                        data-stu-id="{{ $student->stu_id }}"
                                                                        data-file-name="{{ $student->feedback_file_name }}"
                                                                        data-file-path="{{ $student->feedback_file_path }}">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                            </td>
                                                        @endif --}}
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="13" class="text-center py-4">Please select school to view student
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                    <!-- Upload Modal -->
                                    <div id="uploadModal"
                                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
                                            <h3 class="text-lg font-semibold mb-4">Upload Feedback</h3>

                                            <form id="uploadForm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" id="stu_id" name="stu_id" value="">
                                                
                                                <div class="mb-4">
                                                    <label class="block font-medium mb-1">Select PDF File</label>
                                                    <input type="file" name="written_feedback" id="written_feedback"
                                                        accept="application/pdf" class="border w-full p-2 rounded" required>
                                                    <p class="text-xs text-gray-500 mt-1">Only PDF (max 2MB)</p>
                                                </div>

                                                <div id="uploadProgress" class="hidden mb-2 text-sm text-blue-600">Uploading...</div>

                                                <div class="flex justify-end space-x-3">
                                                    <button type="button" id="cancelUpload"
                                                        class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">Upload</button>
                                                </div>
                                            </form>

                                            <button id="closeModal" class="absolute top-2 right-3 text-gray-600 text-lg">&times;</button>
                                        </div>
                                    </div>

                                    {{-- Edit --}}
                                    <div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="editFeedbackModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered justify-center z-50">
                                            <form id="editFeedbackForm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="editFeedbackModalLabel">Edit Feedback</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" id="edit_stu_id" name="stu_id">

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Select New Feedback File:</label>
                                                            <input type="file" class="form-control" name="written_feedback" accept="application/pdf" required>
                                                        </div>

                                                        <div id="editUploadProgress" class="text-center text-muted small"></div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Update</button>
                                                    </div>
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
    
    <script>
        $(document).ready(function() {
            $("#filterSchool").on("change", function() {
                const schoolId = $(this).val();

                $.ajax({
                    url: "{{ route('student.feedback') }}", // your route name
                    method: "GET",
                    data: { school_id: schoolId },
                    success: function(response) {
                        const html = $(response).find("#studentTable tbody").html();
                        $("#studentTable tbody").html(html);
                    },
                    error: function() {
                        alert("Failed to fetch students.");
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($("#rowsPerPage").val());
            let currentPage = 1;
            let sortDirection = {}; // keep track of each column's sorting state

            function renderTable() {
                let searchText = $("#searchInput").val().toLowerCase();
                let rows = $("#studentTable tbody tr");

                // Filter rows
                rows.each(function() {
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
            $("#rowsPerPage").on("change", function() {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1;
                renderTable();
            });

            // Search filter
            $("#searchInput").on("keyup", function() {
                currentPage = 1;
                renderTable();
            });

            // Pagination click
            $(document).on("click", ".page-btn", function() {
                currentPage = parseInt($(this).text());
                renderTable();
            });

            // 🔽 Sorting click
            $(document).on("click", ".sort", function() {
                let columnIndex = $(this).data("column");
                sortDirection[columnIndex] = !sortDirection[columnIndex]; // toggle asc/desc
                let asc = sortDirection[columnIndex];

                let rows = $("#studentTable tbody tr").get();

                rows.sort(function(a, b) {
                    let A = $(a).children("td").eq(columnIndex).text().toLowerCase();
                    let B = $(b).children("td").eq(columnIndex).text().toLowerCase();

                    // numeric check
                    if ($.isNumeric(A) && $.isNumeric(B)) {
                        return asc ? A - B : B - A;
                    } else {
                        return asc ? A.localeCompare(B) : B.localeCompare(A);
                    }
                });

                $.each(rows, function(index, row) {
                    $("#studentTable tbody").append(row);
                });

                currentPage = 1; // reset pagination after sort
                renderTable();
            });

            // Initial render
            renderTable();
        });
    </script>

<script>
    $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function () {
    // Open modal
    $(document).on("click", ".btn-upload", function () {
      const stuId = $(this).data("id");
      const stuName = $(this).data("name") || '';
      $("#stu_id").val(stuId);
      // optionally show student name in modal title
      $("#uploadModal h3").text('Upload Feedback — ' + stuName);
      $("#uploadModal").removeClass("hidden");
    });

    // Close modal
    $("#closeModal, #cancelUpload").on("click", function () {
      $("#uploadModal").addClass("hidden");
      $("#uploadForm")[0].reset();
      $("#uploadProgress").addClass("hidden").text('');
      $("#uploadModal h3").text('Upload Feedback');
    });

    // AJAX upload
    $("#uploadForm").on("submit", function (e) {
      e.preventDefault();

      let formData = new FormData(this);
      $("#uploadProgress").removeClass("hidden").text("Uploading...");

      $.ajax({
        url: "{{ route('student.feedback.upload') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          // nicer success message
          $("#uploadProgress").text("Upload successful");

          // update the table row for this student without reload
          const stuId = $("#stu_id").val();
            if (response.url) {
                // find the row containing the upload button for this student
                const $row = $(`button.btn-upload[data-id='${stuId}']`).closest('tr');

                // update the "Upload Feedback" cell (index 10, adjust if needed)
                const $uploadCell = $row.find('td').eq(10);

                // replace upload button + icons with just the PDF icon
                $uploadCell.html(`
                <a href="${response.url}" target="_blank" class="text-blue-600 hover:text-blue-800 mx-1" title="${response.file_name}">
                    <i class="fas fa-file-pdf"></i>
                </a>
                <span class="text-green-600 ml-2" title="Uploaded successfully">
                    <i class="fas fa-check-circle"></i>
                </span>
                `);
            }

          setTimeout(() => {
            $("#uploadModal").addClass("hidden");
            $("#uploadProgress").addClass("hidden").text('');
            $("#uploadForm")[0].reset();
          }, 900);
        },
        error: function (xhr) {
          // show the server error if there is one
          let msg = "Upload failed";
          if (xhr.responseJSON && xhr.responseJSON.error) {
            msg = xhr.responseJSON.error;
          } else if (xhr.responseText) {
            try {
              const j = JSON.parse(xhr.responseText);
              if (j.error) msg = j.error;
            } catch (e) {
              // not JSON
              msg = xhr.responseText;
            }
          }
          $("#uploadProgress").removeClass("hidden").text("❌ " + msg);
          console.error("Upload error:", xhr);
        },
      });
    });
  });
</script>


<script>
$(document).ready(function() {
    // Open modal on click
    $(document).on('click', '.edit-feedback-btn', function() {
        $('#edit_stu_id').val($(this).data('stu-id'));
        $('#editUploadProgress').text('');
        $('#editFeedbackForm')[0].reset();
        $('#editFeedbackModal').modal('show');
    });

    // Submit form via AJAX
    $('#editFeedbackForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $('#editUploadProgress').text('Uploading...');

        $.ajax({
            url: "{{ route('student.feedback.update') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $('#editUploadProgress').text('Feedback Update successfully!');
                    setTimeout(() => {
                        $('#editFeedbackModal').modal('hide');
                        location.reload();
                    }, 1000);
                } else {
                    $('#editUploadProgress').text('❌ ' + response.error);
                }
            },
            error: function() {
                $('#editUploadProgress').text('❌ Upload failed. Please try again.');
            }
        });
    });
});


</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
@include('components.footer')