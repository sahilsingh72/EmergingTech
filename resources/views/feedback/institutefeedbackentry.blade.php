@include('components.navbar')
@include('components.sidebar')
<style>
  .star-rating {
    display: flex;
    gap: 15px;
    cursor: pointer;
  }

  .star-rating i {
    font-size: 35px;
    cursor: pointer;
    color: #ccc;
    transition: color 0.2s;

  }

  .star-rating i:hover {
    color: gold;
  }

  .star-rating i.selected {
    color: #f7b500 !important;
    /* gold */
  }


  @keyframes gradientMove {
    0% {
      background-position: 0% 50%;
    }

    100% {
      background-position: 200% 50%;
    }
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
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  /* .wrapper, .content-wrapper {
  position: static !important;
} */
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
              <h1 class="m-0 text-dark">Institute Feedback Entry</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Feedback</a></li>
                <li class="breadcrumb-item active">Institute Feedback Entry</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content relative">

        <div class="container-fluid">
          <div class="py-12">
            <div class="max-w-8xl mx-auto space-y-6">
              <div class="px-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="bg-white rounded-lg w-full">
                  <!-- Title -->
                  <h2 class="text-2xl font-semibold text-center mb-6">Institute Feedback Form</h2>

                  @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                  @endif

                  @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif

                  <form method="POST" action="{{ route('institute.feedback.entry.store') }}">
                    @csrf
                    @php
                        $roleId = Auth::user()->role_id;
                    @endphp
                    @if($roleId == 1 || $roleId == 2 || $roleId == 8)

                        <div class="row mb-2">
                            {{-- District --}}
                            <div class="col-md-6">
                                <label>District</label>
                                <select id="filterDistrict" class="form-control">
                                    <option value="">-- Select District --</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->DSM_DSCD }}"
                                            {{ $selectedDistrictId == $district->DSM_DSCD ? 'selected' : '' }}>
                                            {{ $district->DSM_DSNM }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- School --}}
                            <div class="col-md-6">
                                <label>School</label>
                                <select id="filterSchool" class="form-control">
                                    <option value="">-- Select School --</option>
                                </select>
                            </div>
                        </div>
                    @else

                    <!-- School -->
                    <label class="font-semibold">School</label>
                    <select name="school_id" class="form-control mb-3" required onchange="location.href='{{ route('institute.feedback.entry') }}?school_id=' + this.value">
                      <option value="">-- Select School --</option>
                      @foreach($schools as $school)
                        <option value="{{ $school->scm_id }}"
                          {{ request('school_id') == $school->scm_id ? 'selected' : '' }}>
                          {{ $school->scm_name }} - {{ $school->scm_dist }}
                        </option>
                      @endforeach
                    </select>
                    @endif

                    <!-- Training Date -->
                    <label class="font-semibold">Training Date</label>
                    <input type="date" name="training_date" id="training_date" class="form-control mb-4" value="{{ old('training_date', $trainingDate) }}" readonly>


                    <hr class="my-4">

                    <div class="border rounded p-3 mb-4 bg-light">
                      <h1 class="">Camp Implementation Feedback</h1><br>
                      <p class="fw-bold text-secondary mb-3">
                        Please rate on a scale of 1–5 (★)
                      (1 = Poor | 2 = Fair | 3 = Good | 4 = Very Good | 5 = Excellent)
                    </p>

                    @php
                      $ratingQuestions = [
                        'planning_coordination' => 'Overall planning and coordination of the camp',
                        'timeliness_discipline' => 'Timeliness and discipline of the camp team',
                        'trainer_quality' => 'Quality of trainers and subject knowledge',
                        'student_engagement' => 'Student engagement and interaction',
                        'clarity_explanation' => 'Clarity of explanations and demonstrations',
                        'iot_robotics_usefulness' => 'Usefulness of IoT & Robotics demonstrations for technical knowledge',
                        'ai_relevance' => 'Relevance of Artificial Intelligence session in education',
                        'cyber_awareness_need' => 'Need of Cybersecurity awareness and safety learning',
                        'equipment_quality' => 'Quality of equipment and learning materials',
                        'overall_impact' => 'Overall impact on students'
                      ];
                      @endphp

                    <div class="row">
                      @foreach($ratingQuestions as $key => $label)
                      <div class="col-md-6 mb-4">
                          <label>{{ $loop->iteration }}. {{ $label }}</label>

                              @php
                                $value = old($key, $existingFeedback->$key ?? 0);
                                @endphp
                          <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star {{ $value >= $i ? 'selected' : '' }}" data-question="{{ $key }}"
                              onclick="setRating('{{ $key }}', {{ $i }})"></i>
                              @endfor
                            </div>
                            
                            <input type="hidden" id="{{ $key }}_input" name="{{ $key }}" value="{{ $value }}" required>
                          </div>
                          @endforeach
                        </div>
                        
                      </div>  
                  <div class="border rounded p-3 mb-4 bg-light">
                    
                    <h1 class="">Outcome & Impact</h1><br>
                    <div class="mb-3">
                      <label class="fw-semibold">
                        1. Did the camp increase students’ awareness about emerging technology?
                      </label><br>
                      <label>
                        <input type="radio" name="awareness_increased" value="1" 
                        {{ old('awareness_increased', $existingFeedback->awareness_increased ?? '') == 1 ? 'checked' : '' }} required> 
                          Yes
                        </label>
                        <label class="ms-3">
                        <input type="radio" name="awareness_increased" value="0"  
                          {{ old('awareness_increased', $existingFeedback->awareness_increased ?? '') === 0 ? 'checked' : '' }}>
                          No
                      </label>
                    </div>

                    <div class="mb-3">
                      <label class="fw-semibold">
                        2. Did students show enthusiasm and interest during sessions?
                      </label><br>
                      <label><input type="radio" name="student_enthusiasm" value="High" required {{ old('student_enthusiasm', $existingFeedback->student_enthusiasm ?? '') == 'High' ? 'checked' : '' }}> High</label>
                      <label class="ms-3"><input type="radio" name="student_enthusiasm" value="Moderate" {{ old('student_enthusiasm', $existingFeedback->student_enthusiasm ?? '') == 'Moderate' ? 'checked' : '' }}> Moderate</label>
                      <label class="ms-3"><input type="radio" name="student_enthusiasm" value="Low" {{ old('student_enthusiasm', $existingFeedback->student_enthusiasm ?? '') == 'Low' ? 'checked' : '' }}> Low</label>
                    </div>

                    <div class="mb-3">
                      <label class="fw-semibold">
                        3. Do you feel such programs are beneficial for school/college students?
                      </label><br>
                      <label><input type="radio" name="program_beneficial" value="Strongly Agree" {{ old('program_beneficial', $existingFeedback->program_beneficial ?? '') == 'Strongly Agree' ? 'checked' : '' }} required> Strongly
                        Agree</label>
                      <label class="ms-3"><input type="radio" name="program_beneficial" value="Agree" {{ old('program_beneficial', $existingFeedback->program_beneficial ?? '') == 'Agree' ? 'checked' : '' }}> Agree</label>
                      <label class="ms-3"><input type="radio" name="program_beneficial" value="Neutral" {{ old('program_beneficial', $existingFeedback->program_beneficial ?? '') == 'Neutral' ? 'checked' : '' }}> Neutral</label>
                      <label class="ms-3"><input type="radio" name="program_beneficial" value="Disagree" {{ old('program_beneficial', $existingFeedback->program_beneficial ?? '') == 'Disagree' ? 'checked' : '' }}> Disagree</label>
                    </div>

                    <div class="mb-4">
                      @php
                        $future = old(
                            'future_program_interest',
                            json_decode($existingFeedback->future_program_interest ?? '[]', true)
                        );
                      @endphp
                      <label class="fw-semibold">
                        4. Would you like similar advanced level programs in the future?
                      </label><br>
                      <label><input type="checkbox" name="future_program_interest[]" value="AI" {{ in_array('AI', $future) ? 'checked' : '' }} > AI</label>
                      <label class="ms-3"><input type="checkbox" name="future_program_interest[]" value="IoT & Robotics" {{ in_array('IoT & Robotics', $future) ? 'checked' : '' }}> IoT &Robotics</label>
                      <label class="ms-3"><input type="checkbox" name="future_program_interest[]" value="Cybersecurity" {{ in_array('Cybersecurity', $future) ? 'checked' : '' }}> Cybersecurity</label>
                      <label class="ms-3"><input type="checkbox" name="future_program_interest[]" value="All" {{ in_array('All', $future) ? 'checked' : '' }}> All</label>
                    </div>
                  </div>

                  @php
                    $roleId = Auth::user()->role_id;
                  @endphp
                  @if($roleId == 3 || $roleId == 6)
                    <button class="bg-green-600 hover:bg-green-700 text-white w-full py-2 rounded text-lg">
                      Submit Feedback
                    </button>
                  @endif
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
    function setRating(questionName, value) {
      document.getElementById(questionName + "_input").value = value;

      let stars = document.querySelectorAll(`[data-question='${questionName}']`);
      stars.forEach((star, index) => {
        if (index < value) {
          star.classList.add("selected");
        } else {
          star.classList.remove("selected");
        }
      });
    }
  </script>
  <script>
    document.querySelector('select[name="school_id"]').addEventListener('change', function () {
      const date = this.options[this.selectedIndex].dataset.date || '';
      document.getElementById('training_date').value = date;
    });
  </script>
<script>
function loadSchools(districtId, selectedSchoolId = null) {

    $("#filterSchool").html('<option value="">Loading...</option>');

    $.ajax({
        url: "{{ route('schools.byDistrict') }}",
        method: "GET",
        data: { district_id: districtId },
        success: function (schools) {
            let options = '<option value="">-- Select School --</option>';

            schools.forEach(school => {
                let selected =
                    selectedSchoolId == school.scm_id ? 'selected' : '';

                options += `
                    <option value="${school.scm_id}" ${selected}>
                        ${school.scm_name} (${school.scm_udise_code})
                    </option>`;
            });

            $("#filterSchool").html(options);
        }
    });
}

// district change
$(document).on("change", "#filterDistrict", function () {
    let districtId = $(this).val();
    if (!districtId) {
        $("#filterSchool").html('<option value="">-- Select School --</option>');
        return;
    }
    loadSchools(districtId);
});

// 🔥 page load restore
$(document).ready(function () {
    let districtId = "{{ $selectedDistrictId }}";
    let schoolId   = "{{ $selectedSchoolId }}";

    if (districtId) {
        loadSchools(districtId, schoolId);
    }
});
</script>

<script>
$(document).on("change", "#filterSchool", function () {
    let schoolId = $(this).val();
    if (!schoolId) return;

    window.location.href =
        "{{ route('institute.feedback.entry') }}?school_id=" + schoolId;
});
</script>


</body>
@include('components.footer')