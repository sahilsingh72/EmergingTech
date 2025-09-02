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
                            <h1 class="m-0 text-dark">Upload Feedback</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer</a></li>
                                <li class="breadcrumb-item active">Upload Feedback</li>
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

                                    <h2 class="text-2xl font-semibold text-center mb-6">Feedback Form</h2>

                                    <form class="space-y-5">
                                        <!-- Designation -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="Teacher"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>Teacher</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="HM"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>HM</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="Student"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>Student</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="DEO"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>DEO</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="BEO"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>BEO</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="Guest"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>Guest</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="OCAC Staff"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>OCAC Staff</span>
                                                </label>
                                                <label class="flex items-center space-x-2">
                                                    <input type="checkbox" name="designation[]" value="Other"
                                                        class="h-4 w-4 text-green-600 border-gray-300 rounded">
                                                    <span>Other</span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                            <input type="text" placeholder="Enter your name"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300">
                                        </div>

                                        <!-- Purpose -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                            <input type="text" placeholder="Enter purpose"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300">
                                        </div>

                                        <!-- Any Other Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Any Other
                                                Description</label>
                                            <textarea rows="3" placeholder="Write details here..."
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300"></textarea>
                                        </div>

                                        </br>
                                        <!-- Upload Instruction -->
                                        <p class="text-center text-gray-600 mb-4">Upload feedback Video of the
                                            event</p>
                                        <!-- Upload File (Video) -->
                                        <div id="dropZone"
                                            class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                            </svg>
                                            <p class="text-gray-500">Drag and drop video file, or click to select</p>
                                            <input type="file" id="fileUpload" class="hidden" accept="video/*"
                                                multiple>
                                            <div id="fileList" class="mt-3 text-sm text-gray-700 space-y-3"></div>
                                        </div>

                                        <!-- Date (Auto Detect) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Date &
                                                Time</label>
                                            <input type="text" id="dateTimeField" readonly
                                                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                        </div>

                                        <!-- School Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">School
                                                Name</label>
                                            <input type="text" id="schoolName" readonly
                                                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit"
                                            class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition">
                                            Submit Feedback
                                        </button>
                                    </form>

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
        const dropZone = document.getElementById("dropZone");
        const fileInput = document.getElementById("fileUpload");
        const fileList = document.getElementById("fileList");

        // Function to show selected files with small preview
        function showFiles(files) {
            fileList.innerHTML = "";
            Array.from(files).forEach(file => {
                const wrapper = document.createElement("div");
                wrapper.classList.add("border", "p-2", "rounded-md", "bg-gray-50", "text-center");

                const name = document.createElement("p");
                name.textContent = file.name;
                name.classList.add("text-xs", "truncate", "text-gray-600");

                const video = document.createElement("video");
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.classList.add("w-32", "h-20", "mx-auto", "rounded-md", "object-cover");

                wrapper.appendChild(video);
                wrapper.appendChild(name);
                fileList.appendChild(wrapper);
            });
        }

        // Click on box opens file dialog
        dropZone.addEventListener("click", () => fileInput.click());

        // Show selected files
        fileInput.addEventListener("change", (e) => {
            if (e.target.files.length > 0) {
                showFiles(e.target.files);
            }
        });

        // Drag & Drop functionality
        dropZone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZone.classList.add("border-green-500", "bg-green-50");
        });

        dropZone.addEventListener("dragleave", () => {
            dropZone.classList.remove("border-green-500", "bg-green-50");
        });

        dropZone.addEventListener("drop", (e) => {
            e.preventDefault();
            dropZone.classList.remove("border-green-500", "bg-green-50");
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                showFiles(e.dataTransfer.files);
            }
        });


        // Auto detect Date & Time
        document.getElementById("dateTimeField").value = new Date().toLocaleString();

        // Auto fetch School Name (example: from login session)
        // In real project, replace this with backend value (Laravel Blade, session, etc.)
        const loggedInSchool = "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // ← This should come from login
        document.getElementById("schoolName").value = loggedInSchool;
    </script>

</body>
@include('components.footer')
