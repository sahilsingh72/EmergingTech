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
              <h1 class="m-0 text-dark">Feedback Summary Report</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Feedback</a></li>
                <li class="breadcrumb-item active">Summary Report</li>
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
              <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="bg-white rounded-lg w-full">

                  <!-- Title -->
                  <h2 class="text-2xl font-semibold text-center mb-6"></h2>

                  <form method="GET" class="mb-4">
                    <div class="row flex mb-4 justify-between">
                      <div class="col-md-6">
                        <label for="district_id">Select District</label>
                        <select name="district_id" id="district_id" class="form-control" onchange="this.form.submit()">
                          <option value="">All Districts</option>
                          @foreach($districts as $district)
                            <option value="{{ $district->DSM_DSCD }}" {{ $districtId == $district->DSM_DSCD ? 'selected' : '' }}>
                              {{ $district->DSM_DSNM }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="school_id">Select School</label>
                        <select name="school_id" id="school_id" class="form-control" onchange="this.form.submit()">
                          <option value="">All Schools</option>
                          @foreach($schools as $school)
                            <option value="{{ $school->scm_id }}" {{ $schoolId == $school->scm_id ? 'selected' : '' }}>
                              {{ $school->scm_name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6 justify-end">
                        <h5 class="text-muted mt-3">Total Student Feedbacks: {{ $totalFeedbacks }}</h5>
                      </div>
                    </div>
                  </form>

                  @if($totalFeedbacks > 0)

                    <div class="row my-5">
                      <div class="col-md-12">
                        <h4 class="text-center text-dark mb-3">📈 Pre vs Post Comparison (Key Areas)</h4>
                        <canvas class="border rounded p-3 bg-white shadow-sm" id="comparisonChart"></canvas>
                      </div>
                    </div>
                    <hr class="my-5">

                    <div class="row mt-5">
                      <div class="col-md-12">
                        <h4 class="text-center text-dark mb-3">📊 Detailed Post Feedback (All Parameters)</h4>
                        <canvas class="border rounded p-3 bg-white shadow-sm" id="detailedPostChart"></canvas>
                      </div>
                    </div>
                    <hr class="my-5">

                    <div class="my-5">
                      <div class="row mt-5">
                        <div class="col-md-12">
                          <h4 class="text-center text-dark mb-4">📊 Individual Post Feedback Parameters</h4>
                        </div>

                        @php
                          $postParams = [
                            'post_knowledge_improve' => 'Knowledge Improve',
                            'post_confidence_now' => 'Confidence Now',
                            'post_engagement' => 'Engagement',
                            'post_usefulness' => 'Usefulness',
                            'post_demo_helpfulness' => 'Demo Helpfulness',
                            'post_topic_coverage' => 'Topic Coverage',
                            'post_hands_on_usefulness' => 'Hands-on Usefulness',
                            'post_real_life_use' => 'Real Life Use',
                            'post_trainer_rating' => 'Trainer Rating',
                            'post_interest_increase' => 'Interest Increase',
                            'post_motivation_future' => 'Motivation Future',
                            'post_overall_satisfaction' => 'Overall Satisfaction',
                          ];
                        @endphp

                        @foreach($postParams as $key => $label)
                          <div class="col-md-3 col-sm-6 mb-4">
                            <div class="border rounded p-3 bg-white shadow-sm">
                              <h6 class="text-center mb-2">{{ $label }}</h6>
                              <canvas id="trend_{{ $key }}" height="150"></canvas>
                            </div>
                          </div>
                        @endforeach
                      </div>

                    </div>

                    
                    <hr class="my-5">

                    <h3 class="text-center text-dark">📊 Overall Feedback Statistics</h3>
                    <div class="row mt-4">
                      @foreach($averages as $field => $avg)
                        <div class="col-md-3 mb-3">
                          <div class="border rounded p-3 text-center bg-light">
                            <h6>{{ ucwords(str_replace('_', ' ', $field)) }}</h6>
                            <h4 class="text-primary fw-bold">{{ $avg ?: 0 }} / 5</h4>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <div class="alert alert-info mt-4">No feedback data available for this selection.</div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  </div>
<script></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('comparisonChart').getContext('2d');
    const comparisonLabels = [
      'Technology Known',
      'Confidence',
      'Interest',
      'Usefulness'
    ];

    const preValues = [
    {{ $comparison['Technology Known']['pre'] ?? 0 }},
    {{ $comparison['Confidence']['pre'] ?? 0 }},
    {{ $comparison['Interest']['pre'] ?? 0 }},
      {{ $comparison['Usefulness']['pre'] ?? 0 }}
    ];

    const postValues = [
    {{ $comparison['Technology Known']['post'] ?? 0 }},
    {{ $comparison['Confidence']['post'] ?? 0 }},
    {{ $comparison['Interest']['post'] ?? 0 }},
      {{ $comparison['Usefulness']['post'] ?? 0 }}
    ];

    new Chart(document.getElementById('comparisonChart'), {
      type: 'line',
      data: {
        labels: comparisonLabels,
        datasets: [
          {
            label: 'Pre Feedback',
            data: preValues,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointStyle: 'circle',
            pointRadius: 6,
            pointHoverRadius: 8,
          },
          {
            label: 'Post Feedback',
            data: postValues,
            borderColor: 'rgba(69, 214, 4, 1)',
            backgroundColor: 'rgba(69, 214, 4, 0.2)',
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointStyle: 'triangle',
            pointRadius: 6,
            pointHoverRadius: 8,
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          },
          tooltip: {
            mode: 'index',
            intersect: false,
          },
          datalabels: {
            align: 'top',
            anchor: 'end',
            color: '#000',
            font: {
              weight: 'bold',
              size: 11
            },
            formatter: (value) => value ? value.toFixed(2) : ''
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 5,
            title: {
              display: true,
              text: 'Rating (1–5)'
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
    });


  </script>

  <script>
    document.getElementById('district_id').addEventListener('change', function () {
      const districtId = this.value;
      const schoolSelect = document.getElementById('school_id');

      schoolSelect.innerHTML = '<option value="">Loading...</option>';

      fetch(`/get-schools-by-district-${districtId}`)
        .then(res => res.json())
        .then(data => {
          schoolSelect.innerHTML = '<option value="">Select School</option>';
          data.forEach(school => {
            const option = document.createElement('option');
            option.value = school.scm_id;
            option.text = school.scm_name;
            schoolSelect.appendChild(option);
          });
        });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <script>
    const detailedPostLabels = [
      'Knowledge Improve',
      'Confidence Now',
      'Engagement',
      'Usefulness',
      'Demo Helpfulness',
      'Topic Coverage',
      'Hands-on Usefulness',
      'Real Life Use',
      'Trainer Rating',
      'Overall Satisfaction',
      'Interest Increase',
      'Motivation Future'
    ];

    const detailedPostValues = [
      {{ $averages['post_knowledge_improve'] ?? 0 }},
      {{ $averages['post_confidence_now'] ?? 0 }},
      {{ $averages['post_engagement'] ?? 0 }},
      {{ $averages['post_usefulness'] ?? 0 }},
      {{ $averages['post_demo_helpfulness'] ?? 0 }},
      {{ $averages['post_topic_coverage'] ?? 0 }},
      {{ $averages['post_hands_on_usefulness'] ?? 0 }},
      {{ $averages['post_real_life_use'] ?? 0 }},
      {{ $averages['post_trainer_rating'] ?? 0 }},
      {{ $averages['post_overall_satisfaction'] ?? 0 }},
      {{ $averages['post_interest_increase'] ?? 0 }},
      {{ $averages['post_motivation_future'] ?? 0 }}
    ];

    Chart.register(ChartDataLabels);
    new Chart(document.getElementById('detailedPostChart'), {
      type: 'bar',
      data: {
        labels: detailedPostLabels,
        datasets: [{
          label: 'Post Feedback Detailed Scores',
          data: detailedPostValues,
          backgroundColor: 'rgba(153, 102, 255, 0.6)',
          borderColor: 'rgba(153, 102, 255, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            max: 5,
            title: { display: true, text: 'Rating (1–5)' }
          },
          x: {
            ticks: {
              autoSkip: false,
              maxRotation: 45,
              minRotation: 45
            }
          }
        },
        plugins: {
          legend: { display: false },
          tooltip: { mode: 'index', intersect: false },
          datalabels: {
            anchor: 'end',
            align: 'top',
            color: '#000',
            font: { weight: 'bold' },
            formatter: function(value) {
              return value.toFixed(2);
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  </script>

<script>
  const trendData = @json($trendData);
  const totalStudents = {{ $totalStudents ?? 0 }};
  Object.entries(trendData).forEach(([key, records]) => {
    const ctx = document.getElementById(`trend_${key}`);
    if (ctx && records.length > 0) {
      const labels = records.map(r => r.date);
      const avgRatings = records.map(r => r.avg_rating);
      const responses = records.map(r => r.responses);

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Average Rating',
            data: avgRatings,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            borderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.3,
          }]
        },
        options: {
          responsive: true,
          interaction: { mode: 'index', intersect: false },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                title: function (context) {
                  return `Date: ${context[0].label}`;
                },
                label: function (context) {
                  const avg = context.raw?.toFixed(2) || 0;
                  const count = responses[context.dataIndex];
                  return [`Avg Rating: ${avg}`, `Total Student Responses: ${count}/${totalStudents}`];
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 5,
              ticks: { stepSize: 1 },
              title: { display: true, text: 'Rating (1–5)' }
            },
            x: {
              title: { display: true, text: 'Feedback Date' },
              ticks: {
                autoSkip: true,
                maxTicksLimit: 6
              }
            }
          }
        }
      });
    }
  });
</script>

  {{-- <script>
    // Define parameters for individual bar charts
    const totalStudents = {{ $totalStudents ?? 0 }};
    const postCharts = {
      'post_knowledge_improve': {
        label: 'Student Knowledge Improve',
        value: {{ $averages['post_knowledge_improve'] ?? 0 }},
        count: {{ $counts['post_knowledge_improve'] ?? 0 }}
    },
      'post_confidence_now': {
        label: 'Student Confidence Now',
        value: {{ $averages['post_confidence_now'] ?? 0 }},
        count: {{ $counts['post_confidence_now'] ?? 0 }}
    },
      'post_engagement': {
        label: 'Student Engagement',
        value: {{ $averages['post_engagement'] ?? 0 }},
        count: {{ $counts['post_engagement'] ?? 0 }}
    },
      'post_usefulness': {
        label: 'Student Usefulness',
        value: {{ $averages['post_usefulness'] ?? 0 }},
        count: {{ $counts['post_usefulness'] ?? 0 }}
    },
      'post_demo_helpfulness': {
        label: 'Student Demo Helpfulness',
        value: {{ $averages['post_demo_helpfulness'] ?? 0 }},
        count: {{ $counts['post_demo_helpfulness'] ?? 0 }}
    },
      'post_topic_coverage': {
        label: 'Student Topic Coverage',
        value: {{ $averages['post_topic_coverage'] ?? 0 }},
        count: {{ $counts['post_topic_coverage'] ?? 0 }}
    },
      'post_hands_on_usefulness': {
        label: 'Student Hands-on Usefulness',
        value: {{ $averages['post_hands_on_usefulness'] ?? 0 }},
        count: {{ $counts['post_hands_on_usefulness'] ?? 0 }}
    },
      'post_real_life_use': {
        label: 'Student Real Life Use',
        value: {{ $averages['post_real_life_use'] ?? 0 }},
        count: {{ $counts['post_real_life_use'] ?? 0 }}
    },
      'post_trainer_rating': {
        label: 'Student Trainer Rating',
        value: {{ $averages['post_trainer_rating'] ?? 0 }},
        count: {{ $counts['post_trainer_rating'] ?? 0 }}
    },
      'post_interest_increase': {
        label: 'Student Interest Increase',
        value: {{ $averages['post_interest_increase'] ?? 0 }},
        count: {{ $counts['post_interest_increase'] ?? 0 }}
    },
      'post_motivation_future': {
        label: 'Student Motivation Future',
        value: {{ $averages['post_motivation_future'] ?? 0 }},
        count: {{ $counts['post_motivation_future'] ?? 0 }}
    },
      'post_overall_satisfaction': {
        label: 'Student Overall Satisfaction',
        value: {{ $averages['post_overall_satisfaction'] ?? 0 }},
        count: {{ $counts['post_overall_satisfaction'] ?? 0 }}
    },
    };

    Object.entries(postCharts).forEach(([key, info]) => {
      const ctx = document.getElementById(`chart_${key}`);
      if (ctx) {
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: [info.label],
            datasets: [{
              label: 'Average Rating',
              data: [info.value],
              backgroundColor: 'rgba(255, 159, 64, 0.7)',
              borderColor: 'rgba(255, 159, 64, 1)',
              borderWidth: 1,
              barPercentage: 0.35,      
              categoryPercentage: 0.4,
            }]
          },
          options: {
            responsive: true,
            scales: {
              x: { 
              grid: { display: false }
              },
              y: {
                beginAtZero: true,
                max: 5,
                ticks: { stepSize: 1 },
                title: { display: true, text: 'Rating (1–5)' }
              }
            },
            plugins: {
              legend: { display: false },
              tooltip: {
                enabled: true,
                callbacks: {
                  title: function (context) {
                    return info.label;
                  },
                  label: function (context) {
                    const value = context.raw.toFixed(2) || 0;
                    const count = info.count;
                    return [
                      `Average Rating: ${value}`,
                      `Total Student Responses: ${count}/${totalStudents}`
                    ];
                  }
                }
              }
            }
          }
        });
      }
    });
  </script> --}}



</body>
@include('components.footer')