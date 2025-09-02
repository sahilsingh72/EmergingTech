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
                            <h1 class="m-0 text-dark">Camp's Media Upload</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer</a></li>
                                <li class="breadcrumb-item active">Media Upload</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Media Upload</h2>

                                    <!-- Purpose/Event -->
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1">Purpose/Event</label>
                                        <input type="text"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300"
                                            placeholder="Enter purpose/Event">
                                    </div>

                                    <!-- Any Other Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Any Other
                                            Description</label>
                                        <textarea rows="3" placeholder="Write details here..."
                                            class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300"></textarea>
                                    </div>



                                    <!-- School Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">School
                                            Name</label>
                                        <input type="text" id="schoolName" readonly
                                            class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                    </div>
                                    <!-- Date (Auto Detect) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date &
                                            Time</label>
                                        <input type="text" id="dateTimeField" readonly
                                            class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                    </div>

                                    </br>
                                    <!-- Upload Instruction -->
                                    <p class="text-center text-gray-600 mb-4">Upload Image / PDF / Video of the
                                        event</p>

                                    <!-- Upload Box -->
                                    <div id="dropZone"
                                        class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                        </svg>
                                        <p class="text-gray-500">Drag and drop Image / PDF / Video, or click to select
                                        </p>
                                        <input type="file" id="fileUpload" class="hidden"
                                            accept="image/*,application/pdf,video/*" multiple>
                                        <!-- File Preview Section -->
                                        <div id="fileList"
                                            class="mt-3 text-sm text-gray-700 space-y-2 flex flex-wrap gap-3"></div>
                                    </div>

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
            </section>
        </div>
    </div>
    </div>
    <script>
        const dropZone = document.getElementById("dropZone");
        const fileInput = document.getElementById("fileUpload");
        const fileList = document.getElementById("fileList");

        // Show selected files with preview
        function showFiles(files) {
            fileList.innerHTML = "";
            Array.from(files).forEach(file => {
                const fileDiv = document.createElement("div");
                fileDiv.className = "border rounded-md p-2 w-32 text-center bg-gray-50 shadow-sm";

                if (file.type.startsWith("image/")) {
                    // Image preview
                    const img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.className = "w-24 h-24 object-cover mx-auto rounded";
                    fileDiv.appendChild(img);

                } else if (file.type === "application/pdf") {
                    // PDF preview
                    const pdfPreview = document.createElement("embed");
                    pdfPreview.src = URL.createObjectURL(file);
                    pdfPreview.type = "application/pdf";
                    pdfPreview.className = "w-24 h-24 mx-auto rounded border";
                    fileDiv.appendChild(pdfPreview);

                } else if (file.type.startsWith("video/")) {
                    // Video preview
                    const video = document.createElement("video");
                    video.src = URL.createObjectURL(file);
                    video.controls = true;
                    video.className = "w-24 h-24 mx-auto rounded object-cover";
                    fileDiv.appendChild(video);

                } else {
                    // Other file preview
                    const fileIcon = document.createElement("div");
                    fileIcon.innerHTML = "📎";
                    fileIcon.className = "text-4xl text-gray-500";
                    fileDiv.appendChild(fileIcon);
                }

                // File name
                const p = document.createElement("p");
                p.textContent = file.name;
                p.className = "truncate text-xs mt-1";
                fileDiv.appendChild(p);

                fileList.appendChild(fileDiv);
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
        const loggedInSchool =
        "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // ← This should come from login
        document.getElementById("schoolName").value = loggedInSchool;
    </script>
</body>
@include('components.footer')
