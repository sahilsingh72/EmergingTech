@include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">
<script src="https://cdn.tailwindcss.com"></script>

<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">School Data</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">{{ $school->scm_name }}</a></li>
                            <li class="breadcrumb-item active">School Data</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid px-4 pb-6">
                <div class="bg-white shadow rounded-lg p-4 sm:p-6">

                    <!-- SCHOOL INFO -->
                    <div class="mb-6">
                        <h2 class="ext-lg sm:text-xl font-bold text-gray-800">{{ $school->scm_name }}</h2>
                        <p class="text-gray-600 text-sm sm:text-base">
                            District: {{ $school->scm_dist }} |
                            UDISE: {{ $school->scm_udise_code }}
                        </p>
                    </div>

                    <!-- STATUS LIST -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @php
                            $requiredFiles = [
                                'attendance_sheet' => 'Attendance Sheet',
                                'training_photo' => 'Training Photo',
                                'training_video' => 'Training Video',
                                'written_feedback' => 'Student Feedback',
                                'institute_feedback' => 'Institute Feedback',
                                'video_feedback' => 'Video Feedback',
                                'training_completion_certificate' => 'Completion Certificate',
                            ];

                            $uploadedTypes = $school->trainingUploads->pluck('file_type')->toArray();
                        @endphp

                            @foreach($requiredFiles as $key => $label)
                                @php
                                    $isUploaded = in_array($key, $uploadedTypes);
                                @endphp

                                <div class="border rounded p-3 text-center cursor-pointer hover:bg-gray-50" 
                                    onclick="loadUploads({{ $school->scm_id }}, '{{ $key }}', '{{ $label }}')">

                                    <span class="font-medium">{{ $label }}</span><br>

                                    @if($isUploaded)
                                        <span class="text-green-600 font-semibold">Completed</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Not Completed</span>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">
                                        Click to view uploads
                                    </div>
                                </div>
                            @endforeach

                            <!-- INSTITUTE FEEDBACK RATING-->
                            @if (auth()->user()->role->id != '1')
                                <div class="border rounded p-4 text-center cursor-pointer hover:bg-gray-50"
                                    onclick="openInstituteFeedback({{ $school->scm_id }})"
                                    title="Open Institute Feedback Rating">
                                    <p class="font-medium text-gray-700">Institute Feedback Rating</p>
                                    <p class="mt-1 font-semibold
                                        {{ $instituteFeedbackEntrys->count() >= 1 ? 'text-green-700' : 'text-red-600' }}">
                                        {{ $instituteFeedbackEntrys->count() ? 'Completed' : 'Not Completed' }}
                                    </p>

                                    <div class="text-xs text-gray-500 mt-1">
                                        Click to open feedback
                                    </div>
                                </div>
                            @endif

                            <!-- STUDENT FEEDBACK RATING-->
                            @if (auth()->user()->role->id != '1')
                                <div class="border rounded p-4 text-center cursor-pointer hover:bg-gray-50"
                                    onclick="openStudentFeedback({{ $school->scm_id }})"
                                    title="Open Student Feedback Rating">
                                    <p class="font-medium text-gray-700">Student Feedback Rating</p>
                                    <p class="mt-1 font-semibold
                                        {{ $studentsWithFeedbackEntrys->count() == 0 ? 'text-red-600' : ($studentsWithFeedbackEntrys->count() >= 120 ? 'text-green-700' : 'text-yellow-600') }}">
                                        {{ $studentsWithFeedbackEntrys->count() >= 120 ? 'Completed' : 'Not Completed'}}<br>
                                        {{ $studentsWithFeedbackEntrys->count() }} / {{ $totalStudents }}
                                        <span class="text-sm font-normal text-gray-600">(Minimum 120 required)</span>
                                    </p>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Click to open feedback list
                                    </div>
                                </div>
                            @endif

                        <!-- TRAINING COMPLETED -->
                        {{-- <div class="border rounded p-4 text-center sm:col-span-2 lg:col-span-3">
                            <p class="font-medium text-gray-700">Training Completed</p>
                            <p class="mt-1 font-bold text-lg
                                {{ $school->training_completed ? 'text-green-700' : 'text-red-600' }}">
                                {{ $school->training_completed ? 'YES' : 'NO' }}
                            </p>
                        </div> --}}
                    </div>
                    <div class="text-left mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- Upload Viewer Modal -->
<div class="modal fade" id="uploadViewerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadViewerTitle"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div id="uploadList" class="list-group"></div>
            </div>
        </div>
    </div>
</div>

</body>
<script>
function loadUploads(schoolId, type, title) {

    let url = "{{ route('school.uploads.byType', ['schoolId' => '__ID__', 'type' => '__TYPE__']) }}"
        .replace('__ID__', schoolId)
        .replace('__TYPE__', type);

    fetch(url)
        .then(res => res.json())
        .then(files => {

            document.getElementById('uploadViewerTitle').innerText = title;
            const list = document.getElementById('uploadList');
            list.innerHTML = '';

            if (!files.length) {
                list.innerHTML = `<div class="text-muted">No files uploaded</div>`;
                $('#uploadViewerModal').modal('show');
                return;
            }

            files.forEach(file => {
                const filename =
                    file.school.replace(/[^A-Za-z0-9_\-]/g, '_') +
                    '_' + file.file_type;

                list.innerHTML += `
                    <a href="/preview-files?path=${encodeURIComponent(file.file_path)}
                        &filename=${filename}"
                       target="_blank"
                       class="list-group-item list-group-item-action">
                        <i class="fas fa-eye mr-2 text-primary"></i>
                        Click here to view uploads
                    </a>
                `;
            });

            $('#uploadViewerModal').modal('show');
        })
        .catch(() => alert('Unable to load uploads'));
}
</script>
<script>
function openInstituteFeedback(schoolId) {
    const url = "{{ route('institute.feedback.entry') }}?school_id=" + schoolId;
    window.location.href = url;
}

function openStudentFeedback(schoolId) {
    const url = "{{ route('student.feedback') }}?school_id=" + schoolId;
    window.location.href = url;
}
</script>


@include('components.footer')
