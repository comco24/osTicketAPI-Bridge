<div class="row">
  <div class="col-12 col-sm-6 offset-sm-3 form_area">
    <div class="row">
      <div class="col-12 text-center">
        <form action="<?php echo DIR_WWW_ROOT ?>act/password-change/" method="POST">
        <?php
        $sec_csrf_token_tag = $sec->csrf_token_tag();
        echo $sec_csrf_token_tag;
        ?>
        <div class="login_window">
          <div class="row">
            <div class="col-12">
              <h1 class="modul mb-5"><?php echo $_SESSION["admin"]["auth"]["email"]; ?> - zmena hesla</h1>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-12 col-md-6 text-left">
              <label for="pwdA">Nové heslo</label>
              <input type="password" class="form-control" id="pwdA" name="pwdA" placeholder="" maxlength="100">
            </div>
            <div class="form-group col-12 col-md-6 text-left">
              <label for="pwdB">Nové heslo (kontrola)</label>
              <input type="password" class="form-control" id="pwdB" name="pwdB" placeholder="" maxlength="100">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-12 text-center lwC">
              <input type="submit" value=" Zmeniť " class="form_button" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
