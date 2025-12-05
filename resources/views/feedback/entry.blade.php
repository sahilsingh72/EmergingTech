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
              <h1 class="m-0 text-dark">Student Feedback Entry</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Feedback</a></li>
                <li class="breadcrumb-item active">Feedback Entry</li>
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
              <div class="sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="bg-white rounded-lg w-full">
                  <!-- Title -->
                  <h2 class="text-2xl font-semibold text-center mb-6">Feedback Entry — {{ $student->stu_name }}</h2>

                  @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                  @endif
                  <div class="card-body">
                    <form action="{{ route('student.feedback.store') }}" method="POST">
                      @csrf
                      <input type="hidden" name="stu_id" value="{{ $student->stu_id }}">
                      <input type="hidden" name="school_id" value="{{ $student->stu_scm_id }}">

                      <!-- Pre Feedback -->
                      <h4 class="text-primary mb-3">ପ୍ରଶିକ୍ଷଣ ପୂର୍ବ ମତାମତ (Pre Feedback)</h4>
                      <div class="border rounded p-3 mb-4 bg-light">
                        
                        {{-- <label>1. Have you attended any technology-related training before?</label><br> --}}
                        <label>୧. ଆପଣ ପୂର୍ବରୁ କୌଣସି ପ୍ରଯୁକ୍ତିବିଦ୍ୟା ସମ୍ପର୍କିତ କର୍ମଶାଳା କିମ୍ବା ପ୍ରଶିକ୍ଷଣ ଶିବିରରେ ଯୋଗ ଦେଇଛନ୍ତି କି?</label><br>
                        <div class="ms-2">
                          <label><input type="radio" name="pre_attended_training" value="1" required {{ old('pre_attended_training', $existingFeedback->pre_attended_training ?? '') == '1' ? 'checked' : '' }}> ହଁ</label>
                          <label class="ms-3"><input type="radio" name="pre_attended_training" value="0" required {{ old('pre_attended_training', $existingFeedback->pre_attended_training ?? '') == '0' ? 'checked' : '' }}> ନାହିଁ</label>
                        </div>
                        {{-- <input type="text" name="pre_if_any" class="form-control mt-2"
                          placeholder="If any, please specify"
                          value="{{ old('pre_if_any', $existingFeedback->pre_if_any ?? '') }}">
                        <hr></br> --}}

                        {{-- <label>2. Have you heard about these technologies before? (AI, IoT & Robotics,
                          Cybersecurity)</label><br>
                        <div class="ms-2">
                          <label><input type="radio" name="pre_heard_technologies" value="1" required {{ old('pre_heard_technologies', $existingFeedback->pre_heard_technologies ?? '') == '1' ? 'checked' : '' }}> Yes</label>
                          <label class="ms-3"><input type="radio" name="pre_heard_technologies" value="0" required {{ old('pre_heard_technologies', $existingFeedback->pre_heard_technologies ?? '') == '0' ? 'checked' : '' }}> No</label>
                        </div>

                        <div class="mt-2 ms-2">
                          <label>If Yes, which ones:</label><br>
                          <label><input type="checkbox" name="pre_heard_ai" value="1" {{ old('pre_heard_tecpre_heard_aihnologies', $existingFeedback->pre_heard_ai ?? '') == '1' ? 'checked' : '' }}> AI</label>
                          <label class="ms-3"><input type="checkbox" name="pre_heard_iot" value="1" {{ old('pre_heard_iot', $existingFeedback->pre_heard_iot ?? '') == '1' ? 'checked' : '' }}> IoT
                            & Robotics</label>
                          <label class="ms-3"><input type="checkbox" name="pre_heard_cybersecurity" value="1" {{ old('pre_heard_cybersecurity', $existingFeedback->pre_heard_cybersecurity ?? '') == '1' ? 'checked' : '' }}> Cybersecurity</label>
                        </div> --}}

                        </br>
                        <hr></br>
                        <p class="fw-bold text-secondary mb-2">
                          {{-- Please give your opinion on a scale of 1–5 (1 = Lowest, 5 = Highest): --}}
                          ନିମ୍ନଲିଖିତ ପ୍ରଶ୍ନଗୁଡିକ ପାଇଁ ୧ ରୁ ୫ ମଧ୍ୟରେ ଆପଣଙ୍କ ମତାମତ (&#x2605) ଦିଅନ୍ତୁ। (୧=ସବୁଠାରୁ କମ୍, ୫=ସବୁଠାରୁ ଅଧିକ):
                        </p>

                        <div class="row">
                          <div class="col-md-6 mb-3">
                            {{-- <label>3. How much do you know about technology?</label> --}}
                            <label>୨. ଆପଣଙ୍କର AI, IoT&Robotics, Cyber Security ଓ ସୁରକ୍ଷିତ ଇଣ୍ଟରନେଟ ବ୍ୟବହାର ବିଷୟରେ ଜ୍ଞାନ କେତେ ଅଛି?</label>
                            @php
                              $value = old('pre_know_tech', $existingFeedback->pre_know_tech ?? '');
                            @endphp
                            <div class="star-rating">
                              @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $value >= $i ? 'selected' : '' }}" data-question="pre_know_tech"
                                  onclick="setRating('pre_know_tech', {{ $i }})"></i>
                              @endfor
                            </div>

                            <input type="hidden" id="pre_know_tech_input" name="pre_know_tech" class="form-control"
                              value="{{ $value }}" required>
                            @error('pre_know_tech')
                              <span class="text-danger small">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                            {{-- <label>4. How confident are you in using technology?</label> --}}
                            <label>୩. Digital Device ଓ Technology ବ୍ୟବହାର କରିବାରେ ଆପଣ କେତେ ଆତ୍ମବିଶ୍ୱାସୀ?</label>
                            @php
                              $value = old('pre_confidence', $existingFeedback->pre_confidence ?? '');
                            @endphp
                            <div class="star-rating">
                              @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $value >= $i ? 'selected' : '' }}" data-question="pre_confidence"
                                  onclick="setRating('pre_confidence', {{ $i }})"></i>
                              @endfor
                            </div>
                            <input type="hidden" id="pre_confidence_input" name="pre_confidence" class="form-control"
                              value="{{ $value }}" required>
                            @error('pre_confidence')
                              <span class="text-danger small">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                            {{-- <label>5. How interested are you in learning about technology before this training?</label> --}}
                            <label>୪. ନୂଆଁ Technology ଶିଖିବା ପାଇଁ ଆପଣ କେତେ ଆଗ୍ରହୀ?</label>
                            @php
                              $value = old('pre_interest', $existingFeedback->pre_interest ?? '');
                            @endphp
                            <div class="star-rating">
                              @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $value >= $i ? 'selected' : '' }}" data-question="pre_interest"
                                  onclick="setRating('pre_interest', {{ $i }})"></i>
                              @endfor
                            </div>
                            <input type="hidden" id="pre_interest_input" name="pre_interest" class="form-control"
                              value="{{ $value }}" required>
                            @error('pre_interest')
                              <span class="text-danger small">{{ $message }}</span>
                            @enderror
                          </div>

                          <div class="col-md-6 mb-3">
                            {{-- <label>6. How useful do you think this camp will be for your learning or career?</label> --}}
                            <label>୫. ଭବିଷ୍ୟତରେ ଚାକିରି ପାଇଁ Emerging Technology ଭଳି ପ୍ରଯୁକ୍ତିବିଦ୍ୟାକୁ ଆପଣ କେତେ ଗୁରୁତ୍ୱପୂର୍ଣ୍ଣ ଭାବୁଛନ୍ତି?</label>
                            @php
                              $value = old('pre_usefulness', $existingFeedback->pre_usefulness ?? '');
                            @endphp
                            <div class="star-rating">
                              @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $value >= $i ? 'selected' : '' }}" data-question="pre_usefulness"
                                  onclick="setRating('pre_usefulness', {{ $i }})"></i>
                              @endfor
                            </div>
                            <input type="hidden" id="pre_usefulness_input" name="pre_usefulness" class="form-control"
                              value="{{ $value }}" required>
                            @error('pre_usefulness')
                              <span class="text-danger small">{{ $message }}</span>
                            @enderror
                          </div>
                        </div>

                        {{-- <label class="mt-2">7. What are your expectations from this camp?</label>
                        <textarea name="pre_expectations" class="form-control" rows="2"
                          required>{{ old('pre_expectations', $existingFeedback->pre_expectations ?? '') }}</textarea>
                        --}}
                      </div>

                      <!-- Post Feedback -->
                      <h4 class="text-primary mb-3">ପ୍ରଶିକ୍ଷଣ ପରବର୍ତ୍ତୀ ମତାମତ (Post Feedback)</h4>
                      <div class="border rounded p-3 mb-4 bg-light">
                        {{-- <label>1. Which course interests you most?</label><br> --}}
                        <label>୧. ଏହି ଶିବିରରେ ଆପଣ କେଉଁ ବିଷୟକୁ ସବୁଠାରୁ ଉପଯୋଗୀ କିମ୍ବା ଆନନ୍ଦଦାୟକ ମନେକଲେ?</label><br>
                        <div class="ms-2">
                          <label><input type="radio" name="post_interested_course" value="AI" {{ old('post_interested_course', $existingFeedback->post_interested_course ?? '') == 'AI' ? 'checked' : '' }}> AI</label>
                          <label class="ms-3"><input type="radio" name="post_interested_course" value="IoT & Robotics"
                              {{ old('post_interested_course', $existingFeedback->post_interested_course ?? '') == 'IoT & Robotics' ? 'checked' : '' }}> IoT & Robotics</label>
                          <label class="ms-3"><input type="radio" name="post_interested_course" value="Cybersecurity" {{ old('post_interested_course', $existingFeedback->post_interested_course ?? '') == 'Cybersecurity' ? 'checked' : '' }}> Cybersecurity</label>
                        </div>

                        </br>
                        <hr></br>
                        <p class="fw-bold text-secondary mb-2">
                          ନିମ୍ନଲିଖିତ ପ୍ରଶ୍ନଗୁଡିକ ପାଇଁ ୧ ରୁ ୫ ମଧ୍ୟରେ ଆପଣଙ୍କ ମତାମତ (&#x2605) ଦିଅନ୍ତୁ। (୧=ସବୁଠାରୁ କମ୍, ୫=ସବୁଠାରୁ ଅଧିକ):
                        </p>

                        @php
                          $questions = [
                            // 'post_knowledge_improve' => '2. How much did your knowledge improve after the training?',
                            'post_knowledge_improve' => '୨. ପ୍ରଶିକ୍ଷଣ ପରେ AI, IoT & Robotics ଓ Cyber Security ବିଷୟରେ ଆପଣଙ୍କ ଜ୍ଞାନ କେତେ ବଢ଼ିଲା?',
                            // 'post_confidence_now' => '3. How confident are you in using technology now?',
                            'post_confidence_now' => '୩. ବର୍ତମାନ Technology ବ୍ୟବହାର କରିବାରେ ଆପଣ କେତେ ଆତ୍ମବିଶ୍ୱାସୀ?',
                            // 'post_engagement' => '4. How interesting and engaging were the sessions?',
                            'post_engagement' => '୪. ପ୍ରଶିକ୍ଷଣ କାର୍ଯ୍ୟକ୍ରମଗୁଡ଼ିକ କେତେ ରୁଚିକର ଓ ଆକର୍ଷଣୀୟ ଥିଲା?',
                            // 'post_usefulness' => '5. How useful do you think this training will be for your studies or career?',
                            'post_usefulness' => '୫. ଏହି ପ୍ରଶିକ୍ଷଣ ଆପଣଙ୍କ ପାଠପଢା କିମ୍ବା ଭବିଷ୍ୟତ Career ପାଇଁ କେତେ ଉପଯୋଗୀ?',
                            // 'post_demo_helpfulness' => '6. How helpful were the demonstrations?',
                            'post_demo_helpfulness' => '୬. ଶିବିର ସମୟରେ ଦେଖାଯାଇଥିବା Demonstration ଗୁଡ଼ିକ କେତେ ଉପକାରୀ ଥିଲା?',
                            // 'post_topic_coverage' => '7. How well did the camp cover the main topics?',
                            'post_topic_coverage' => '୭. ପ୍ରଶିକ୍ଷଣ ଶିବିରରେ  ପ୍ରମୁଖ  ବିଷୟଗୁଡିକ କେତେ ଭଲ ଭାବରେ ଉପସ୍ଥାପନ କରାଗଲା?',
                            // 'post_hands_on_usefulness' => '8. How useful were the hands-on activities?',
                            'post_hands_on_usefulness' => '୮. Hands-on activities କେତେ ଶିକ୍ଷଣୀୟ ଥିଲା?',
                            // 'post_real_life_use' => '9. How likely are you to use what you learned in real life?',
                            'post_real_life_use' => '୯. ଶିଖିଥିବା ଜ୍ଞାନ କୌଶଳକୁ ଆପଣ ବାସ୍ତବ ଜୀବନରେ ବ୍ୟବହାର କରିବାକୁ କେତେ ଇଚ୍ଛୁକ?',
                            // 'post_trainer_rating' => '10. How would you rate the trainers’ teaching?'
                            'post_trainer_rating' => '୧୦. ତାଲିମ ପ୍ରଦାନକାରୀଙ୍କର ଶିକ୍ଷାଦାନ କେତେ ଗ୍ରହଣୀୟ ଥିଲା?',
                            // 'post_overall_satisfaction' => '11. How satisfied are you with the training overall?',
                            'post_overall_satisfaction' => '୧୧. ସମଗ୍ର ପ୍ରଶିକ୍ଷଣ ପ୍ରତି ଆପଣ କେତେ ସନ୍ତୁଷ୍ଟ?',
                            // 'post_interest_increase' => '12. How much has your interest in technology increased after this camp?',
                            'post_interest_increase' => '୧୨. Technology ସମ୍ବନ୍ଧୀୟ କ୍ୟାରିୟର କରିବା ବିଷୟରେ ଆପଣଙ୍କ ଆଗ୍ରହ କେତେ ବଢ଼ିଲା?',
                            // 'post_motivation_future' => '13. How motivated are you to learn more about technology in the future?',
                            'post_motivation_future' => '୧୩. ଭବିଷ୍ୟତରେ ଆପଣ Emerging Technology ବିଷୟରେ ଅଧିକ ପ୍ରଶିକ୍ଷଣ ଚାହୁଁଛନ୍ତି?',
                          ];
                        @endphp

                        <div class="row">
                          @foreach($questions as $name => $label)
                            <div class="col-md-6 mb-3">
                              <label>{{ $label }}</label>

                              <div class="star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                  <i class="fa fa-star {{ (old($name, $existingFeedback->$name ?? '') >= $i) ? 'selected' : '' }}"
                                    data-question="{{ $name }}" onclick="setRating('{{ $name }}', {{ $i }})"></i>
                                @endfor
                              </div>
                              <input type="hidden" id="{{ $name }}_input" name="{{ $name }}" class="form-control"
                                value="{{ old($name, $existingFeedback->$name ?? '') }}" required>
                              @error($name)
                                <span class="text-danger small">{{ $message }}</span>
                              @enderror
                            </div>
                          @endforeach
                        </div>

                        {{-- <label class="mt-3">14. Do you have any other comments or suggestions?</label>
                        <textarea name="post_comments" class="form-control" rows="2"
                          required>{{ old('post_comments', $existingFeedback->post_comments ?? '') }}</textarea> --}}

                        {{-- <label class="mt-3">15. What did you find most useful or enjoyable about the camp?</label>
                        <textarea name="post_most_useful" class="form-control" rows="2"
                          required>{{ old('post_most_useful', $existingFeedback->post_most_useful ?? '') }}</textarea>
                        --}}
                      </div>

                      <div class="text-end">
                        <a href="{{ route('student.feedback') }}" class="btn btn-secondary">Back</a>
                        @php
                          use Illuminate\Support\Facades\Auth;
                          $user = Auth::user();
                        @endphp
                        @if($user->role_id == 3 || $user->role_id == 6)
                          <button type="submit" class="btn btn-success">Submit Feedback</button>
                        @endif
                      </div>
                    </form>
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

</body>
@include('components.footer')