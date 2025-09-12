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
                            <h1 class="m-0 text-dark">Training Videos</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Training Videos</a></li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Training Videos</h2>

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
                                        
                                    </div>

                                    <!-- Upload Instruction -->
                                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Upload Videos of
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

                                        <input type="file" id="videoUpload" class="hidden" accept="video/*" multiple>
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
</body>
@include('components.footer')
