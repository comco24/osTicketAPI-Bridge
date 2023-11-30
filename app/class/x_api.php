<?php
class x_api
{
  private $x_api_s_key;
  private $x_api_app_id;
  private $x_api_url;
  private $x_api_public_key;

  public function __construct($api_s_key, $api_app_id, $api_url)
  {
    $this->x_api_s_key = $api_s_key;
    $this->x_api_app_id = $api_app_id;
    $this->x_api_url = $api_url;
  }

  /* #region  "getOSTData()" */
  public function getOSTData($id)
  {
    $hash = $this->getHash();

    $data = array('b' => X_API_APP_ID, 'c' => $this->x_api_public_key, 'd' => $hash);

    $ch = curl_init($this->x_api_url . 'hi');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $res_array = json_decode($result, true);
    if ($res_array["result"] == 1) {
      // vytvorime poziadavku na formular:
      $access_id = $res_array["access_id"];
      $data = array('b' => X_API_APP_ID, 'e' => $access_id, 'a' => 'get_form', 'r' => $id);

      $ch = curl_init($this->x_api_url . 'process');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $result = curl_exec($ch);
      return $result;
    }
  }
  /* #endregion */

  /* #region  "getOSTFaqsData()" */
  public function getOSTFaqsData($topic_id = 0)
  {
    $hash = $this->getHash();

    $data = array('b' => X_API_APP_ID, 'c' => $this->x_api_public_key, 'd' => $hash);

    $ch = curl_init($this->x_api_url . 'hi');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $res_array = json_decode($result, true);
    
    if ($res_array["result"] == 1) {
      // vytvorime poziadavku na formular:
      $access_id = $res_array["access_id"];
      $data = array('b' => X_API_APP_ID, 'e' => $access_id, 'a' => 'get_faqs', 'id' => $topic_id);

      $ch = curl_init($this->x_api_url . 'process');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $result = curl_exec($ch);
      return $result;
    }
  }
  /* #endregion */

  /* #region  "getOSTFaqsData()" */
  public function getOSTFaqsDataCategories()
  {
    $hash = $this->getHash();

    $data = array('b' => X_API_APP_ID, 'c' => $this->x_api_public_key, 'd' => $hash);

    $ch = curl_init($this->x_api_url . 'hi');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $res_array = json_decode($result, true);
    
    if ($res_array["result"] == 1) {
      // vytvorime poziadavku na formular:
      $access_id = $res_array["access_id"];
      $data = array('b' => X_API_APP_ID, 'e' => $access_id, 'a' => 'get_faqs_categories', 'id' => $topic_id);

      $ch = curl_init($this->x_api_url . 'process');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $result = curl_exec($ch);
      return $result;
    }
  }
  /* #endregion */
  
  /* #region  "getOSTFaqData()" */
  public function getOSTFaqData($faq_id)
  {
    $hash = $this->getHash();

    $data = array('b' => X_API_APP_ID, 'c' => $this->x_api_public_key, 'd' => $hash);

    $ch = curl_init($this->x_api_url . 'hi');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $res_array = json_decode($result, true);
    if ($res_array["result"] == 1) {
      // vytvorime poziadavku na formular:
      $access_id = $res_array["access_id"];
      $data = array('b' => X_API_APP_ID, 'e' => $access_id, 'a' => 'get_faq', 'r' => $faq_id);

      $ch = curl_init($this->x_api_url . 'process');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      $result = curl_exec($ch);
      return $result;
    }
  }
  /* #endregion */


  private function getHash()
  {
    $this->x_api_public_key = $this->getRandomString(32);
    return hash('sha256', $this->x_api_s_key . $this->x_api_app_id . $this->x_api_public_key);
  }

  private function getRandomString($length = 8)
  {
    $characters = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
      $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
  }

  public function generateForm($topic_data, $form_type, $r_url)
  {
    switch ($form_type) {
      case 1:
        return $this->generateForm1($topic_data, $r_url);
        break;
      case 2:
        return $this->generateForm2($topic_data, $r_url);
        break;
    }
  }


  // generateForm1() - formular pre bootstrap 4
  /* #region  "generateForm1" */
  private function generateForm1($topic_data, $r_url)
  {
    $cache = '';
    $td_arr = json_decode($topic_data, true);
    $cache = '<div class="container"><div class="row">
      <div class="col-12">
        <form id="form_' . $td_arr["topic"]["topic_id"] . '" action="' . $this->x_api_url . 'ticket/" method="post" enctype="multipart/form-data">
          <input type="hidden" name="topic_id" value="' . $td_arr["topic"]["topic_id"] . '">
          <input type="hidden" name="return_url" value="' . $r_url . '">
          <div class="row">
            <div class="col-lg-6 offset-lg-3 pt-3">
              <h2 class="t-700 t-lora t-blue t-l">' . $td_arr["topic"]["topic_name"] . '</h2>' . $td_arr["topic"]["topic_notes"] . '
              <div id="form_error_' . $td_arr["topic"]["topic_id"] . '" class="form_msg"></div>
            </div>
          </div>';
    if (is_array($td_arr["topic"]["forms"]))
    foreach ($td_arr["topic"]["forms"] as $form) {
      $show_head = false;
      $cache_form_head = '';
      /*
      if (strlen(trim($form["form_title"])) > 0) {
        $label = '';
        $input = '';
        $cache_form_head = '<div class="row">
              <div class="col-lg-6 offset-lg-3">
              <h3 class="t-lora t-s t-700 t-blue text-left mt-5">' . $form["form_title"] . '</h3>
              </div>
              </div>';
      }
      */
      /* #region  "fields" */
      $file_fields = array();
      foreach ($form["fields"] as $field) {
        $show_head = true;
        if ($field["field_type"] == "files") {
          $file_fields[] = $field["field_name"];
        } else {
          $fields[] = $field["field_name"];
        }

        if ($field["field_validator"] == null) {
          $validator = "-";
        } else {
          $validator = $field["field_validator"];
        }

        if ($field["field_regex"] == null) {
          $regex = "-";
        } else {
          $regex = str_replace("/iu", "", $field["field_regex"]);
          $regex = str_replace("/", "", $regex);
        }

        if ($field["field_required"] == false) {
          $required = 0;
        } else {
          $required = 1;
        }

        $break = false;
        switch ($field["field_type"]) {
          case "text":
            $label = $field["field_label"];
            if ($field["field_length"] > 0) {
              $max_length = ' maxlength="' . $field["field_length"] . '"';
            } else {
              $max_length = '';
            }
            $type = 'text';
            #if ($field["field_name"] == 'email') $type = 'email';
            
            $input = '<input type="'. $type .'" id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '"' . $max_length . ' data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control"/>';
            break;
          case "files":
            $label = $field["field_label"];
            $input = '<input type="file" id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control" />';
            break;
          case "memo":
            $label = $field["field_label"];
            if ($field["field_length"] > 0) {
              $max_length = ' maxlength="' . $field["field_length"] . '"';
            } else {
              $max_length = '';
            }
            $input = '<textarea id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '"' . $max_length . ' data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control"></textarea>';
            break;
          case "thread":
            $label = $field["field_label"];
            if ($field["field_length"] > 0) {
              $max_length = ' maxlength="' . $field["field_length"] . '"';
            } else {
              $max_length = '';
            }
            $input = '<textarea id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '"' . $max_length . ' data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control"></textarea>';
            break;
          case "choices":
            $label = $field["field_label"];
            $option = '';
            foreach ($field["list"] as $list) {
              $option .= '<option value="' . $list["id"] . '">' . $list["value"] . '</option>';
            }
            $input = '<select id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control">' . $option . '</select>';
            break;
          case "bool":
            $label = $field["field_label"];
            $desc = '';
            if (isset($field["desc"]) && strlen(trim($field["desc"])) > 0) {
              $desc = $field["desc"];
            }
            $input = '<input id="form_input_'. $field['field_id'] .' type="checkbox" name="' . $field["field_name"] . '" value="1" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control" /> ' . $desc;
            break;
          case "datetime":
            $label = $field["field_label"];

            $option = '';
            for ($i = 1; $i <= 31; $i++) {
              $option .= '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . $i . '</option>';
            }
            $fields[] = $field["field_name"] . '_den';
            $den = '<select id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '_den" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control">' . $option . '</select>';
            $option = '';
            for ($i = 1; $i <= 12; $i++) {
              $option .= '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . $i . '</option>';
            }
            $fields[] = $field["field_name"] . '_mesiac';
            $mesiac = '<select id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '_mesiac" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control">' . $option . '</select>';
            $option = '';
            for ($i = date("Y"); $i <= (date("Y") + 10); $i++) {
              $option .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $fields[] = $field["field_name"] . '_rok';
            $rok = '<select id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '_rok" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control">' . $option . '</select>';
            $input = '<table class="input"><tr><td>deň: ' . $den . '</td><td>mesiac: ' . $mesiac . '</td><td>rok: ' . $rok . '</td></tr></table>';
            break;
          case "break":
            $break = true;
            $label = $field["field_label"];
            break;
          default:
            $type = explode("-", $field["field_type"]);
            switch ($type[0]) {
              case "list":
                $label = $field["field_label"];
                $option = '';
                foreach ($field["list"] as $list) {
                  $option .= '<option value="' . $list["id"] . '">' . $list["value"] . '</option>';
                }
                $input = '<select id="form_input_'. $field['field_id'] .'" name="' . $field["field_name"] . '" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-control">' . $option . '</select>';
            }
            break;
        }
        if ($break === true) {
          $cache .= '<div class="row"><div class="col-lg-6 offset-lg-3 mt-5 pt-3 line-break">
          <h4 class="text-center t-green">' . $label . '</h4>
        </div></div>';
        } else {
          $cache .= '<div class="row"><div class="col-lg-6 offset-lg-3">
          <label for="form_input_'. $field['field_id'] .'">' . $label . '</label> <span id="field_' . $field["field_id"] . '" class="err-msg"></span>
          ' . $input . '
        </div></div><br>';
        }
      }
      if ($show_head == true) {
        $cache = $cache_form_head . $cache;
      }
      /* #endregion */
    }
    $jsn = json_encode($fields);
    if ((is_array($file_fields)) and (count($file_fields) > 0)) {
      $jsn_files = json_encode($file_fields);
    } else {
      $jsn_files = '';
    }

    $cache .= '<div class="row">
            <div class="col-lg-6 offset-lg-3 my-5 text-center">
            <input type="hidden" name="form_fields" value=\'' . $jsn . '\'>
            <input type="hidden" name="file_fields" value=\'' . $jsn_files . '\'>
            <button type="submit" id="btn-action-'. $td_arr["topic"]["topic_id"] .'" class="btn btn-primary btn-block">Odoslať</button>
            </div>
          </div>
          </div>
          </div>
        </form>
        <style>
        	.form_error {
        		background-color: #ffe0de;
        	}
        	.err-msg {
        		color: red;
        		font-weight: bold;
        		margin-left: 10px;
        	}
        	textarea.form-control {
        		height: 100px;
        	}
        	.form_msg {
        		display: none;
        		margin-bottom: 15px;
        		padding: 3px 6px;
        		color: white;
        		font-weight: bold;
        		font-size: 1.1em;
        	}
        	.form_msg.success {
        		background-color: green;
        	}
        	.form_msg.error {
        		background-color: red;
        	}
        </style>
        <script>
$(document).ready(function () {
  $(document).on("click", "#btn-action-'. $td_arr["topic"]["topic_id"] .'", function (event) {
    event.preventDefault();

    var val = "";
    var err = false;
    var reg = null;
    $("[id^=field_]").html("");
    
    $(".form-control").each(function(i, obj) {
	  $(this).removeClass("form_error");
      switch($(this).data("type")) {
        case "text":
        case "thread":
        if ($(this).data("required")==1) {
          val = $.trim($(this).val());
          if (val=="") {
            err = true;
            $("#field_" + $(this).data("id")).html("Toto pole je povinné!");
			$(this).addClass("form_error");
          } else {
            if ($(this).data("validator")=="regex") {
              var pattern = new RegExp($(this).data("regex"));
              if (!pattern.test(val)) {
                err = true;
                $("#field_" + $(this).data("id")).html("Zadaná hodnota nie je platná! (" + $(this).data("regex") + ")");
              }
            }
          }
        }
        break;
      }
      if (($(this).attr("name") == "email") && $(this).val() != "") {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var email = $(this).val();
		if (!regex.test(email)) {
			err = true;
            $("#field_" + $(this).data("id")).html("Chybne zadaný email");
			$(this).addClass("form_error");
		}
      }
  });

  if (err==false) {
  	var url = "' . $this->x_api_url . 'ticket/";
  	var data = $( "#form_" + "' . $td_arr["topic"]["topic_id"] . '").serialize();
  	var form_pointer = $( "#form_" + "' . $td_arr["topic"]["topic_id"] . '" );
  	var form_error_pointer = $( "#form_error_" + "' . $td_arr["topic"]["topic_id"] . '" );
  	
	$("#btn-action-'. $td_arr["topic"]["topic_id"] .'").prop("disabled", true);
  	$.ajax({
  		url: url, 
  		data: data,
  		method: "POST",
  		success: function(result){
			form_error_pointer.show().addClass("success").removeClass("error").html("Formulár bol odoslaný").delay(4000).fadeOut();				
		  },
		error: function(result) {
			form_error_pointer.show().addClass("error").removeClass("success").html("Pri spracovaní sa vyskytla chyba. Skúste to znovu.").delay(4000).fadeOut();				
			},
		complete: function(result) {
			$("#btn-action-'. $td_arr["topic"]["topic_id"] .'").prop("disabled", false);
			form_pointer.trigger("reset");
		},
  		});
  
  }

  });
});
        </script>

      </div>
    </div';
    return $cache;
  }
  /* #endregion */


  // generateForm2() - formular (s inline style definiciami)
  /* #region  "generateForm1" */
  private function generateForm2($topic_data, $r_url)
  {
    $cache = '';
    $td_arr = json_decode($topic_data, true);
    $cache = '<div style="width:100%">
      <div style="width:100%">
        <form id="form_' . $td_arr["topic"]["topic_id"] . '" action="' . $this->x_api_url . 'ticket/" method="post" enctype="multipart/form-data">
          <input type="hidden" name="topic_id" value="' . $td_arr["topic"]["topic_id"] . '">
          <input type="hidden" name="return_url" value="' . $r_url . '">
          <div style="width:100%">
            <div style="display:inline-block; width:100%; padding-top: 15px;">
              <h1 style="font-weight: bold; font-size: 22px;">' . $td_arr["topic"]["topic_name"] . '</h1>' . $td_arr["topic"]["topic_notes"] . '
            </div>
          </div>';
    foreach ($td_arr["topic"]["forms"] as $form) {
      $show_head = false;
      $cache_form_head = '';
      if (strlen(trim($form["form_title"])) > 0) {
        $label = '';
        $input = '';
        $cache_form_head = '<div style="width:100%;">
              <div style="display:inline-block; width:50%;">
              <h3 style="font-weight: bold; font-size: 18px; margin-top: 15px;">' . $form["form_title"] . '</h3>
              </div>
              </div>';
      }
      /* #region  "fields" */
      foreach ($form["fields"] as $field) {
        $show_head = true;
        if ($field["field_type"] == "files") {
          $file_fields[] = $field["field_name"];
        } else {
          $fields[] = $field["field_name"];
        }

        if ($field["field_validator"] == null) {
          $validator = "-";
        } else {
          $validator = $field["field_validator"];
        }

        if ($field["field_regex"] == null) {
          $regex = "-";
        } else {
          $regex = str_replace("/iu", "", $field["field_regex"]);
          $regex = str_replace("/", "", $regex);
        }

        if ($field["field_required"] == false) {
          $required = 0;
        } else {
          $required = 1;
        }

        $break = false;
        switch ($field["field_type"]) {
          case "text":
            $label = $field["field_label"];
            if ($field["field_length"] > 0) {
              $max_length = ' maxlength="' . $field["field_length"] . '"';
            } else {
              $max_length = '';
            }
            $input = '<input type="text" name="' . $field["field_name"] . '"' . $max_length . ' data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field" />';
            break;
          case "files":
            $label = $field["field_label"];
            $input = '<input type="file" name="' . $field["field_name"] . '" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field" />';
            break;
          case "memo":
            $label = $field["field_label"];
            if ($field["field_length"] > 0) {
              $max_length = ' maxlength="' . $field["field_length"] . '"';
            } else {
              $max_length = '';
            }
            $input = '<textarea name="' . $field["field_name"] . '"' . $max_length . ' data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field"></textarea>';
            break;
          case "thread":
            $label = $field["field_label"];
            if ($field["field_length"] > 0) {
              $max_length = ' maxlength="' . $field["field_length"] . '"';
            } else {
              $max_length = '';
            }
            $input = '<textarea name="' . $field["field_name"] . '"' . $max_length . ' data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field"></textarea>';
            break;
          case "choices":
            $label = $field["field_label"];
            $option = '';
            foreach ($field["list"] as $list) {
              $option .= '<option value="' . $list["id"] . '">' . $list["value"] . '</option>';
            }
            $input = '<select name="' . $field["field_name"] . '" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field">' . $option . '</select>';
            break;
          case "bool":
            $label = $field["field_label"];
            $desc = '';
            if (isset($field["desc"]) && strlen(trim($field["desc"])) > 0) {
              $desc = $field["desc"];
            }
            $input = '<input type="checkbox" name="' . $field["field_name"] . '" value="1" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field" /> ' . $desc;
            break;
          case "datetime":
            $label = $field["field_label"];

            $option = '';
            for ($i = 1; $i <= 31; $i++) {
              $option .= '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . $i . '</option>';
            }
            $fields[] = $field["field_name"] . '_den';
            $den = '<select name="' . $field["field_name"] . '_den" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field">' . $option . '</select>';
            $option = '';
            for ($i = 1; $i <= 12; $i++) {
              $option .= '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . $i . '</option>';
            }
            $fields[] = $field["field_name"] . '_mesiac';
            $mesiac = '<select name="' . $field["field_name"] . '_mesiac" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field">' . $option . '</select>';
            $option = '';
            for ($i = date("Y"); $i <= (date("Y") + 10); $i++) {
              $option .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $fields[] = $field["field_name"] . '_rok';
            $rok = '<select name="' . $field["field_name"] . '_rok" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field">' . $option . '</select>';
            $input = '<table class="input"><tr><td>deň: ' . $den . '</td><td>mesiac: ' . $mesiac . '</td><td>rok: ' . $rok . '</td></tr></table>';
            break;
          case "break":
            $break = true;
            $label = $field["field_label"];
            break;
          default:
            $type = explode("-", $field["field_type"]);
            switch ($type[0]) {
              case "list":
                $label = $field["field_label"];
                $option = '';
                foreach ($field["list"] as $list) {
                  $option .= '<option value="' . $list["id"] . '">' . $list["value"] . '</option>';
                }
                $input = '<select name="' . $field["field_name"] . '" data-id="' . $field["field_id"] . '" data-validator="' . $validator . '" data-regex="' . $regex . '" data-required="' . $required . '" data-type="' . $field["field_type"] . '" class="form-field">' . $option . '</select>';
            }
            break;
        }
        if ($break === true) {
          $cache .= '<div style="width:100%;"><div style="width:100%;">
          <h4 style="font-weight:bold;font-size:16px;">' . $label . '</h4>
        </div></div>';
        } else {
          $cache .= '<div style="width:100%;"><div style="width:100%;">
          <label style="width:100%;" for="">' . $label . '</label>
          ' . $input . '
        <div id="field_' . $field["field_id"] . '" style="width:100%;color:red;"></div></div></div>';
        }
      }
      if ($show_head == true) {
        $cache = $cache_form_head . $cache;
      }
      /* #endregion */
    }
    $jsn = json_encode($fields);
    $jsn_files = json_encode($file_fields);
    $cache .= '<div style="width:100%;">
            <div style="width:100%; text-align:left; padding-top:15px; padding-bottom:15px;">
            <input type="hidden" name="form_fields" value=\'' . $jsn . '\'">
            <input type="hidden" name="file_fields" value=\'' . $jsn_files . '\'">
            <button type="submit" id="btn-action">Odoslať</button>
            </div>
          </div>
        </form>
        <script>
$j(document).ready(function () {
  $j("#btn-action").click(function (event) {
    event.preventDefault();

    var val = "";
    var err = false;
    var reg = null;
    $j(".form-field").each(function(i, obj) {
      switch($j(this).data("type")) {
        case "text":
        if ($j(this).data("required")==1) {
          val = $j.trim($j(this).val());
          if (val=="") {
            err = true;
            $j("#field_" + $j(this).data("id")).html("Toto pole je povinné!");
          } else {
            if ($j(this).data("validator")=="regex") {
              var pattern = new RegExp($j(this).data("regex"));
              if (!pattern.test(val)) {
                err = true;
                $j("#field_" + $j(this).data("id")).html("Zadaná hodnota nie je platná! (" + $j(this).data("regex") + ")");
              }
            }
          }
        }
        break;
      }
  });

  if (err==false) {
    $j( "#form_" + "' . $td_arr["topic"]["topic_id"] . '" ).submit();
  }

  });
});
        </script>
      </div>
    </div>';
    return $cache;
  }
  /* #endregion */

  public function generateFaqsCategory($faqs_data, $faq_type, $url)
  {
    switch ($faq_type) {
      case 1:
        return $this->generateFaqsCategory1($faqs_data, $url);
        break;
    }
  }

  private function generateFaqsCategory1($faqs_data, $url)
  {
    $cache = '';
    $fd_arr = json_decode($faqs_data, true);
    $cache = '<div class="row mt-5">';
    foreach ($fd_arr["category"] as $category) {
      $c_category = '<div class="col-md-6">';
      $c_category .= '<h2 class="t-lora t-green t-l t-700">' . $category["category_name"] . '</h2>';

      $c_category .= '<p class="t-green">' . $category["category_description"] . '</p>';
      foreach ($category["faqs"] as $faqs) {
        $c_faqs = '<h3 class="t-lora t-blue t-m">' . $faqs["question"] . '</h3>';

        $tmp_popis = substr($faqs["answer"], 0, 200);
        $popis_arr = explode(' ', $tmp_popis);
        $popis = '';
        for ($i = 0; $i < 7; $i++) {
          if ($popis_arr[$i] == '.' || $popis_arr[$i] == ',' || $popis_arr[$i] == '?' || $popis_arr[$i] == '!' || $popis_arr[$i] == ':' || $popis_arr[$i] == ';') {
            $popis .= $popis_arr[$i];
          } else {
            $popis .= ' ' . $popis_arr[$i];
          }
        }
        $popis = substr($popis, 1);
        $c_faqs .= '<p><a href="' . $url . $faqs["id"] . '.html" class="a_viac">' . $popis . ' ...</a></p>';
        $c_category .= $c_faqs;
      }
      $c_category .= '</div>';
      $cache .= $c_category;
    }
    $cache .= '</div>';
    return $cache;
  }

  public function generateFaq($faq_data, $faq_type)
  {
    switch ($faq_type) {
      case 1:
        return $this->generateFaq1($faq_data);
        break;
    }
  }

  private function generateFaq1($faq_data)
  {
    $cache = '';
    $fd_arr = json_decode($faq_data, true);
    $cache .= '<div class="row mt-5">';
    $cache .= '<div class="col-12 col-lg-6 offset-lg-3">';
    $cache .= '<h3 class="t-lora t-blue t-m">' . $fd_arr["faq"]["question"] . '</h3>';
    $cache .= '<p>' . $fd_arr["faq"]["answer"] . '</p>';
    $cache .= '</div>';
    $cache .= '</div>';
    return $cache;
  }
}
