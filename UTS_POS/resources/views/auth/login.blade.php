<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | autoMobilPOS</title>
  <link rel="icon" href="{{ asset('autologo-circle.jpg') }}" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #e74c3c;
      --accent-color: #3498db;
    }
    
    body {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                  url('https://images.unsplash.com/photo-1494976388531-d1058494cdd8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
    }
    
    .login-page {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .login-box {
      width: 400px;
      margin: 0 auto;
    }
    
    .login-logo {
      margin-bottom: 2rem;
    }
    
    .login-logo a {
      color: white;
      font-size: 2.5rem;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .login-logo a b {
      color: var(--secondary-color);
    }
    
    .login-card {
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
      border: none;
      overflow: hidden;
    }
    
    .card-header {
      background-color: var(--primary-color);
      color: white;
      text-align: center;
      padding: 1.5rem;
      border-bottom: 3px solid var(--secondary-color);
    }
    
    .card-body {
      padding: 2rem;
      background-color: rgba(255, 255, 255, 0.95);
    }
    
    .login-box-msg {
      color: var(--primary-color);
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 500;
    }
    
    .input-group-text {
      background-color: var(--primary-color);
      color: white;
      border: none;
    }
    
    .form-control {
      border-left: none;
      padding-left: 0;
    }
    
    .form-control:focus {
      box-shadow: none;
      border-color: #ced4da;
    }
    
    .btn-login {
      background-color: var(--secondary-color);
      border: none;
      padding: 0.75rem;
      font-weight: 600;
      transition: all 0.3s;
      width: 100%;
    }
    
    .btn-login:hover {
      background-color: #c0392b;
      transform: translateY(-2px);
    }
    
    .footer-links {
      text-align: center;
      margin-top: 1.5rem;
      color: #6c757d;
    }
    
    .footer-links a {
      color: var(--accent-color);
      text-decoration: none;
    }
    
    .footer-links a:hover {
      text-decoration: underline;
    }
    
    .password-toggle {
      cursor: pointer;
      color: var(--primary-color);
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{ url('/') }}"><b>auto</b>Mobil<span style="color: var(--accent-color);">POS</span></a>
  </div>
  
  <div class="card login-card">
    <div class="card-header">
      <h3 class="m-0">Dealer Management System</h3>
    </div>
    
    <div class="card-body">
      <p class="login-box-msg">
        <i class="fas fa-car-side mr-2"></i>Access your dealership dashboard
      </p>
      
      <form action="{{ route('login') }}" method="POST" id="form-login">
        @csrf
        <div class="input-group mb-4">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="fas fa-user"></i>
            </div>
          </div>
          <input type="text" id="username" name="username" class="form-control" placeholder="Username">
          <small id="error-username" class="error-text text-danger w-100"></small>
        </div>
        
        <div class="input-group mb-4">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="fas fa-lock"></i>
            </div>
          </div>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <span class="input-group-text password-toggle" id="togglePassword">
              <i style="color: white" class="fas fa-eye"></i>
            </span>
          </div>
          <small id="error-password" class="error-text text-danger w-100"></small>
        </div>
        
        <div class="row mb-3">
          <div class="col-12">
            <button type="submit" class="btn btn-login btn-block">
              <i class="fas fa-sign-in-alt mr-2"></i> SIGN IN
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  <div class="text-center mt-3" style="color: white;">
    <small>&copy; {{ date('Y') }} autoMobilPOS. All rights reserved.</small>
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function(){
      const password = $('#password');
      const type = password.attr('type') === 'password' ? 'text' : 'password';
      password.attr('type', type);
      $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
    
    // Form validation
    $("#form-login").validate({
      rules: {
        username: {required: true, minlength: 4, maxlength: 20},
        password: {required: true, minlength: 5, maxlength: 20}
      },
      messages: {
        username: {
          required: "Please enter your username",
          minlength: "Username must be at least 4 characters"
        },
        password: {
          required: "Please enter your password",
          minlength: "Password must be at least 5 characters"
        }
      },
      submitHandler: function(form) {
        $.ajax({
          url: form.action,
          type: form.method,
          data: $(form).serialize(),
          success: function(response) {
            if(response.status) {
              $('#myModal').modal('hide');
              Swal.fire({
                  icon: 'success',
                  title: 'Welcome!',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500
              }).then(function() {
                  window.location = response.redirect;
              });
            } else {
              $('.error-text').text('');
              $.each(response.msgField, function(prefix, val) {
                  $('#error-'+prefix).text(val[0]);
              });
              Swal.fire({
                  icon: 'error',
                  title: 'Login Failed',
                  text: response.message
              });
            }
          },
          error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: 'Unable to connect to server'
            });
          }
        });
        return false;
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.input-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });
</script>
</body>
</html>