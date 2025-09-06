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
                            <h1 class="m-0 text-dark">Student Attendance</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Student Attendance</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Student Attendance</h2>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">School
                                            Name</label>
                                        <input type="text" id="schoolName" readonly
                                            class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                    </div>

                                    <div class="flex items-end space-x-4 mt-2">
                                        <!-- Date -->
                                        <div class="w-1/3 mt-2">
                                            <label for="training_date"
                                                class="block text-sm font-medium text-gray-700 mb-1">Date of
                                                Training</label>
                                            <input type="date" id="training_date" name="training_date"
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                        </div>

                                        <!-- Time From - To -->
                                        <div class="w-2/3 flex items-end space-x-2">
                                            <div class="flex-1">
                                                <label for="time_from"
                                                    class="block text-sm font-medium text-gray-700 mb-1">Time of
                                                    Training</label>
                                                <input type="time" id="time_from" name="time_from"
                                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                            </div>

                                            <span class="mb-2">to</span>

                                            <div class="flex-1">
                                                <label for="time_to"
                                                    class="block text-sm font-medium text-gray-700 mb-1 hidden">Time of
                                                    Training To</label>
                                                <input type="time" id="time_to" name="time_to"
                                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Upload Instruction -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Student's
                                        Attendance Sheet (PDF or Image)</label>
                                    <!-- Upload Box -->
                                    <div id="dropZone1"
                                        class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                        </svg>
                                        <p class="text-gray-500">Drag and drop PDFs or Images, or click to select</p>
                                        <input type="file" id="fileUpload1" class="hidden"
                                            accept="application/pdf,image/*" multiple>
                                        <!-- File Preview Section -->
                                        <div id="fileList1"
                                            class="mt-3 text-sm text-gray-700 space-y-2 flex flex-wrap gap-3"></div>
                                    </div>

                                    <!-- Upload Instruction -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Image of
                                        Trainer with Students</label>
                                    <!-- Upload Box -->
                                    <div id="dropZone2"
                                        class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-green-500 transition mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a1 1 0 001 1h14a1 1 0 001-1v-1M12 12V4m0 8l-3-3m3 3l3-3" />
                                        </svg>
                                        <p class="text-gray-500">Drag and drop Images, or click to select</p>
                                        <input type="file" id="fileUpload2" class="hidden" accept="image/*">
                                        <!-- File Preview Section -->
                                        <div id="fileList2"
                                            class="mt-3 text-sm text-gray-700 space-y-2 flex flex-wrap gap-3"></div>
                                    </div>
                                    <div id="previewModal"
                                        class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
                                        <div
                                            class="bg-white rounded-2xl shadow-lg max-w-4xl w-11/12 h-5/6 p-4 relative flex flex-col">
                                            <!-- Close Button -->
                                            <button id="closePreview"
                                                class="absolute top-2 right-3 text-red-600 text-2xl font-bold">&times;</button>
                                            <!-- Preview Area -->
                                            <div id="previewBody"
                                                class="flex-1 overflow-auto flex justify-center items-center"></div>
                                        </div>
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
        function setupFileUpload(dropZoneId, fileInputId, fileListId, allowMultiple = true) {
            const dropZone = document.getElementById(dropZoneId);
            const fileInput = document.getElementById(fileInputId);
            const fileList = document.getElementById(fileListId);

            // Modal elements
            const modal = document.getElementById("previewModal");
            const modalBody = document.getElementById("previewBody");
            const closePreview = document.getElementById("closePreview");

            let filesArray = []; // Store selected files

            function renderFiles() {
                fileList.innerHTML = "";
                filesArray.forEach((file, index) => {
                    const fileDiv = document.createElement("div");
                    fileDiv.className = "border rounded-lg p-2 w-32 text-center bg-gray-50 shadow-sm relative";

                    if (file.type.startsWith("image/")) {
                        const img = document.createElement("img");
                        img.src = URL.createObjectURL(file);
                        img.className = "w-24 h-24 object-cover mx-auto rounded";
                        fileDiv.appendChild(img);
                    } else if (file.type === "application/pdf") {
                        const pdfPreview = document.createElement("embed");
                        pdfPreview.src = URL.createObjectURL(file);
                        pdfPreview.type = "application/pdf";
                        pdfPreview.className = "w-24 h-24 mx-auto rounded border";
                        fileDiv.appendChild(pdfPreview);
                    } else {
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

                    // Action buttons
                    const actionDiv = document.createElement("div");
                    actionDiv.className = "flex justify-center gap-3 mt-2";

                    // View Button
                    const viewBtn = document.createElement("button");
                    viewBtn.innerHTML = "view";
                    viewBtn.className = "text-blue-600 hover:text-blue-800 text-lg";
                    viewBtn.title = "View";
                    viewBtn.onclick = () => {
                        modal.classList.remove("hidden");
                        modalBody.innerHTML = "";

                        if (file.type.startsWith("image/")) {
                            const img = document.createElement("img");
                            img.src = URL.createObjectURL(file);
                            img.className = "max-h-[80vh] max-w-full rounded";
                            modalBody.appendChild(img);
                        } else if (file.type === "application/pdf") {
                            const pdf = document.createElement("embed");
                            pdf.src = URL.createObjectURL(file);
                            pdf.type = "application/pdf";
                            pdf.className = "w-[80vw] h-[80vh] border rounded";
                            modalBody.appendChild(pdf);
                        } else {
                            const msg = document.createElement("p");
                            msg.textContent = "Preview not available for this file type.";
                            msg.className = "text-gray-700";
                            modalBody.appendChild(msg);
                        }
                    };
                    actionDiv.appendChild(viewBtn);

                    // Remove Button
                    const removeBtn = document.createElement("button");
                    removeBtn.innerHTML = "&times;";
                    removeBtn.className = "text-red-600 hover:text-red-800 text-lg";
                    removeBtn.title = "Remove";
                    removeBtn.onclick = () => {
                        filesArray.splice(index, 1);
                        updateFileInput();
                        renderFiles();
                    };
                    actionDiv.appendChild(removeBtn);

                    fileDiv.appendChild(actionDiv);
                    fileList.appendChild(fileDiv);
                });
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                filesArray.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            }

            function addFiles(newFiles) {
                if (!allowMultiple) filesArray = [];
                Array.from(newFiles).forEach(file => filesArray.push(file));
                updateFileInput();
                renderFiles();
            }

            // Click opens file dialog
            dropZone.addEventListener("click", () => fileInput.click());

            // File select
            fileInput.addEventListener("change", (e) => {
                if (e.target.files.length > 0) addFiles(e.target.files);
            });

            // Drag & Drop
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
                if (e.dataTransfer.files.length > 0) addFiles(e.dataTransfer.files);
            });

            // Close modal
            closePreview.addEventListener("click", () => modal.classList.add("hidden"));
            modal.addEventListener("click", (e) => {
                if (e.target === modal) modal.classList.add("hidden");
            });
        }

        // Init both uploaders
        setupFileUpload("dropZone1", "fileUpload1", "fileList1", true);
        setupFileUpload("dropZone2", "fileUpload2", "fileList2", true);
        // Auto fetch Date
        document.addEventListener("DOMContentLoaded", function () {
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
