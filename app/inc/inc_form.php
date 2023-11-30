<?php
const FLAG_CLIENT_VIEW = 256;
const FLAG_CLIENT_EDIT = 512;
const FLAG_CLIENT_REQUIRED = 1024;
const MASK_CLIENT_FULL = 1792;

function hasFlag($flag, $requested_flag)
{
  return (($flag & $requested_flag) != 0);
}

$fields = array();
if (isset($id) && $id > 0) {
  $SQL = "SELECT * FROM " . TBL_HELP_TOPIC . " WHERE topic_id = :topic_id";
  $stmt = $db_pdo->prepare($SQL);
  $stmt->bindValue(':topic_id', $id, PDO::PARAM_INT);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="row">
      <div class="col-12">
        <form action="<?php echo DIR_WWW_ROOT; ?>act/send-form/" method="post">
          <input type="hidden" name="topic_id" value="<?php echo $id; ?>">
          <div class="row">
            <div class="col-6 offset-sm-3">
              <label for="">Meno:</label>
              <input type="text" name="name" />
            </div>
          </div>
          <div class="row">
            <div class="col-6 offset-sm-3">
              <label for="">Email:</label>
              <input type="text" name="email" />
            </div>
          </div>
          <?php
              echo $sec->csrf_token_tag();

              $SQL = "SELECT * FROM " . TBL_HT_FORM . " WHERE topic_id = :topic_id ORDER BY `sort`";
              $stmt = $db_pdo->prepare($SQL);
              $stmt->bindValue(':topic_id', $id, PDO::PARAM_INT);
              $stmt->execute();
              if ($stmt->rowCount() > 0) {
                $records_htf = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($records_htf as $record_htf) {
                  $disabled = json_decode($record_htf["extra"], true);
                  $SQL = "SELECT * FROM " . TBL_FORM . " WHERE id = :id";
                  $stmt = $db_pdo->prepare($SQL);
                  $stmt->bindValue(':id', $record_htf["form_id"], PDO::PARAM_INT);
                  $stmt->execute();
                  if ($stmt->rowCount() > 0) {
                    $record_form = $stmt->fetch(PDO::FETCH_ASSOC);
                    $form_title = $record_form["title"];
                    $form_instructions = $record_form["instructions"];
                    $field_counter = 0;
                    $form_header_show = 1;
                    $SQL = "SELECT * FROM " . TBL_F_FIELD . " WHERE form_id = :form_id ORDER BY `sort`";
                    $stmt = $db_pdo->prepare($SQL);
                    $stmt->bindValue(':form_id', $record_htf["form_id"], PDO::PARAM_INT);
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                      $records_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      foreach ($records_fields as $record_field) {
                        if (!(in_array($record_field["id"], $disabled["disable"]))) {
                          if (hasFlag($record_field["flags"], FLAG_CLIENT_VIEW)) {
                            $fields[] = $record_field["name"];
                            $field_counter++;
                            switch ($record_field["type"]) {
                              case "text":
                                $label = $record_field["label"];
                                $input = '<input type="text" name="' . $record_field["name"] . '" />';
                                break;
                              case "memo":
                                $label = $record_field["label"];
                                $input = '<textarea name="' . $record_field["name"] . '"></textarea>';
                                break;
                              case "thread":
                                $label = $record_field["label"];
                                $input = '<textarea name="' . $record_field["name"] . '"></textarea>';
                                break;
                              case "choices":
                                $label = $record_field["label"];
                                $values = json_decode($record_field["configuration"], true);
                                $v_choices = explode(PHP_EOL, $values["choices"]);
                                $option = '';
                                foreach ($v_choices as $v_choice) {
                                  $choice = explode(':', $v_choice);
                                  $option .= '<option value="' . $choice[0] . '">' . $choice[1] . '</option>';
                                }
                                $input = '<select name="' . $record_field["name"] . '">' . $option . '</select>';
                                break;
                              case "bool":
                                $label = $record_field["label"];
                                $desc = '';
                                if (strlen(trim($record_field["configuration"])) > 0) {
                                  $values = json_decode($record_field["configuration"], true);
                                  if (strlen(trim($values["desc"])) > 0) {
                                    $desc = $values["desc"];
                                  }
                                }
                                $input = '<input type="checkbox" name="' . $record_field["name"] . '" value="1" /> ' . $desc;
                                break;
                              case "datetime":
                                $label = $record_field["label"];

                                $option = '';
                                for ($i = 1; $i <= 31; $i++) {
                                  $option .= '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . $i . '</option>';
                                }
                                $fields[] = $record_field["name"] . '_den';
                                $den = '<select name="' . $record_field["name"] . '_den">' . $option . '</select>';
                                $option = '';
                                for ($i = 1; $i <= 12; $i++) {
                                  $option .= '<option value="' . str_pad($i, 2, "0", STR_PAD_LEFT) . '">' . $i . '</option>';
                                }
                                $fields[] = $record_field["name"] . '_mesiac';
                                $mesiac = '<select name="' . $record_field["name"] . '_mesiac">' . $option . '</select>';
                                $option = '';
                                for ($i = date("Y"); $i <= (date("Y") + 10); $i++) {
                                  $option .= '<option value="' . $i . '">' . $i . '</option>';
                                }
                                $fields[] = $record_field["name"] . '_rok';
                                $rok = '<select name="' . $record_field["name"] . '_rok">' . $option . '</select>';
                                $input = '<table class="input"><tr><td>deň: ' . $den . '</td><td>mesiac: ' . $mesiac . '</td><td>rok: ' . $rok . '</td></tr></table>';
                                break;
                              default:
                                $type = explode("-", $record_field["type"]);
                                switch ($type[0]) {
                                  case "list":
                                    $SQL = "SELECT * FROM " . TBL_LIST . " WHERE id = :id";
                                    $stmt = $db_pdo->prepare($SQL);
                                    $stmt->bindValue(':id', $type[1], PDO::PARAM_INT);
                                    $stmt->execute();
                                    if ($stmt->rowCount() > 0) {
                                      $record_list = $stmt->fetch(PDO::FETCH_ASSOC);
                                      switch ($record_list["sort_mode"]) {
                                        case "Alpha":
                                          $order_by = " ORDER BY `value`";
                                          break;
                                        case "SortCol":
                                          $order_by = " ORDER BY `sort`";
                                        default:
                                          break;
                                      }

                                      $option = '';
                                      $SQL = "SELECT * FROM " . TBL_L_ITEMS . " WHERE status = 1 AND list_id = :id" . $order_by;
                                      $stmt = $db_pdo->prepare($SQL);
                                      $stmt->bindValue(':id', $type[1], PDO::PARAM_INT);
                                      $stmt->execute();
                                      $records_list_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                      foreach ($records_list_items as $record_li) {
                                        $option .= '<option value="' . $record_li["id"] . '">' . $record_li["value"] . '</option>';
                                      }
                                      $label = $record_field["label"];
                                      $input = '<select name="' . $record_field["name"] . '">' . $option . '</select>';
                                    }
                                    break;
                                }
                                break;
                            }
                            if ($form_header_show === 1 && $field_counter > 0) {
                              ?>
                                <div class="row">
                                  <div class="col-6 offset-sm-3">
                                    <h3 class="t-lora t-s t-700 t-blue text-left mt-5"><?php echo $form_title; ?></h3>
                                    <?php
                                                        if (strlen(trim($form_instructions)) > 0) {
                                                          ?>
                                      <p class="text-left"><?php echo $form_instructions; ?></p>
                                    <?php
                                                        }
                                                        ?>
                                  </div>
                                </div>
                              <?php
                                                  $form_header_show = 0;
                                                }
                                                ?>
                              <div class="row">
                                <div class="col-6 offset-sm-3">
                                  <label for=""><?php echo $label; ?></label>
                                  <?php echo $input; ?>
                                  <?php
                                                    if (strlen(trim($record_field["configuration"])) > 0) {
                                                      $f_value = json_decode($record_field["configuration"], true);
                                                      if ($f_value["validator"] == "regex") {
                                                        echo '<br><span class="t-green">regex: ' . $f_value["regex"] . '</span>';
                                                      }
                                                    }
                                                    ?>
                                </div>
                              </div>
                <?php
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                    ?>
                <div class="row">
                  <div class="col-12 text-center pt-5">
                    <button type="submit" class="general-green-btn">Odoslať</button>

                  </div>
                </div>
                <?php
                    // form_fields obsahuje nazvy vsetkych premennych z formulara v json objekte
                    $jsn = json_encode($fields);
                    ?>
                <input type="hidden" name="form_fields" value='<?php echo $jsn; ?>'>
        </form>
      </div>
    </div>
<?php
  }
} else {
  echo 'Neplatné ID fromuláru!';
}
?>

<div class="row my-5">
  <div class="col-12 text-center pb-5">
    <a href="<?php echo DIR_WWW_ROOT; ?>" class="general-green-btn">späť</a>
  </div>
</div>


<!--
return array(
  'a' => array('desc' => __('Optional'),
      'flags' => self::FLAG_CLIENT_VIEW | self::FLAG_AGENT_VIEW
          | self::FLAG_CLIENT_EDIT | self::FLAG_AGENT_EDIT),
  'b' => array('desc' => __('Required'),
      'flags' => self::FLAG_CLIENT_VIEW | self::FLAG_AGENT_VIEW
          | self::FLAG_CLIENT_EDIT | self::FLAG_AGENT_EDIT
          | self::FLAG_CLIENT_REQUIRED | self::FLAG_AGENT_REQUIRED),
  'c' => array('desc' => __('Required for EndUsers'),
      'flags' => self::FLAG_CLIENT_VIEW | self::FLAG_AGENT_VIEW
          | self::FLAG_CLIENT_EDIT | self::FLAG_AGENT_EDIT
          | self::FLAG_CLIENT_REQUIRED),
  'd' => array('desc' => __('Required for Agents'),
      'flags' => self::FLAG_CLIENT_VIEW | self::FLAG_AGENT_VIEW
          | self::FLAG_CLIENT_EDIT | self::FLAG_AGENT_EDIT
          | self::FLAG_AGENT_REQUIRED),
  'e' => array('desc' => __('Internal, Optional'),
      'flags' => self::FLAG_AGENT_VIEW | self::FLAG_AGENT_EDIT),
  'f' => array('desc' => __('Internal, Required'),
      'flags' => self::FLAG_AGENT_VIEW | self::FLAG_AGENT_EDIT
          | self::FLAG_AGENT_REQUIRED),
  'g' => array('desc' => __('For EndUsers Only'),
      'flags' => self::FLAG_CLIENT_VIEW | self::FLAG_CLIENT_EDIT
          | self::FLAG_CLIENT_REQUIRED),
);

const FLAG_ENABLED          = 0x00001;
const FLAG_EXT_STORED       = 0x00002; // Value stored outside of form_entry_value
const FLAG_CLOSE_REQUIRED   = 0x00004;

const FLAG_MASK_CHANGE      = 0x00010;
const FLAG_MASK_DELETE      = 0x00020;
const FLAG_MASK_EDIT        = 0x00040;
const FLAG_MASK_DISABLE     = 0x00080;
const FLAG_MASK_REQUIRE     = 0x10000;
const FLAG_MASK_VIEW        = 0x20000;
const FLAG_MASK_NAME        = 0x40000;

const MASK_MASK_INTERNAL    = 0x400B2;  # !change, !delete, !disable, !edit-name
const MASK_MASK_ALL         = 0x700F2;

const FLAG_CLIENT_VIEW      = 0x00100;
const FLAG_CLIENT_EDIT      = 0x00200;
const FLAG_CLIENT_REQUIRED  = 0x00400;

const MASK_CLIENT_FULL      = 0x00700;

const FLAG_AGENT_VIEW       = 0x01000;
const FLAG_AGENT_EDIT       = 0x02000;
const FLAG_AGENT_REQUIRED   = 0x04000;

const MASK_AGENT_FULL       = 0x7000;
  -->