@include('components.navbar')
@include('components.sidebar')
<style>

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
                            <h1 class="m-0 text-dark">Student's Attendance</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Student's Attendance</a></li>
                                <li class="breadcrumb-item active">Student & Attendance</li>
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
                                                        <form action="#" 
                                                            method="POST" class="inline">
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
                                                        <form action="#" 
                                                            method="POST" class="inline">
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
                                                        <form action="#" 
                                                            method="POST" class="inline">
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
<div id="addTrainerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Add Trainer</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <form id="trainerForm" method="POST" action="/trainers/store">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" class="w-full border rounded p-2 mt-1" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2 mt-1" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" class="w-full border rounded p-2 mt-1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Specialization</label>
                    <input type="text" name="specialization" class="w-full border rounded p-2 mt-1">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancelModal" 
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md">
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
    
    @push('scripts')
    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
    $(document).ready(function() {
        $('#trainerTable').DataTable({
            "pageLength": 5,
            "lengthMenu": [5, 10, 25, 50],
        });
    });
    </script>
    <script>
    $(document).ready(function () {
        $("#addTrainerBtn").on("click", function () {
            $("#addTrainerModal").removeClass("hidden");
        });
        $("#closeModal, #cancelModal").on("click", function () {
            $("#addTrainerModal").addClass("hidden");
        });
    });
    </script>

    <script>

        // Auto fetch School Name (example: from session/auth)
        const loggedInSchool = "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // Replace with Blade variable in Laravel
        document.getElementById("schoolName").value = loggedInSchool;
    </script>
</body>
@include('components.footer')
