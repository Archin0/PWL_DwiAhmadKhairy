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
</head> 
<body class="hold-transition login-page"> 
<div class="login-box"> 
  <!-- /.login-logo --> 
  <div class="login-logo">
    <a href="{{ url('/') }}"><b>autoMobil</b>POS</a>
  </div>
  <div class="card card-outline card-primary"> 
    {{-- <div class="card-header text-center"><a href="{{ url('/') }}" class="h1"><b>autoMobil</b>POS</a></div>  --}}
    <div class="card-body"> 
      <p class="login-box-msg">Sign in to start your session</p> 
      <form action="{{ route('login') }}" method="POST" id="form-login"> 
        @csrf 
        <div class="input-group mb-3"> 
          <input type="text" id="username" name="username" class="form-control" placeholder="Username"> 
          <div class="input-group-append"> 
            <div class="input-group-text"> 
              <span class="fas fa-envelope"></span> 
            </div> 
          </div> 
          <small id="error-username" class="error-text text-danger"></small> 
        </div> 
        <div class="input-group mb-3"> 
          <input type="password" id="password" name="password" class="form-control" placeholder="Password"> 
          <div class="input-group-append"> 
            <div class="input-group-text"> 
              <span class="fas fa-lock"></span> 
            </div> 
          </div> 
          <small id="error-password" class="error-text text-danger"></small> 
        </div> 
        <div class="row"> 
          <!-- /.col --> 
          <div class="col-4"> 
            <button type="submit" class="btn btn-primary btn-block">Sign in</button> 
          </div> 
          <!-- /.col --> 
        </div> 
      </form> 
      {{-- <p class="mt-3 mb-auto">Belum punya akun? <a href="{{ route('register') }}" class="text-center">Daftar</a></p> --}}
    </div> 
    <!-- /.card-body --> 
  </div> 
  <!-- /.card --> 
</div> 
<!-- /.login-box --> 
 
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
    $("#form-login").validate({ 
      rules: { 
        username: {required: true, minlength: 4, maxlength: 20}, 
        password: {required: true, minlength: 5, maxlength: 20} 
      }, 
      submitHandler: function(form) { // ketika valid, maka bagian yg akan dijalankan 
        $.ajax({ 
          url: form.action, 
          type: form.method, 
          data: $(form).serialize(), 
          success: function(response) { 
            if(response.status){ // jika sukses 
              $('#myModal').modal('hide');
              Swal.fire({ 
                  icon: 'success', 
                  title: 'Berhasil', 
                  text: response.message, 
              }).then(function() { 
                  window.location = response.redirect; 
              }); 
            }else{ // jika error 
              $('.error-text').text(''); 
              $.each(response.msgField, function(prefix, val) { 
                  $('#error-'+prefix).text(val[0]); 
              }); 
              Swal.fire({ 
                  icon: 'error', 
                  title: 'Terjadi Kesalahan', 
                  text: response.message 
              }); 
            } 
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