@include('components.navbar')
@include('components.sidebar')
<style>
        .sort::after {
        content: " ⇅";
        font-size: 0.7rem;
        color: gray;
    }

    #trainerTable {
        table-layout: auto;
        /* allow natural sizing */
        width: 100%;
        /* still stretch full table */
    }

    #trainerTable th,
    #trainerTable td {
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
                            <h1 class="m-0 text-dark">Trainer List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer List</a></li>
                                <li class="breadcrumb-item active">Trainer</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6"></i>Trainer list</h2>
                                    <div class="mb-4">
                                        <button id="addTrainerBtn"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                            <i class="fas fa-user-plus"></i> Add Trainer
                                        </button>
                                    </div>
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
                           
                                    <!-- Trainer Table -->
                                    <div class="bg-white shadow rounded-lg p-4">
                                        <table id="trainerTable" class="w-full border-collapse">
                                            <thead>
                                                <tr class="bg-gray-100 text-left">
                                                    <th class="p-2 border">SNO</th>
                                                    <th class="p-2 border">Name</th>
                                                    <th class="p-2 border">Email</th>
                                                    <th class="p-2 border">Phone</th>
                                                    <th class="p-2 border">Specialization</th>
                                                    <th class="p-2 border text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-2 border">1</td>
                                                    <td class="p-2 border">Sambit Sir</td>
                                                    <td class="p-2 border">sahbit@okcl.org</td>
                                                    <td class="p-2 border">8789-----</td>
                                                    <td class="p-2 border">AI</td>
                                                    <td class="p-2 border text-center">
                                                        <a href="#" class="text-blue-500 mx-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" class="text-green-500 mx-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="#" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 mx-1"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 border">2</td>
                                                    <td class="p-2 border">Sahil Singh</td>
                                                    <td class="p-2 border">sahils@okcl.org</td>
                                                    <td class="p-2 border">8789293571</td>
                                                    <td class="p-2 border">Cybersecurity</td>
                                                    <td class="p-2 border text-center">
                                                        <a href="#" class="text-blue-500 mx-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" class="text-green-500 mx-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="#" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 mx-1"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-2 border">3</td>
                                                    <td class="p-2 border">Kumar</td>
                                                    <td class="p-2 border">kumars@okcl.org</td>
                                                    <td class="p-2 border">878-----</td>
                                                    <td class="p-2 border">Cybersecurity</td>
                                                    <td class="p-2 border text-center">
                                                        <a href="#" class="text-blue-500 mx-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" class="text-green-500 mx-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="#" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 mx-1"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Add Trainer Modal -->
                                    <div id="addTrainerModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div
                                            class="bg-white rounded-lg shadow-lg  max-w-5xl p-6 max-h-[90vh] overflow-y-auto">
                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Add Trainer</h3>
                                                <button id="closeModal"
                                                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                            </div>

                                            <!-- Form -->
                                            <form id="trainerForm" method="POST" action="/trainers/store"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-8">
                                                    <!-- Left Column: Trainer Info -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Name</label>
                                                            <input type="text" name="name"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Email</label>
                                                            <input type="email" name="email"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                accept=".pdf,.doc,.docx"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Educational
                                                                Qualification Certificates</label>
                                                            <input type="file" name="education_certificates[]"
                                                                multiple accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>

                                                    </div>

                                                    <!-- Right Column: File Uploads -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Phone</label>
                                                            <input type="text" name="phone"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Specialization</label>
                                                            <input type="text" name="specialization"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>

                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Experience
                                                                Certificate</label>
                                                            <input type="file" name="experience_certificate"
                                                                accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Photo</label>
                                                            <input type="file" name="photo"
                                                                accept=".jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" id="cancelModal"
                                                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-md">
                                                        Save Trainer
                                                    </button>
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
                let rows = $("#trainerTable tbody tr");

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

                let rows = $("#trainerTable tbody tr").get();

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
                    $("#trainerTable tbody").append(row);
                });

                currentPage = 1; // reset pagination after sort
                renderTable();
            });

            // Initial render
            renderTable();
        });
    </script>
        <script>
            $(document).ready(function() {
                $("#addTrainerBtn").on("click", function() {
                    $("#addTrainerModal").removeClass("hidden");
                });
                $("#closeModal, #cancelModal").on("click", function() {
                    $("#addTrainerModal").addClass("hidden");
                });
            });
        </script>

        <script>
            // Auto fetch Date
            document.addEventListener("DOMContentLoaded", function() {
                let today = new Date().toISOString().split('T')[0];
                document.getElementById("training_date").value = today;
            });
            // Auto fetch School Name (example: from session/auth)
            const loggedInSchool =
                "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // Replace with Blade variable in Laravel
            document.getElementById("schoolName").value = loggedInSchool;
        </script>
    </body>
    @include('components.footer')
