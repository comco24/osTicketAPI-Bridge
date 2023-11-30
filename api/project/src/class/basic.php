<?php
class basic
{
  /* #region  "writeToDB" */
  function writeToDB($action, $params)
  {
    switch ($action) {
      case 'access_id':
        $this->writeAccessID($params);
        break;
      case '':
        break;
    }
  }
  /* #endregion */

  /* #region  "writeAccessID" */
  function writeAccessID($params)
  {
    global $db_pdo;

    $sql = "INSERT INTO " . TBL_API_ACCESS . " (app_id, access_id, ts, valid_window, ip_address) VALUES (:app_id, :access_id, :ts, :valid_window, :ip_address)";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $params["app_id"], PDO::PARAM_STR);
    $stmt->bindValue(':access_id', $params["access_id"], PDO::PARAM_STR);
    $stmt->bindValue(':ts', $params["timestamp"], PDO::PARAM_STR);
    $stmt->bindValue(':valid_window', $params["valid_window"], PDO::PARAM_INT);
    $stmt->bindValue(':ip_address', $params["ip_address"], PDO::PARAM_STR);
    $stmt->execute();
  }
  /* #endregion */

  /* #region  "isAccessIdValid" */
  function isAccessIdValid($app_id, $access_id, $ip_address, $ts)
  {
    global $db_pdo;

    $SQL = "SELECT * FROM " . TBL_API_ACCESS . " WHERE app_id = :app_id AND access_id = :access_id";
    $stmt = $db_pdo->prepare($SQL);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->bindValue(':access_id', $access_id, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $ts_db = date("Y-m-d H:i:s", strtotime($record["ts"]) + $record["valid_window"]);
      if ($ts_db > $ts && $ip_address == $record["ip_address"]) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  /* #endregion */

  /* #region  "get_app_hash" */
  function get_app_hash($app_id, $public_key, $s_key)
  {
    $hash = hash('sha256', $s_key . $app_id . $public_key);
    return $hash;
  }
  /* #endregion */

  /* #region  "writeReqToDB_a" */
  function writeReqToDB_a($request)
  {
    global $db_pdo;

    $start_time = microtime(true);

    $db_ts = date("Y-m-d H:i:s");
    $db_json = $request;
    $db_action = "in";
    $SQL = "INSERT INTO " . TBL_API_COM . " (ts, json, action, time) VALUES (:ts, :json, :action, :time)";
    $stmt = $db_pdo->prepare($SQL);
    $stmt->bindValue(':ts', $db_ts, PDO::PARAM_STR);
    $stmt->bindValue(':json', $db_json, PDO::PARAM_STR);
    $stmt->bindValue(':action', $db_action, PDO::PARAM_STR);
    $stmt->bindValue(':time', $start_time, PDO::PARAM_STR);
    $stmt->execute();
    return $start_time;
  }
  /* #endregion */

  /* #region  "writeReqToDB_b" */
  function writeReqToDB_b($request, $start_time)
  {
    global $db_pdo;

    $end_time = microtime(true);
    $exec_time = $end_time - $start_time;
    $db_ts = date("Y-m-d H:i:s");
    $db_json = $request;
    $db_action = "out";
    $SQL = "INSERT INTO " . TBL_API_COM . " (ts, json, action, time) VALUES (:ts, :json, :action, :time)";
    $stmt = $db_pdo->prepare($SQL);
    $stmt->bindValue(':ts', $db_ts, PDO::PARAM_STR);
    $stmt->bindValue(':json', $db_json, PDO::PARAM_STR);
    $stmt->bindValue(':action', $db_action, PDO::PARAM_STR);
    $stmt->bindValue(':time', $exec_time, PDO::PARAM_STR);
    $stmt->execute();
  }
  /* #endregion */

  /* #region  "getRandomString" */
  function getRandomString($length = 8)
  {
    $characters = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
      $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
  }
  /* #endregion */

  /* #region  "getUniqueString" */
  function getUniqueString($length, $table, $column)
  {
    global $db_pdo;

    $i = 0;
    $unique = false;

    //ob_start();

    do {
      $string = $this->getRandomString($length);

      //echo 'string: '.$string.'<br>';

      $SQL = "SELECT " . $column . " FROM " . $table . " WHERE " . $column . " = :value";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':value', $string, PDO::PARAM_STR);
      $stmt->execute();
      //echo $SQL.' - '.$stmt->rowCount().'<br>';
      if ($stmt->rowCount() == 0) {
        $unique = true;
      }
      $i++;
      if ($i > 1000) {
        $unique = true;
      }
      /*
      if ($unique==true)
      {
          echo 'unique=true<br />';
      }
      else
      {
          echo 'unique=false<br />';
      }
      ob_end_flush();
      ob_flush();
       *
       */
    } while ($unique == false);
    return $string;
  }
  /* #endregion */

  /* #region  "apiAPP_getTopic()" */
  function apiAPP_getTopic($id)
  {
    global $db_pdo;

    $cache_array = array();

    if (isset($id) && $id > 0) {
      $SQL = "SELECT * FROM " . TBL_HELP_TOPIC . " WHERE topic_id = :topic_id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':topic_id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        $cache_array = array('topic_id' => $id, 'topic_name' => $record["topic"], 'topic_notes' => $record["notes"]);
        $cache_array["forms"][] = array('form_id' => 0, 'form_title' => '', 'fields' => array(array('field_id' => 1, 'field_label' => 'Meno', 'field_name' => 'name', 'field_type' => 'text', 'field_configuration' => null, 'field_desc' => null, 'field_required' => true), array('field_id' => 2, 'field_label' => 'Email', 'field_name' => 'email', 'field_type' => 'text', 'field_configuration' => '', 'field_desc' => '', 'field_required' => true)));
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
              $form_id = $record_form["id"];
              $form_title = $record_form["title"];
              $form_instructions = $record_form["instructions"];

              $SQL = "SELECT * FROM " . TBL_F_FIELD . " WHERE form_id = :form_id ORDER BY `sort`";
              $stmt = $db_pdo->prepare($SQL);
              $stmt->bindValue(':form_id', $record_htf["form_id"], PDO::PARAM_INT);
              $stmt->execute();
              if ($stmt->rowCount() > 0) {
                $records_fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $fields_array = array();
                foreach ($records_fields as $record_field) {
                  /* #region  "record_fields" */
                  $field_desc = null;
                  $field_validator = null;
                  $field_regex = null;
                  $field_required = false;
                  $field_length = 0;
                  if (!(in_array($record_field["id"], $disabled["disable"]))) {
                    if ($this->hasFlag($record_field["flags"], FLAG_CLIENT_VIEW)) {
                      /* #region  "fields" */
                      if (strlen(trim($record_field["configuration"])) > 0) {
                        $values = json_decode($record_field["configuration"], true);
                        if (isset($values["desc"]) && strlen(trim($values["desc"])) > 0) {
                          $field_desc = $values["desc"];
                        }
                        if (isset($values["length"]) && strlen(trim($values["length"])) > 0) {
                          $field_length = $values["length"];
                        } else {
                          if ($record_field["type"]=="text") {
                            // ak ide o polozku typu text a nie je nastavena max dlzka tak nastavime preddefinovanu dlzku 30 znakov
                            $field_length = 30;
                          }
                        }
                        if (isset($values["validator"]) && strlen(trim($values["validator"])) > 0) {
                          $field_validator = $values["validator"];
                        }
                        if (isset($values["regex"]) && strlen(trim($values["regex"])) > 0) {
                          $field_regex = $values["regex"];
                        }
                      } else {
                        if ($record_field["type"]=="text") {
                          // ak ide o polozku typu text a nie je nastavena max dlzka tak nastavime preddefinovanu dlzku 30 znakov
                          $field_length = 30;
                        }
                      }
                      if ($this->hasFlag($record_field["flags"], FLAG_CLIENT_REQUIRED)) {
                        $field_required = true;
                      }

                      /* #endregion */
                      $list_array = array();
                      switch ($record_field["type"]) {
                        case "text":
                        case "memo":
                        case "thread":
                        case "bool":
                        case "datetime":
                        case "files":
                          break;
                        case "choices":
                          $v_choices = explode(PHP_EOL, $values["choices"]);
                          $option = '';
                          foreach ($v_choices as $v_choice) {
                            if (strpos($v_choice, ':') === false) {
                              $list_array[] = array('id' => $v_choice, 'value' => $v_choice);
                            } else {
                              $choice = explode(':', $v_choice);
                              $list_array[] = array('id' => $choice[0], 'value' => $choice[1]);
                            }
                          }
                          break;

                          //break;
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
                                  $list_array[] = array('id' => $record_li["id"], 'value' => $record_li["value"]);
                                }
                              }
                              break;
                          }
                          break;
                      }
                      if (count($list_array) > 0) {
                        $fields_array[] = array('field_id' => (int) $record_field["id"], 'field_label' => $record_field["label"], 'field_name' => $record_field["name"], 'field_type' => $record_field["type"], 'field_configuration' => $record_field["configuration"], 'field_desc' => $field_desc, 'field_validator' => $field_validator, 'field_regex' => $field_regex, 'field_required' => $field_required, 'field_length' => $field_length, 'list' => $list_array);
                      } else {
                        $fields_array[] = array('field_id' => (int) $record_field["id"], 'field_label' => $record_field["label"], 'field_name' => $record_field["name"], 'field_type' => $record_field["type"], 'field_configuration' => $record_field["configuration"], 'field_desc' => $field_desc, 'field_validator' => $field_validator, 'field_regex' => $field_regex, 'field_required' => $field_required, 'field_length' => $field_length);
                      }
                    }
                  }
                  /* #endregion */
                }
              }
            }
            $cache_array["forms"][] = array('form_id' => (int) $form_id, 'form_title' => $form_title, 'form_instructions' => $form_instructions, 'fields' => $fields_array);
          }
        }
        $return_values = array(
          "result" => 1,
          "result_text" => null,
          "topic_id" => $id,
          "topic" => $cache_array,
          "status" => 200
        );
      } else {
        $return_values = array(
          "result" => 0,
          "result_text" => 'topic ID sa nepodarilo nájsť',
          "topic_id" => $id,
          "status" => 200
        );
      }
    } else {
      $return_values = array(
        "result" => 0,
        "result_text" => 'neplatné topic ID',
        "topic_id" => $id,
        "status" => 200
      );
    }

    return $return_values;
  }
  /* #endregion */

  /* #region  "apiAPP_getFaqsCategories()" */
  function apiAPP_getFaqsCategories()
  {
    global $db_pdo;

    $cache_category_array = array();

    $SQL = "SELECT * FROM " . TBL_FAQ_CATEGORY . " WHERE ispublic in (1, 2)";
    $stmt = $db_pdo->prepare($SQL);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $records_category = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($records_category as $record_category) {
        $cache_category_array[] = array('id' => $record_category["category_id"], 'category_name' => $record_category["name"], 'category_description' => $record_category["description"], 'caregory_notes' => $record_category["notes"], 'faqs' => $cache_array, 'sub_categories' =>$cache_array_sub);
      }
      $return_values = array(
        "result" => 1,
        "result_text" => null,
        "category" => $cache_category_array,
        "status" => 200
      );
    } else {
      $return_values = array(
        "result" => 0,
        "result_text" => 'v databáze sa nenachádza žiadna aktívna položka',
        "faqs" => null,
        "status" => 200
      );
    }

    return $return_values;
  }
  /* #endregion */

  
  /* #region  "apiAPP_getFaqs()" */
  function apiAPP_getFaqs($topic_id = 0)
  {
    global $db_pdo;

    $cache_category_array = array();

    if ($topic_id == 0) {
	    $SQL = "SELECT * FROM " . TBL_FAQ_CATEGORY . " WHERE ispublic in (1, 2) and  (category_pid is null or category_pid = 0) ORDER BY category_id";
    } else {
	    $SQL = "SELECT * FROM " . TBL_FAQ_CATEGORY . " WHERE ispublic in (1, 2) and category_id = $topic_id ORDER BY category_id";
    }
    $stmt = $db_pdo->prepare($SQL);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $records_category = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($records_category as $record_category) {
        $cache_array = array();
        $SQL = "SELECT * FROM " . TBL_FAQ . " WHERE ispublished in (1, 2) AND category_id = :category_id ORDER BY faq_id";
        $stmt = $db_pdo->prepare($SQL);
        $stmt->bindValue(':category_id', $record_category["category_id"], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
          $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($records as $record) {
            $cache_array[] = array('id' => $record["faq_id"], 'category_id' => $record["category_id"], 'question' => $record["question"], 'answer' => $record["answer"]);
          }

          $cache_array_sub = array();
             
		    $SQL = "SELECT * FROM " . TBL_FAQ_CATEGORY . " WHERE ispublic in (1, 2) and category_pid = :category_pid ORDER BY category_id";
	        $stmt = $db_pdo->prepare($SQL);
	        $stmt->bindValue(':category_pid', $record_category["category_id"], PDO::PARAM_INT);
	        $stmt->execute();
	        if ($stmt->rowCount() > 0) {
	          $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
	          foreach ($records as $record) {
	            $cache_array_sub[] = array('category_id' => $record["category_id"], 'category_name' => $record["name"], 'category_description' => $record["description"], 'caregory_notes' => $record["notes"]);
	          }
	        }#if
	              
          $cache_category_array[] = array('id' => $record_category["category_id"], 'category_name' => $record_category["name"], 'category_description' => $record_category["description"], 'caregory_notes' => $record_category["notes"], 'faqs' => $cache_array, 'sub_categories' =>$cache_array_sub);
        }
        
        
      }
      $return_values = array(
        "result" => 1,
        "result_text" => null,
        "category" => $cache_category_array,
        "status" => 200
      );
    } else {
      $return_values = array(
        "result" => 0,
        "result_text" => 'v databáze sa nenachádza žiadna aktívna položka',
        "faqs" => null,
        "status" => 200
      );
    }




    return $return_values;
  }
  /* #endregion */

  /* #region  "apiAPP_getFaqsTopic()" */
  function apiAPP_getFaqsTopic($id_topic = 0)
  {
    global $db_pdo;

    $cache_array = array();

    if ($id_topic > 0) {
      $SQL = "SELECT " . TBL_FAQ . ".* FROM " . TBL_FAQ . ", " . TBL_FAQ_TOPIC . " WHERE " . TBL_FAQ . ".faq_id = " . TBL_FAQ_TOPIC . ".faq_id AND " . TBL_FAQ_TOPIC . ".topic_id = :topic_id AND " . TBL_FAQ . ".ispublished = :ispublished ORDER BY " . TBL_FAQ . ".category_id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':topic_id', $id_topic, PDO::PARAM_INT);
      $stmt->bindValue(':ispublished', 1, PDO::PARAM_INT);
      $stmt->execute();
    } else {
      $SQL = "SELECT * FROM " . TBL_FAQ . " WHERE ispublished = :ispublished ORDER BY category_id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':ispublished', 1, PDO::PARAM_INT);
      $stmt->execute();
    }
    if ($stmt->rowCount() > 0) {
      $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($records as $record) {
        $cache_array[] = array('id' => $record["faq_id"], 'category_id' => $record["category_id"], 'question' => $record["question"], 'answer' => $record["answer"]);
      }
      $return_values = array(
        "result" => 1,
        "result_text" => null,
        "faqs" => $cache_array,
        "topic_id" => $id_topic,
        "status" => 200
      );
    } else {
      $return_values = array(
        "result" => 0,
        "result_text" => 'v databáze sa nenachádza žiadna aktívna položka',
        "faqs" => null,
        "topic_id" => $id_topic,
        "status" => 200
      );
    }
    return $return_values;
  }
  /* #endregion */

  /* #region  "apiAPP_getFaq()" */
  function apiAPP_getFaq($id)
  {
    global $db_pdo;

    $cache_array = array();

    if ($id > 0) {
      $SQL = "SELECT * FROM " . TBL_FAQ . " WHERE ispublished = :ispublished AND faq_id = :faq_id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':ispublished', 1, PDO::PARAM_INT);
      $stmt->bindValue(':faq_id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        $cache_array = array('id' => $record["faq_id"], 'category_id' => $record["category_id"], 'question' => $record["question"], 'answer' => $record["answer"]);
        $return_values = array(
          "result" => 1,
          "result_text" => null,
          "faq" => $cache_array,
          "faq_id" => $id,
          "status" => 200
        );
      } else {
        $return_values = array(
          "result" => 0,
          "result_text" => 'v databáze sa požadovaná položka nenachádza',
          "faq" => null,
          "faq_id" => $id,
          "status" => 200
        );
      }
    } else {
      $return_values = array(
        "result" => 0,
        "result_text" => 'neplatné faq ID',
        "faq" => null,
        "faq_id" => $id,
        "status" => 200
      );
    }

    return $return_values;
  }
  /* #endregion */

  /* #region  "getRecordsFromDB" */
  function getRecordsFromDB($action, $action_id, $params = '')
  {
    switch ($action) {
      case "get":
        switch ($action_id) {
          case "ip-address":
            return $this->getRecordsIP($params["app_id"]);
            break;
        }
        break;
      case "set":
        switch ($action_id) {
          case "add-ip":
            return $this->addIP($params["app_id"], $params["ip_address"]);
            break;
          case "remove-ip":
            return $this->removeIP($params["app_id"], $params["ip_address"]);
            break;
        }
        break;
    }
  }
  /* #endregion */

  /* #region  "getRecordsIP" */
  function getRecordsIP($app_id)
  {
    global $db_pdo;

    $sql = "SELECT * FROM " . TBL_API_ACCESS_APP_IP . " WHERE app_id = :app_id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      return array();
    }
  }
  /* #endregion */

  /* #region  "isValidRequest" */
  function isValidRequest($app_id, $access_id)
  {
    global $db_pdo;

    $sql = "SELECT * FROM " . TBL_API_ACCESS . " WHERE app_id = :app_id AND access_id = :access_id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->bindValue(':access_id', $access_id, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      if (date("Y-m-d H:i:s", (strtotime($record["ts"]) + $record["valid_window"])) < date("Y-m-d H:i:s")) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }
  /* #endregion */

  /* #region  "isValidIP" */
  function isValidIP($ip_address, $app_id)
  {
    global $db_pdo;

    $sql = "SELECT * FROM " . TBL_API_ACCESS_APP_IP . " WHERE app_id = :app_id AND ip_address = :ip_address";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }
  /* #endregion */

  /* #region  "setRecordToDB" */
  function setRecordToDB($action, $action_id, $params = '')
  {
    switch ($action) {
      case "set":
        switch ($action_id) {
          case "add-ip":
            return $this->addIP($params["app_id"], $params["ip_address"]);
            break;
          case "remove-ip":
            return $this->removeIP($params["app_id"], $params["ip_address"]);
            break;
        }
        break;
    }
  }
  /* #endregion */

  /* #region  "addIP" */
  function addIP($app_id, $ip_address)
  {
    global $db_pdo;

    $sql = "SELECT * FROM " . TBL_API_ACCESS_APP_IP . " WHERE app_id = :app_id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() >= MAX_IP_ADDRESSES) {
      return 9;
    }

    $sql = "SELECT * FROM " . TBL_API_ACCESS_APP_IP . " WHERE app_id = :app_id AND ip_address = :ip_address";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      return 8;
    }

    $sql = "INSERT INTO " . TBL_API_ACCESS_APP_IP . " (app_id, ip_address) VALUES (:app_id, :ip_address)";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      return 1;
    } else {
      return 0;
    }
  }
  /* #endregion */

  /* #region  "removeIP" */
  function removeIP($app_id, $ip_address)
  {
    global $db_pdo;

    $sql = "SELECT * FROM " . TBL_API_ACCESS_APP_IP . " WHERE app_id = :app_id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() == 1) {
      return 9;
    }

    $sql = "DELETE FROM " . TBL_API_ACCESS_APP_IP . " WHERE app_id = :app_id AND ip_address = :ip_address";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':app_id', $app_id, PDO::PARAM_STR);
    $stmt->bindValue(':ip_address', $ip_address, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      return 1;
    } else {
      return 0;
    }
  }
  /* #endregion */

  /* #region  "getIP" */
  function getIP($format = "ip")
  {
    //    echo "HTTP_CLIENT_IP:".$_SERVER['HTTP_CLIENT_IP']."<br>";
    //    echo "HTTP_X_FORWARDED_FOR:".$_SERVER['HTTP_X_FORWARDED_FOR']."<br>";
    //    echo "REMOTE_ADDR:".$_SERVER['REMOTE_ADDR']."<br>";
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
      //Is it a proxy address
      //    }
      //    elseif
      //    (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
      //    {
      //      $ip_tmp = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
      //      if (count($ip_tmp)>1)
      //      {
      //        $ip=$ip_tmp[(count($ip_tmp)-1)];
      //      }
      //      else
      //      {
      //        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
      //      }
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    if ($format == "ip") {
      return $ip;
    } else {
      return ip2long($ip); // pre dekodovani: SELECT INET_NTOA(ip) FROM ...
    }
  }
  /* #endregion */

  /* #region  "hasFlag" */
  function hasFlag($flag, $requested_flag)
  {
    return (($flag & $requested_flag) != 0);
  }
  /* #endregion */
}
