<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/dashboard" class="brand-link">
    {{-- <img style="align-items:center; width:20%" src="{{ asset('images\Et.webp') }}" alt="App Logo"></img> --}}
    <img src="{{ asset('images/ocaclogo1.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light">EmergingTech | OCAC</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    @php
      use Illuminate\Support\Facades\Auth;
      $user = Auth::user();
    @endphp
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="{{route('dashboard')}}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              {{-- <i class="right fas fa-angle-left"></i> --}}
            </p>
          </a>
        </li>
        {{-- <li class="nav-item has-treeview {{ request()->is('') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
            <i class="nav-icon fas fa-university "></i>
            <p>
              Institute & Camps
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-list nav-icon"></i>
                <p>Institute List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-graduation-cap nav-icon"></i>
                <p>Completion Certificate</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-film  nav-icon"></i>
                <p>Media</p>
              </a>
            </li>
          </ul>
        </li> --}}

        @if($user->role_id == 1 || $user->role_id == 2 )
        
          {{-- Training Evidences --}}

          <li
            class="nav-item has-treeview {{ request()->routeIs('attendance', 'trainingphotos', 'trainingvideos') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ request()->routeIs('attendance', 'trainingphotos', 'trainingvideos') ? 'active' : '' }}">
              <i class="nav-icon fas fa-folder"></i>
              <p>
                Training Evidences
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('attendance') }}"
                  class="nav-link {{ request()->routeIs('attendance') ? 'active' : '' }}">
                  <i class="fas fa-user-check  nav-icon"></i>
                  <p>Student Attendance</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('trainingphotos')}}"
                  class="nav-link {{ request()->routeIs('trainingphotos') ? 'active' : '' }}">
                  <i class="fas fa-photo-video  nav-icon"></i>
                  <p>Training Photos</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="trainingvideos" class="nav-link {{ request()->routeIs('trainingvideos') ? 'active' : '' }}">
                  <i class="fas fa-video  nav-icon"></i>
                  <p>Training Videos</p>
                </a>
              </li>
            </ul>

          </li>

        @endif

        @if($user->role_id == 1 || $user->role_id == 2 )
          {{-- Feedback --}}

          <li
            class="nav-item has-treeview {{ request()->routeIs('uploadfeedback', 'writtenfeedback', 'onlinefeedback') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ request()->routeIs('uploadfeedback', 'writtenfeedback', 'onlinefeedback') ? 'active' : '' }}">
              <i class="nav-icon fas fa-edit "></i>
              <p>
                Feedback
                <i class="fas fa-angle-left right"></i>
                {{-- <span class="badge badge-info right">6</span> --}}
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('writtenfeedback')}}"
                  class="nav-link {{ request()->routeIs('writtenfeedback') ? 'active' : '' }}">
                  <i class="fas fa-pen  nav-icon"></i>
                  <p>Written Feedback</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('uploadfeedback')}}"
                  class="nav-link {{ request()->routeIs('uploadfeedback') ? 'active' : '' }}">
                  <i class="fas fa-file-video   nav-icon"></i>
                  <p>Video Feedback</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        @if($user->role_id == 1 || $user->role_id == 2 )
          {{-- completion certificate --}}

          <li class="nav-item has-treeview {{ request()->routeIs('trainingcompcertificate') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('trainingcompcertificate') ? 'active' : '' }}">
              <i class="nav-icon fas fa-graduation-cap"></i>
              <p>
                Training Completion
                <i class="fas fa-angle-left right"></i>
                {{-- <span class="badge badge-info right">6</span> --}}
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('trainingcompcertificate')}}"
                  class="nav-link {{ request()->routeIs('trainingcompcertificate') ? 'active' : '' }}">
                  <i class="fas fa-award  nav-icon"></i>
                  <p>Completion Certificate</p>
                </a>
              </li>
            </ul>
          </li>
        @endif


        @if($user->role_id == 3 || $user->role_id == 1 || $user->role_id == 2)
        {{-- coordinator & Trainer --}}
          <li
            class="nav-item has-treeview {{ request()->routeIs('', 'coordinators.index', 'trainers.index', 'supstaff.index') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ request()->routeIs('', 'coordinators.index', 'trainers.index', 'supstaff.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie "></i>
              <p>
                Program Team
                <i class="fas fa-angle-left right"></i>
                {{-- <span class="badge badge-info right">6</span> --}}
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('coordinators.index')}}"
                  class="nav-link {{ request()->routeIs('coordinators.index') ? 'active' : '' }}">
                  <i class="fas fa-users-cog  nav-icon"></i>
                  <p>Coordinator's List</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('trainers.index')}}"
                  class="nav-link {{ request()->routeIs('trainers.index') ? 'active' : '' }}">
                  <i class="fas fa-users  nav-icon"></i>
                  <p>Trainer's List</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('supstaff.index')}}"
                  class="nav-link {{ request()->routeIs('supstaff.index') ? 'active' : '' }}">
                  <i class="fas fa-people-carry  nav-icon"></i>
                  <p>Supporting Staff List</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        @if($user->role_id == 1 || $user->role_id == 2 )
          {{-- Students --}}
          <li
            class="nav-item has-treeview {{ request()->routeIs('addstudent', 'studentlist', 'single.addstudent') ? 'menu-open' : '' }}">
            <a href="#"
              class="nav-link {{ request()->routeIs('addstudent', 'studentlist', 'single.addstudent') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>
                Student
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('addstudent')}}" class="nav-link {{ request()->routeIs('addstudent') ? 'active' : '' }}">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add Student</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('studentlist')}}"
                  class="nav-link {{ request()->routeIs('studentlist') ? 'active' : '' }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Student List</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        @if($user->role_id == 1 || $user->role_id == 2 )
          {{-- Finance & Bills --}}

          <li
            class="nav-item has-treeview {{ request()->routeIs('uploadbills', 'uploadtravelbills', 'uploadexpensebills') ? 'menu-open' : '' }}">
            <a href="{{route('uploadbills')}}"
              class="nav-link {{ request()->routeIs('uploadbills', 'uploadtravelbills', 'uploadexpensebills') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>
                Finance & Bills
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('uploadbills')}}"
                  class="nav-link {{ request()->routeIs('uploadbills') ? 'active' : '' }}">
                  <i class="fas fa-utensils nav-icon"></i>
                  <p>School Food Bills</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('uploadtravelbills')}}"
                  class="nav-link {{ request()->routeIs('uploadtravelbills') ? 'active' : '' }}">
                  <i class="fas fa-suitcase-rolling nav-icon"></i>
                  <p>Travel Bills</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('uploadexpensebills')}}"
                  class="nav-link {{ request()->routeIs('uploadexpensebills') ? 'active' : '' }}">
                  <i class="fas fa-dollar-sign nav-icon"></i>
                  <p>Expenses Bills</p>
                </a>
              </li>
            </ul>
          </li>
        @endif
        {{-- <li class="nav-item has-treeview {{ request()->is('') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('') ? 'active' : '' }}">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Logistic & Inventory
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-boxes nav-icon"></i>
                <p>Inventory List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-tasks nav-icon"></i>
                <p>Assign/Dispatch</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-map-marker nav-icon"></i>
                <p>Return Tracking</p>
              </a>
            </li>
          </ul>
        </li> --}}

        {{-- <li class="nav-item has-treeview {{ request()->routeIs('uploadreport') ? 'menu-open' : '' }}">
          <a href="{{route('uploadreport')}}" class="nav-link {{ request()->routeIs('uploadreport') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>
              Reports
            </p>
          </a>
        </li> --}}

        @if($user->role_id == 1 || $user->role_id == 2 || $user->role_id == 3 || $user->role_id == 4 || $user->role_id == 5 || $user->role_id == 6)
          {{-- User Management --}}
          <li class="nav-item has-treeview {{ request()->routeIs('register', 'profile.edit') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->routeIs('register', 'profile.edit') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ">
              @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                <li class="nav-item">
                  <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>User Creation</p>
                  </a>
                </li>
              @endif
              <li class="nav-item">
                <a href="{{route('profile.edit')}}"
                  class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                  <i class="fas fa-address-card nav-icon"></i>
                  <p>Profile</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link ">
                  <i class="fas fa-sign-out-alt nav-icon"></i>
                  <p>Logout</p>
                </a>
              </li>
            </ul>
          </li>
        @endif
        
      </ul>
    </nav>
    <!-- /.sidebar menu -->
  </div>
  <!-- /.sidebar -->
</aside>
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>