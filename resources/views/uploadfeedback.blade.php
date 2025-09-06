{{-- @include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Video Feedback</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer</a></li>
                                <li class="breadcrumb-item active">Video Feedback</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">

                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Video Feedback</h2>

                                    <form class="space-y-5" id="feedbackForm">
                                        <!-- School Name -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">School
                                                Name</label>
                                            <input type="text" id="schoolName" readonly
                                                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                        </div>
<!-- Date & Time -->
            <div class="flex items-end space-x-4 mt-3">
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Training</label>
                    <input type="date" name="training_date[]"
                        class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                </div>
                <div class="w-2/3 flex items-end space-x-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                        <input type="time" name="time_from[]"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                    </div>
                    <span class="mb-2">to</span>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                        <input type="time" name="time_to[]"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                    </div>
                </div>
            </div>
                                        <!-- Dynamic Upload Blocks -->
                                        <div id="designationUploads" class="space-y-6"></div>

                                        <!-- Add Designation -->
                                        <button type="button" id="addDesignationBlock"
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                                            + Add Designation Upload
                                        </button>

                                        <!-- Submit Button -->
                                        <button type="submit"
                                            class="w-full bg-green-500 text-white py-2 rounded-md text-lg font-medium hover:bg-green-600 transition mt-6">
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

    <!-- Template for designation block -->
    <template id="designationBlockTemplate">
        <div class="p-4 border rounded-lg bg-gray-50 relative">
            <button type="button"
                class="absolute top-2 right-2 text-red-600 font-bold removeBlock">✕</button>

            <!-- Designation -->
            <label class="block font-medium mb-1">Designation</label>
            <select name="designation[]" class="w-full border rounded-md p-2 focus:ring focus:ring-green-300">
                <option value="">-- Select Designation --</option>
                <option value="Teacher">Teacher</option>
                <option value="HM">HM</option>
                <option value="Student">Student</option>
                <option value="DEO">DEO</option>
                <option value="BEO">BEO</option>
                <option value="Guest">Guest</option>
                <option value="OCAC Staff">OCAC Staff</option>
                <option value="Other">Other</option>
            </select>

            

            <!-- Upload Videos -->
            <label class="block text-sm font-medium text-gray-700 mt-3">Upload Videos</label>
            <input type="file" name="videos[0][]" accept="video/*" multiple
                class="videoUpload w-full border border-gray-300 rounded-md p-2">

            <!-- Preview -->
            <div class="videoList mt-2 flex flex-wrap gap-3"></div>

            <!-- Description -->
            <label class="block text-sm font-medium text-gray-700 mt-3">Description</label>
            <textarea name="description[]" rows="2" class="w-full border rounded-md p-2" placeholder="type here...."></textarea>
        </div>
    </template>

    <!-- Modal for video preview -->
    <div id="videoModal" class="fixed inset-0 bg-black/70 hidden z-[9999]">
        <button id="closeVideoModal" type="button"
            class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full shadow z-[10000]">X</button>
        <div class="w-full h-full flex items-center justify-center p-4">
            <video id="modalVideo" controls class="max-w-4xl w-full max-h-[90vh] rounded shadow-lg"></video>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const addBtn = document.getElementById("addDesignationBlock");
            const container = document.getElementById("designationUploads");
            const template = document.getElementById("designationBlockTemplate").content;
            const videoModal = document.getElementById("videoModal");
            const modalVideo = document.getElementById("modalVideo");
            const closeVideoModal = document.getElementById("closeVideoModal");

            let blockIndex = 0;

            // Add new block
            addBtn.addEventListener("click", () => {
                const clone = document.importNode(template, true);
                const fileInput = clone.querySelector(".videoUpload");
                const videoList = clone.querySelector(".videoList");

                // update input name for backend mapping
                fileInput.name = `videos[${blockIndex}][]`;
                blockIndex++;

                // Handle file upload & preview
                fileInput.addEventListener("change", (e) => {
                    videoList.innerHTML = "";
                    [...e.target.files].forEach(file => {
                        if (!file.type.startsWith("video/")) return;

                        const url = URL.createObjectURL(file);
                        const videoDiv = document.createElement("div");
                        videoDiv.className = "relative w-40 h-28 border rounded overflow-hidden shadow";

                        videoDiv.innerHTML = `
                            <video src="${url}" class="w-full h-full object-cover cursor-pointer"></video>
                            <button type="button"
                              class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded z-10">X</button>
                        `;

                        // remove preview
                        videoDiv.querySelector("button").addEventListener("click", () => {
                            videoDiv.remove();
                            URL.revokeObjectURL(url);
                        });

                        // open modal
                        videoDiv.querySelector("video").addEventListener("click", () => {
                            modalVideo.src = url;
                            videoModal.classList.remove("hidden");
                            videoModal.classList.add("flex");
                            modalVideo.play().catch(() => { });
                        });

                        videoList.appendChild(videoDiv);
                    });
                });

                // remove block
                clone.querySelector(".removeBlock").addEventListener("click", (e) => {
                    e.target.closest("div").remove();
                });

                container.appendChild(clone);
            });

            // close modal
            function hideVideoModal() {
                try { modalVideo.pause(); } catch { }
                modalVideo.src = "";
                videoModal.classList.add("hidden");
                videoModal.classList.remove("flex");
            }
            closeVideoModal.addEventListener("click", hideVideoModal);
            videoModal.addEventListener("click", (e) => { if (e.target === videoModal) hideVideoModal(); });
            document.addEventListener("keydown", (e) => { if (e.key === "Escape") hideVideoModal(); });

            // auto-fill school
            document.getElementById("schoolName").value =
                "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125";
        });
    </script>
</body>
@include('components.footer') --}}
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
                            <h1 class="m-0 text-dark">Video Feedback</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer</a></li>
                                <li class="breadcrumb-item active">Video Feedback</li>
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

                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Video Feedback</h2>

                                    <form class="space-y-5">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">School
                                                Name</label>
                                            <input type="text" id="schoolName" readonly
                                                class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 cursor-not-allowed">
                                        </div>
                                        <!-- Designation -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                            <select name="designation"
                                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-green-300">
                                                <option value="">-- Select Designation --</option>
                                                <option value="Teacher">Teacher</option>
                                                <option value="HM">HM</option>
                                                <option value="Student">Student</option>
                                                <option value="DEO">DEO</option>
                                                <option value="BEO">BEO</option>
                                                <option value="Guest">Guest</option>
                                                <option value="OCAC Staff">OCAC Staff</option>
                                                <option value="Other">Other</option>
                                            </select>
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
                                                        class="block text-sm font-medium text-gray-700 mb-1 hidden">Time
                                                        of
                                                        Training To</label>
                                                    <input type="time" id="time_to" name="time_to"
                                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300 shadow-sm">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upload Instruction -->
                                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Videos
                                            of
                                            Training</label>

                                        <!-- Upload Box -->
                                        <div id="videoDropZone"
                                            class="border-2 border-dashed border-gray-400 rounded-md p-8 text-center cursor-pointer hover:border-blue-500 transition mb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="mx-auto h-10 w-10 text-gray-500 mb-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M4 6h8M4 10h8m-8 4h5" />
                                            </svg>
                                            <p class="text-gray-500">Drag and drop Video, or click to select</p>

                                            <input type="file" id="videoUpload" class="hidden" accept="video/*"
                                                multiple>
                                            <!-- File Preview Section -->
                                            <div id="videoList" class="mt-3 text-sm text-gray-700 flex flex-wrap gap-3">
                                            </div>
                                        </div>

                                        <!-- Modal (overlay) -->
                                        <div id="videoModal" class="fixed inset-0 bg-black/70 hidden z-[9999]">
                                            <!-- close button is on overlay, not inside the video box -->
                                            <button id="closeVideoModal" type="button"
                                                class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full shadow pointer-events-auto z-[10000]"
                                                aria-label="Close">X</button>

                                            <!-- centered player area -->
                                            <div class="w-full h-full flex items-center justify-center p-4">
                                                <video id="modalVideo" controls
                                                    class="max-w-4xl w-full max-h-[90vh] rounded shadow-lg"></video>
                                            </div>
                                        </div>
                                        <!-- Any Description -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Any
                                                Description</label>
                                            <textarea rows="3" placeholder="Write details here..."
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300"></textarea>
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
        document.addEventListener('DOMContentLoaded', function() {
            const videoDropZone = document.getElementById("videoDropZone");
            const videoInput = document.getElementById("videoUpload");
            const videoList = document.getElementById("videoList");
            const videoModal = document.getElementById("videoModal");
            const modalVideo = document.getElementById("modalVideo");
            const closeVideoModal = document.getElementById("closeVideoModal");

            let uploadedVideos = [];

            // Open file dialog only if clicking the dropzone background/label graphics
            videoDropZone.addEventListener("click", (e) => {
                const tag = e.target.tagName;
                if (e.target === videoDropZone || tag === "P" || tag === "SVG" || tag === "PATH") {
                    videoInput.click();
                }
            });

            videoInput.addEventListener("change", (e) => {
                handleVideos(e.target.files);
                videoInput.value = "";
            });

            videoDropZone.addEventListener("dragover", (e) => {
                e.preventDefault();
                videoDropZone.classList.add("border-blue-500");
            });
            videoDropZone.addEventListener("dragleave", () => {
                videoDropZone.classList.remove("border-blue-500");
            });
            videoDropZone.addEventListener("drop", (e) => {
                e.preventDefault();
                videoDropZone.classList.remove("border-blue-500");
                handleVideos(e.dataTransfer.files);
            });

            function handleVideos(files) {
                [...files].forEach(file => {
                    if (uploadedVideos.length >= 5) {
                        alert("You can only upload up to 5 videos.");
                        return;
                    }
                    if (!file.type.startsWith("video/")) {
                        alert("Only video files are allowed!");
                        return;
                    }

                    uploadedVideos.push(file);
                    const url = URL.createObjectURL(file);

                    const videoDiv = document.createElement("div");
                    videoDiv.className = "relative w-40 h-28 border rounded overflow-hidden shadow";

                    videoDiv.innerHTML = `
        <video src="${url}" class="w-full h-full object-cover cursor-pointer"></video>
        <button type="button"
          class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded z-10">X</button>
      `;

                    // Remove thumb
                    videoDiv.querySelector("button").addEventListener("click", (ev) => {
                        ev.stopPropagation();
                        videoList.removeChild(videoDiv);
                        uploadedVideos = uploadedVideos.filter(f => f !== file);
                        URL.revokeObjectURL(url);
                    });

                    // Open modal
                    videoDiv.querySelector("video").addEventListener("click", (ev) => {
                        ev.stopPropagation();
                        modalVideo.src = url;
                        videoModal.classList.remove("hidden");
                        // ensure flex layout for centering
                        videoModal.classList.add("flex");
                        modalVideo.play().catch(() => {});
                    });

                    videoList.appendChild(videoDiv);
                });
            }

            // close helpers
            function hideVideoModal() {
                try {
                    modalVideo.pause();
                } catch {}
                modalVideo.src = ""; // reset source
                videoModal.classList.add("hidden");
                videoModal.classList.remove("flex");
            }

            // Close via button
            closeVideoModal.addEventListener("click", (ev) => {
                ev.stopPropagation();
                hideVideoModal();
            });

            // Close by clicking overlay background
            videoModal.addEventListener("click", (e) => {
                if (e.target === videoModal) {
                    hideVideoModal();
                }
            });

            // Close with ESC
            document.addEventListener("keydown", (e) => {
                if (e.key === "Escape" && !videoModal.classList.contains("hidden")) {
                    hideVideoModal();
                }
            });
        });
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
    </script>

</body>
@include('components.footer')
