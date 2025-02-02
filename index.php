<!DOCTYPE html>
<html lang="es">

<?php session_start(); ?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <?php
  if (!isset($_SESSION['login_id']))
    header('location:login.php');
  include('./header.php');
  ?>

  <style>
    /* Estilos personalizados */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: #333;
    }

    #view-panel {
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }

    .navbar,
    .topbar {
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }

    .modal-content {
      border-radius: 10px;
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      background-color: #007bff;
      color: #fff;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .modal-title {
      font-weight: 600;
    }

    .btn-close {
      filter: invert(1);
    }

    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.8);
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #preloader::after {
      content: "";
      width: 50px;
      height: 50px;
      border: 5px solid #007bff;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    .back-to-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      display: none;
      background-color: #007bff;
      color: #fff;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      text-align: center;
      line-height: 40px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s ease;
    }

    .back-to-top:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <?php include 'topbar.php' ?>
  <?php include 'navbar.php' ?>

  <!-- Toast para alertas -->
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white"></div>
  </div>

  <!-- Contenido principal -->
  <main id="view-panel">
    <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
    <?php include $page . '.php' ?>
  </main>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Botón para volver arriba -->
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Modales -->
  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmación</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="delete_content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='confirm' onclick="">Continuar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Guardar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
        <img src="" alt="">
      </div>
    </div>
  </div>

  <script>
    // Funciones JavaScript
    window.start_load = function() {
      $('#preloader').fadeIn();
    }
    window.end_load = function() {
      $('#preloader').fadeOut();
    }
    window.viewer_modal = function($src = '') {
      start_load();
      var t = $src.split('.');
      t = t[1];
      if (t == 'mp4') {
        var view = $("<video src='" + $src + "' controls autoplay></video>");
      } else {
        var view = $("<img src='" + $src + "' />");
      }
      $('#viewer_modal .modal-content video, #viewer_modal .modal-content img').remove();
      $('#viewer_modal .modal-content').append(view);
      $('#viewer_modal').modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
        focus: true
      });
      end_load();
    }
    window.uni_modal = function($title = '', $url = '', $size = "") {
      start_load();
      $.ajax({
        url: $url,
        error: err => {
          console.log(err);
          alert("Ocurrió un error");
        },
        success: function(resp) {
          if (resp) {
            $('#uni_modal .modal-title').html($title);
            $('#uni_modal .modal-body').html(resp);
            if ($size != '') {
              $('#uni_modal .modal-dialog').addClass($size);
            } else {
              $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md");
            }
            $('#uni_modal').modal({
              show: true,
              backdrop: 'static',
              keyboard: false,
              focus: true
            });
            end_load();
          }
        }
      });
    }
    window._conf = function($msg = '', $func = '', $params = []) {
      $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
      $('#confirm_modal .modal-body').html($msg);
      $('#confirm_modal').modal('show');
    }
    window.alert_toast = function($msg = 'TEST', $bg = 'success') {
      $('#alert_toast').removeClass('bg-success bg-danger bg-info bg-warning').addClass('bg-' + $bg);
      $('#alert_toast .toast-body').html($msg);
      $('#alert_toast').toast({
        delay: 3000
      }).toast('show');
    }
    $(document).ready(function() {
      $('#preloader').fadeOut();
      $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
          $('.back-to-top').fadeIn();
        } else {
          $('.back-to-top').fadeOut();
        }
      });
      $('.back-to-top').click(function() {
        $('html, body').animate({
          scrollTop: 0
        }, 500);
        return false;
      });
    });
  </script>

  <?php include('./footer.php'); ?>
</body>

</html>