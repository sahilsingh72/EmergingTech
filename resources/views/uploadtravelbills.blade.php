@include('components.navbar')
@include('components.sidebar')

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
                            <h1 class="m-0 text-dark">Travel Bill</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Finance & Bills</a></li>
                                <li class="breadcrumb-item active">Travel Bill</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Travel Bills</h2>


                                    <div class="flex items-end space-x-4 mt-2">
                                        <div class="w-1/2 mt-2">

                                            <label for="travel"
                                                class="block text-sm font-medium text-gray-700 mb-1 ">Place of
                                                Travelling (full address)</label>
                                            <input type="text" id="time_to" name="time_to"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm"
                                                placeholder="From">
                                        </div>
                                        <span class="mb-2">to</span>
                                        <div class="w-1/2 mt-2">


                                            <input type="text" id="time_to" name="time_to"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm"
                                                placeholder="To">
                                        </div>

                                    </div>
                                    <div class="flex items-end space-x-4 mt-2  mb-3">
                                        <!-- Date -->
                                        <div class="w-1/2 mt-2">
                                            <label for="training_date"
                                                class="block text-sm font-medium text-gray-700 mb-1">Date of
                                                Travelling</label>
                                            <input type="date" id="training_date" name="training_date"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                        </div>

                                        <!-- Time From - To -->
                                        <div class="w-1/2 flex items-end space-x-2">
                                            <div class="flex-1">
                                                <label for="time_from"
                                                    class="block text-sm font-medium text-gray-700 mb-1">Time of
                                                    Travelling</label>
                                                <input type="time" id="time_from" name="time_from"
                                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                            </div>


                                            {{-- <span class="mb-2">to</span>
                                            
                                            <div class="flex-1">
                                                <label for="time_to"
                                                class="block text-sm font-medium text-gray-700 mb-1 hidden">Time of
                                                Training To</label>
                                                <input type="time" id="time_to" name="time_to"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                            </div> --}}
                                        </div>

                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose of
                                            Travelling</label>
                                        <input type="text" id="travelName" placeholder="Enter purpose of travelling"
                                            class="w-full border border-gray-300 rounded-md p-2  focus:ring focus:ring-green-300 shadow-sm ">
                                    </div>
                                    <div>
                                        <!-- Upload Instruction -->
                                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload
                                            Complete
                                            Travel Bill (PDF)</label>
                                        <!-- Upload Box -->
                                        <input type="file" accept="application/pdf"
                                            class="mt-1 mb-3 block w-full text-gray-700 sm:text-sm">
                                    </div>

                                    <div>
                                        <!-- Upload Button -->
                                        <button
                                            class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">
                                            Upload
                                        </button>
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
        // Auto fetch Date
        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("training_date").value = today;
        });
        // Auto fetch School Name (example: from session/auth)
        // const loggedInSchool =
        // "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // Replace with Blade variable in Laravel
        // document.getElementById("schoolName").value = loggedInSchool;
    </script>
</body>
@include('components.footer')
