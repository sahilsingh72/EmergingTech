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
                            <h1 class="m-0 text-dark">Student List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Student List</a></li>
                                <li class="breadcrumb-item active">Students</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Student List</h2>
                                    <div class="mb-4 flex justify-end">
                                        <a href="{{route('single.addstudent')}}">
                                            <button id=""
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                <i class="fas fa-user-plus"></i>Add Student
                                            </button>
                                        </a>
                                    </div>
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
                                            <input type="text" id="searchInput" placeholder="Search..."
                                                class="border rounded p-2 w-64">
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
                                                        data-column="2">Roll Number</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="3">Class</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="4">Section</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="5">Gender</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="6">DOB</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="7">Father's Name</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="8">UDISE Code</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="9">School Name</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="10">Block</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="11">District</th>
                                                    <th class="border px-4 py-2 text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($students as $index => $student)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="border px-4 py-2 text-center">{{ $index + 1 }}
                                                        </td>
                                                        <td class="border px-4 py-2">{{ $student->stu_name }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_roll_number }}
                                                        </td>
                                                        <td class="border px-4 py-2">{{ $student->stu_class }}</td>
                                                        <td class="border px-4 py-2">
                                                            {{ strtoupper($student->stu_section) }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_gender }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_dob }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_fathername }}
                                                        </td>
                                                        <td class="border px-4 py-2">{{ $student->stu_scm_udise }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_schoolname }}
                                                        </td>
                                                        <td class="border px-4 py-2">{{ $student->stu_block }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_dist }}</td>

                                                        <td class="border px-4 py-2 text-center">
                                                            <!-- View -->
                                                            <a href="#"
                                                                class="text-blue-600 hover:text-blue-800 mx-1 btn-view"
                                                                data-id="{{ $student->stu_id }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <!-- Edit -->
                                                            <a href="#"
                                                                class="text-yellow-600 hover:text-yellow-800 mx-1 btn-edit"
                                                                data-id="{{ $student->stu_id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <!-- Delete (can also make AJAX) -->
                                                            <form
                                                                action="{{ route('student.delete', $student->stu_id) }}"
                                                                method="POST" class="inline-block"
                                                                onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 hover:text-red-800 mx-1">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="13" class="text-center py-4">No students found.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                    <!-- View Modal -->
                                    <div id="viewStudentModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                                        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
                                            <!-- Close button -->
                                            <button onclick="closeModal('viewStudentModal')"
                                                class="absolute top-3 right-3 text-gray-600 hover:text-gray-900">
                                                ✖
                                            </button>
                                            <h2 class="text-xl font-semibold mb-4">Student Details</h2>
                                            <div id="studentDetails" class="space-y-2 text-gray-700"></div>
                                        </div>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div id="editStudentModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                                        <div
                                            class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative overflow-y-auto max-h-[90vh]">
                                            <!-- Close button -->
                                            <button onclick="closeModal('editStudentModal')"
                                                class="absolute top-3 right-3 text-gray-600 hover:text-gray-900">
                                                ✖
                                            </button>
                                            <h2 class="text-xl font-semibold mb-4">Edit Student</h2>
                                            <form id="editStudentForm" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" id="editStuId" name="stu_id">
                                                <div>
                                                    <label class="block text-sm">Name</label>
                                                    <input type="text" id="editName" name="stu_name"
                                                        class="w-full border rounded p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm">Roll Number</label>
                                                    <input type="text" id="editRoll" name="stu_roll_number"
                                                        class="w-full border rounded p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium">Class</label>
                                                    <input type="text" id="editClass" name="stu_class"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">Section</label>
                                                    <input type="text" id="editSection" name="stu_section"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">Gender</label>
                                                    <select id="editGender" name="stu_gender"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">Date of Birth</label>
                                                    <input type="date" id="editDOB" name="stu_dob"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">Father's Name</label>
                                                    <input type="text" id="editFather" name="stu_fathername"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">School Name</label>
                                                    <input type="text" id="editSchool" name="stu_schoolname"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">UDISE Code</label>
                                                    <input type="text" id="editUDISE" name="stu_scm_udise"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">Block</label>
                                                    <input type="text" id="editBlock" name="stu_block"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">District</label>
                                                    <input type="text" id="editDist" name="stu_dist"
                                                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                                </div>
                                                <div class="text-right">
                                                    <button type="button" onclick="closeModal('editStudentModal')"
                                                        class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
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
    </div>
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
        function openModal(id) {
            $("#" + id).removeClass("hidden").addClass("flex");
        }

        function closeModal(id) {
            $("#" + id).removeClass("flex").addClass("hidden");
        }
    </script>
    <script>
        // View Student AJAX
        $(document).on("click", ".btn-view", function(e) {
            e.preventDefault();
            let stuId = $(this).data("id");

            $.get("{{ route('student.view', ':id') }}".replace(':id', stuId), function(data) {
                let details = `
            <p><strong>Name:</strong> ${data.stu_name}</p>
            <p><strong>Roll No:</strong> ${data.stu_roll_number}</p>
            <p><strong>Class:</strong> ${data.stu_class}</p>
            <p><strong>Section:</strong> ${data.stu_section}</p>
            <p><strong>Gender:</strong> ${data.stu_gender}</p>
            <p><strong>DOB:</strong> ${data.stu_dob}</p>
            <p><strong>Father:</strong> ${data.stu_fathername}</p>
            <p><strong>School:</strong> ${data.stu_schoolname}</p>
            <p><strong>UDISE:</strong> ${data.stu_scm_udise}</p>
            <p><strong>Block:</strong> ${data.stu_block}</p>
            <p><strong>District:</strong> ${data.stu_dist}</p>
        `;
                $("#studentDetails").html(details);
                openModal("viewStudentModal");
            });
        });
        // Edit Student AJAX - load student details into modal
        $(document).on("click", ".btn-edit", function(e) {
            e.preventDefault();
            let stuId = $(this).data("id");

            $.get("{{ route('student.edit', ':id') }}".replace(':id', stuId), function(data) {
                $("#editStuId").val(data.stu_id);
                $("#editName").val(data.stu_name);
                $("#editRoll").val(data.stu_roll_number);
                $("#editClass").val(data.stu_class);
                $("#editSection").val(data.stu_section);
                $("#editGender").val(data.stu_gender);
                $("#editDOB").val(data.stu_dob);
                $("#editFather").val(data.stu_fathername);
                $("#editSchool").val(data.stu_schoolname);
                $("#editUDISE").val(data.stu_scm_udise);
                $("#editBlock").val(data.stu_block);
                $("#editDist").val(data.stu_dist);
                openModal("editStudentModal");
            });
        });

        // Submit Edit Form (AJAX PUT request)
        $("#editStudentForm").submit(function(e) {
            e.preventDefault();
            let stuId = $("#editStuId").val();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('student.update', ':id') }}".replace(':id', stuId),
                method: "PUT",
                data: formData,
                success: function(res) {
                    alert(res.message);
                    closeModal("editStudentModal");
                    location.reload(); // refresh table
                },
                error: function(xhr) {
                    alert("Something went wrong!");
                }
            });
        });
        // Delete Student
        $(document).on("click", ".btn-delete", function(e) {
            e.preventDefault();
            let stuId = $(this).data("id");

            if (confirm("Are you sure you want to delete this student?")) {
                $.ajax({
                    url: "/students/" + stuId, // your delete route
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    }, // important for Laravel
                    success: function(res) {
                        alert("Student deleted successfully!");
                        $("a.btn-delete[data-id='" + stuId + "']").closest("tr").remove();
                    },
                    error: function(xhr) {
                        alert("Failed to delete student!");
                    }
                });
            }
        });
    </script>

    <script>
        // Auto fetch School Name (example: from session/auth)
        const loggedInSchool =
            "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // Replace with Blade variable in Laravel
        document.getElementById("schoolName").value = loggedInSchool;
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</body>
@include('components.footer')
