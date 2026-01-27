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
                        <h1 class="m-0 text-dark">Training Completion Audit</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Training Completion Audit</li>
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

                                <div class="border rounded p-3 text-center">
                                    <span class="font-medium">{{ $label }}</span><br>

                                    @if($isUploaded)
                                        <span class="text-green-600 font-semibold">Uploaded</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Not Uploaded</span>
                                    @endif
                                </div>
                            @endforeach

                            <!-- INSTITUTE FEEDBACK RATING-->
                            <div class="border rounded p-4 text-center">
                                <p class="font-medium text-gray-700">Institute Feedback Rating</p>
                                <p class="mt-1 font-semibold
                                    {{ $instituteFeedbackEntrys->count() >= 1 ? 'text-green-700' : 'text-red-600' }}">
                                    {{ $instituteFeedbackEntrys->count() ? 'Done' : 'Not Done' }}
                                </p>
                            </div>

                            <!-- STUDENT FEEDBACK RATING-->
                            <div class="border rounded p-4 text-center">
                                <p class="font-medium text-gray-700">Student Feedback Rating</p>
                                <p class="mt-1 font-semibold
                                    {{ $studentsWithFeedbackEntrys->count() >= 120 ? 'text-green-700' : 'text-yellow-600' }}">
                                    {{ $studentsWithFeedbackEntrys->count() }} / {{ $totalStudents }}
                                    <span class="text-sm font-normal text-gray-600">(Minimum 120 required)</span>
                                </p>
                            </div>

                        <!-- TRAINING COMPLETED -->
                        <div class="border rounded p-4 text-center sm:col-span-2 lg:col-span-3">
                            <p class="font-medium text-gray-700">Training Completed</p>
                            <p class="mt-1 font-bold text-lg
                                {{ $school->training_completed ? 'text-green-700' : 'text-red-600' }}">
                                {{ $school->training_completed ? 'YES' : 'NO' }}
                            </p>
                        </div>
                    </div>
                    <!-- AUDIT ACTION (ACCOUNTS ONLY) -->
                    @if(auth()->user()->role->name === 'Accounts')
                    <div class="border-t pt-6">
                        @php
                            $hasCertificate = in_array(
                                'training_completion_certificate',
                                $school->trainingUploads->pluck('file_type')->toArray()
                            );

                            $hasStudentRating = $studentsWithFeedbackEntrys->count() >= 120;
                            $hasInstituteRating = $instituteFeedbackEntrys->count() >= 1;

                            $canApprove = $hasStudentRating && $hasCertificate && $hasInstituteRating;
                        @endphp

                        <form method="POST" action="{{ route('audit.action') }}">
                            @csrf
                            <input type="hidden" name="school_id" value="{{ $school->scm_id }}">

                            <label class="block font-medium mb-1">Audit Comment</label>
                            <textarea name="comment" required
                                class="w-full border rounded p-2 mb-4"
                                placeholder="Enter audit comment"></textarea>

                            @if($school->training_completed)
                                <div class="mb-3 text-green-600 font-semibold text-sm">
                                
                                </div>
                            @else
                                <div class="mb-3 text-red-600 font-semibold text-sm">
                                    ⚠️ Approval: Necessary training files not uploaded
                                </div>
                            @endif
                            @if($studentsWithFeedbackEntrys->count() < 120)
                                <div class="mb-3 text-red-600 font-semibold text-sm">
                                    ⚠️ Approval: Minimum 120 student feedback ratings required
                                </div>
                            @endif
                            @if(!$hasInstituteRating)
                                <div class="mb-3 text-red-600 font-semibold text-sm">
                                    ⚠️ Approval: Institute feedback rating entry required
                                </div>
                            @endif
                            <div class="flex flex-col sm:flex-row gap-3">
                                {{-- <button name="action" value="approve"
                                class="px-4 py-2 rounded text-white
                                {{ $studentsWithFeedback < 120
                                ? 'bg-gray-400 cursor-not-allowed'
                                : 'bg-green-600 hover:bg-green-700' }}"
                                {{ $studentsWithFeedback < 120 ? 'disabled' : '' }}>
                                Approve
                            </button> --}}
                                <button name="action" value="approve"
                                    class="px-4 py-2 rounded text-white
                                    {{ $canApprove ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }}"
                                    {{ $canApprove ? '' : 'disabled' }}>
                                    Approve
                                </button>

                                <button name="action" value="reject"
                                    class="bg-red-600 text-white px-4 py-2 rounded">
                                    Reject
                                </button>

                                <button name="action" value="revert"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded">
                                    Revert
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                </div>
            </div>
        </section>
    </div>
</div>

</body>
@include('components.footer')
