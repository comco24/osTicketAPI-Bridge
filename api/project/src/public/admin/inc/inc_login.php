<div class="row">
  <div class="col-12 offset-sm-2 col-sm-8 offset-md-3 col-md-6 text-center lwC">
    <img src="<?php echo LOGO_MAIN; ?>" alt="<?php echo PROJECT_TITLE; ?>" />
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-12 offset-sm-2 col-sm-8 offset-md-3 col-md-6 form_area" id="login-form">
      <div class="row">
        <div class="col-12 text-center">
          <form action="<?php echo DIR_WWW_ROOT ?>act/login/" method="POST">
          <?php
          $sec_csrf_token_tag = $sec->csrf_token_tag();
          echo $sec_csrf_token_tag;
          ?>
          <div class="login_window">
            <div class="row">
              <div class="col-12 text-center py-3">
                <h2>Prihlásenie</h2>
              </div>
            </div>
            <div class="row vertical-align py-1">
              <div class="col-4 text-right">Užívateľ:</div>
              <div class="col-8"><input type="text" name="user" /></div>
            </div>
            <div class="row vertical-align py-1">
              <div class="col-4 text-right">Heslo:</div>
              <div class="col-8"><input type="password" name="pwd" /></div>
            </div>
            <div class="row">
              <div class="col-12 text-center lwC">
                <input type="submit" value=" Prihlásiť " class="form_button" />
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>