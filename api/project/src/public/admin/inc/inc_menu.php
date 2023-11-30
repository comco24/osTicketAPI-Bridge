<div class="container">
  <div class="row">
    <div class="col-12 offset-sm-2 col-sm-8 offset-md-3 col-md-6 text-center lwC">
      <?php
      switch ($_SESSION["admin"]["auth"]["id_type"]) {
        case 1: // admin
          include('inc_menu_1.php');
          break;
      }
      ?>
    </div>
  </div>
</div>