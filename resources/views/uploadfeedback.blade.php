
@include('components.navbar')
@include('components.sidebar')
<style>
@keyframes gradientMove {
  0% { background-position: 0% 50%; }
  100% { background-position: 200% 50%; }
}
.animate-gradient-move {
  animation: gradientMove 2s linear infinite;
}

  #uploadOverlay {
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(2px);
  }

  .loader {
    border-right-color: transparent;
    border-bottom-color: transparent;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.6);
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  /* .wrapper, .content-wrapper {
  position: static !important;
} */

</style>

<body class="hold-transition sidebar-mini layout-fixed">

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper ">
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
            <section class="content relative">
                <div id="uploadOverlay"
                    class="hidden absolute inset-0 bg-black/40 flex flex-col items-center justify-center z-[9999] rounded-lg backdrop-blur-sm">
                 <div class="loader border-t-4 border-green-400 rounded-full w-16 h-16 animate-spin mb-4"></div>
                 <p class="text-white text-lg font-medium mt-4">Uploading, please wait...</p>
               </div>
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">

                                    <h2 class="text-2xl font-semibold text-center mb-6">Upload Video Feedback</h2>
                                    <form id="videoUploadForm" method="POST" action="{{ route('upload.videofeedback') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                    
                                        <!-- School Name -->
                                    <div class="space-y-5">
                                        <div>
                                            <x-input-label for="school_id" :value="__('School Name')" />
                                            <select name="school_id" id="school_id" class="form-control shadow-sm">
                                                <option value="">-- Select School --</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}">
                                                        {{ $school->scm_name }} - {{ $school->scm_udise_code }},
                                                        {{ $school->scm_dist }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Designation -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                                            <select name="designation"
                                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-green-300 shadow-sm">
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

                                            <input type="file" name="video_feedback" id="videoUpload" class="hidden" accept="video/*"
                                            >
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
                                            <textarea rows="3" name="description" placeholder="Write details here..."
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring focus:ring-green-300"></textarea>
                                        </div>
                                        
                                    <!-- Upload Progress Section -->
                                    <div id="progressContainer" class="hidden mt-6">
                                        <div class="w-full bg-gray-200 rounded-full overflow-hidden h-5">
                                            <div id="progressBar"
                                            class="h-5 bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500 bg-[length:200%_100%] animate-gradient-move text-center text-white text-sm font-medium rounded-full transition-all duration-300 ease-linear"
                                            style="width:0%">0%</div>
                                        </div>
                                        <p id="progressStatus" class="text-gray-600 text-sm mt-2 text-center italic">Preparing upload...</p>
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('videoUploadForm');
    const overlay = document.getElementById('uploadOverlay');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressStatus = document.getElementById('progressStatus');
    const videoInput = document.getElementById('videoUpload');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const file = videoInput.files[0];
        if (!file) {
            Swal.fire('Error', 'Please select a video before uploading.', 'error');
            return;
        }

        const formData = new FormData(form);
        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        progressStatus.textContent = 'Uploading...';
        progressBar.classList.remove('bg-red-500');
        progressBar.classList.add('bg-gradient-to-r');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('upload.videofeedback') }}", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

        let smoothProgress = 0;
        let animationSpeed = 50; // lower = faster visual motion
        let targetPercent = 0;
        let animTimer;

        function smoothTo(target) {
            clearInterval(animTimer);
            animTimer = setInterval(() => {
                if (smoothProgress < target && smoothProgress < 90) {
                    smoothProgress += 0.5; // fine-grained smooth motion
                    progressBar.style.width = smoothProgress + '%';
                    progressBar.textContent = Math.floor(smoothProgress) + '%';
                } else {
                    clearInterval(animTimer);
                }
            }, animationSpeed);
        }

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                targetPercent = Math.min(Math.round((e.loaded / e.total) * 100), 90);
                smoothTo(targetPercent);
            }
        });

        xhr.onload = function () {
            clearInterval(animTimer);
            if (xhr.status === 200) {
                progressStatus.textContent = 'Finalizing...';
                let final = smoothProgress;
                const finishTimer = setInterval(() => {
                    if (final < 100) {
                        final += 0.5;
                        progressBar.style.width = final + '%';
                        progressBar.textContent = Math.floor(final) + '%';
                    } else {
                        clearInterval(finishTimer);
                        progressStatus.textContent = '✅ Upload Complete!';
                        setTimeout(() => {
                            Swal.fire('✅ Success', 'Video uploaded successfully!', 'success');
                            overlay.classList.add('hidden');
                            form.reset();
                            progressContainer.classList.add('hidden');
                            document.getElementById('videoList').innerHTML = '';
                        }, 700);
                    }
                }, 60);
            } else {
                progressBar.classList.remove('bg-gradient-to-r');
                progressBar.classList.add('bg-red-500');
                progressStatus.textContent = '❌ Upload failed.';
                Swal.fire('❌ Failed', 'Upload failed. Please try again.', 'error');
                overlay.classList.add('hidden');
            }
        };
        overlay.classList.remove('hidden');
        xhr.send(formData);
    });
});
</script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                // videoInput.value = "";
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
                    if (uploadedVideos.length >= 1) {
                        alert("You can only upload up to 1 videos.");
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
                        modalVideo.play().catch(() => { });
                    });

                    videoList.appendChild(videoDiv);
                });
            }

            // close helpers
            function hideVideoModal() {
                try {
                    modalVideo.pause();
                } catch { }
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                title: '✅ Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            })
        </script>
    @endif
</body>
@include('components.footer')
