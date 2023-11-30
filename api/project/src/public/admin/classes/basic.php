<?php
class basic
{
  var $show_errors = true;
  
  function odoslatMail($par)
  {
    global $log;
    
    require_once('mail.php');
    $mail = new htmlMail();
    
    switch($par['modul'])
    {
      case 'signup':
        $mail_data = $mail->mailSignUp($par);
        $log_flag = 'registračný mail';
        break;
    }
    
    require_once '../../vendor/autoload.php';

    $transport = (new Swift_SmtpTransport(MAIL_SMTP_SERVER, MAIL_SMTP_PORT, 'ssl'))
      ->setUsername(MAIL_ACC_NAME)
      ->setPassword(MAIL_ACC_PASS)
    ;

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message($mail_data["subject"]))
      ->setFrom([MAIL_CONTACT => MAIL_CONTACT_NAME])
      ;

    if (isset($mail_data["emails"]["to"]))
    {
      $message->setTo($mail_data["emails"]["to"]);
    }
    if (isset($mail_data["emails"]["bcc"]))
    {
      $message->setBcc($mail_data["emails"]["bcc"]);
    }
    if (isset($mail_data["emails"]["cc"]))
    {
      $message->setCc($mail_data["emails"]["cc"]);
    }

    $msg_header = '<html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="'.$par["lang"].'" />
      </head>
      <body>
      <div style="width:100%;background-color:#ededed;text-align:center;padding-top:20px; padding-bottom:20px;">
      <table style="width: 800px; border: 1px solid #dedede; font-family: Tahoma; color: #071a39; font-size:15px;background-color:#dfdfdf;" cellpadding="0" cellspacing="0">
      <tr>
        <td style="text-align: center;margin:0;padding:0;">
          <img src="'.$message->embed(Swift_Image::fromPath('../../images/mail-main-header.png')).'" alt="Image" />
        </td>
      </tr>
      <tr>
        <td style="text-align: center;margin:0;"><h1 style="width:100%;text-align:center;font-size:24px;font-weight:bold; margin:20px 0px 0px 0px;">'.$mail_data["header"].'</h1></td>
      </tr>
      <tr>
        <td style="text-align: center;margin:0;padding:20px;">
          <table style="width: 100%; border: 0px; font-family: Tahoma; color: #000000; font-size:15px;background-color:#ffffff;" cellpadding="0" cellspacing="0">
          <tr>
            <td style="padding:20px;margin:0;text-align:center;">';

    $msg_footer = '</td></tr></table>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="font-size:10px;padding:20px;margin:0; color: #777777; text-align:justify; font-family: Tahoma;">'.MAIL_DISCL.'</td>
      </tr>
</table>
</div></body>
      </html>';

    $message->setBody($msg_header.$mail_data["html_mail"].$msg_footer, 'text/html');
    $message->addPart($mail_data["text_mail"], 'text/plain');

    // Send the message
    try 
    {
      $r = $mailer->send($message, $failedRecipients);
    } 
    catch (Exception $ex) 
    {
      $r = false;
      //echo $ex->getMessage();exit();
    }
  
    $id_log = $log->createEntry($_SESSION["admin"]["auth"]["id_user"],$_SESSION["admin"]["auth"]["id_type"],date("Y-m-d H:i:s"),LOG_TYPE_EMAIL,SITE_BE);
    $zaznam = $par["email"];
    
    if ($r==false)
    {
      $log->addEmailEntry($id_log, $log_flag, 2, $zaznam);
    }
    else
    {
      $log->addEmailEntry($id_log, $log_flag, 1, $zaznam);
    }
    return $r;
  }
  
  function checkMsgScope($scope)
  {
    if (isset($_SESSION["admin"]["msg"]["scope"]))
    {
      if ($scope!=$_SESSION["admin"]["msg"]["scope"])
      {
        unset($_SESSION["admin"]["msg"]);
        $_SESSION["admin"]["msg"]["status"] = false;
      }
    }
  }
  
  function getLogUser($id_user_type,$id_user)
  {
    global $db_pdo;
    
    if ($id_user>0)
    {
      $SQL = "SELECT * FROM ".TBL_USER." WHERE id = :id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':id', $id_user, PDO::PARAM_INT);
      $stmt->execute();
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      switch($id_user_type)
      {
        case USER_TYPE_ADMIN:
          $typ = 'ADMIN';
          break;
        case USER_TYPE_DESIGNER:
          $typ = 'DIZAJNÉR';
          break;
        case USER_TYPE_INFLUENCER:
          $typ = 'INFLUENCER';
          break;
      }
      return '<br /><div class="log_user">'.$record["nickname"].'</div><div class="log_user_type">'.$typ.'</div>';
    }
    else 
    {
      return '';
    }
  }
  
  function getLogRecord($row)
  {
      global $db_pdo;

      $user = '';
      switch($row["id_log_type"])
      {
        case 1:
          $table = TBL_LOG_1;
          break;
        case 2:
          $table = TBL_LOG_2;
          $user = $this->getLogUser($row["id_user_type"],$row["id_user"]);
          break;
        case 3:
          $table = TBL_LOG_3;
          $user = $this->getLogUser($row["id_user_type"],$row["id_user"]);
          break;
        case 4:
          $table = TBL_LOG_4;
          $user = $this->getLogUser($row["id_user_type"],$row["id_user"]);
          break;
        case 5:
          $table = TBL_LOG_5;
          break;
        case 9:
          $table = TBL_LOG_9;
          $user = $this->getLogUser($row["id_user_type"],$row["id_user"]);
          break;
      }
      
      $cache = '<tr>';
      $cache .= '<td valign="middle" class="d-none d-sm-table-cell">'.$row["id"].'</td>';
      $cas = date("j.n.Y H:i:s",strtotime($row["ts_created"]));
      $cache .= '<td valign="middle" class="text-left" >'.$cas.$user.'</td>';
      

      
      $sql = "SELECT * FROM ".$table." WHERE id_log = :id_log";
      $stmt = $db_pdo->prepare($sql);
      $stmt->bindValue(':id_log', $row["id"], PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()>0)
      {
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        $value = '<div class="flag'.$record["id_class"].'">'.$record["flag"].'</div>';
        $cache .= '<td valign="middle" class="text-left">'.$value.'</td>';
        $cache .= '<td valign="middle" class="text-left">'.nl2br($record["description"]).'</td>';
      }
      else
      {
        $cache .= '<td valign="middle" class="text-left">-</td>';
        $cache .= '<td valign="middle" class="text-left">-</td>';
      }
      $cache .= "</tr>";
      return $cache;
  }
  
  function getDesignerRecord($row)
  {
      global $db_pdo;
      global $lang;

      $action_btn = '';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id"].'/detail.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Detail"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id"].'/edit.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Editovat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/msg/q/designer/del-'.$row["id"].'.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id"].'/portfolio/p-1.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Portfolio"].'</a>';
      
      if ($row["status"]==1)
      {
        $status = '<i class="fas fa-check-circle fa-1x col-dgreen"></i>';
      }
      else
      {
        $status = '<i class="fas fa-times-circle fa-1x col-dred"></i>';
      }
      
      if (strlen($row["last_name"])>0)
      {
        if (strlen($row["first_name"])>0)
        {
          $name = $row["last_name"].' '.$row["first_name"];
        }
        else 
        {
          $name = $row["last_name"];
        }
      }
      else
      {
        $name = '-';
      }
      
      $SQL = "SELECT * FROM ".TBL_COUNTRY." WHERE id = :id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':id', $row["id_country"], PDO::PARAM_INT);
      $stmt->execute();
      $record_country = $stmt->fetch(PDO::FETCH_ASSOC);
      $krajina = $record_country["country"];
      
      if ($row["avatar"]!='')
      {
        $img_src = DIR_WWW_ROOT.'files/img/'.$row["avatar"];
        
      }
      else
      {
        $img_src = DIR_WWW_ROOT.'files/img/no-avatar.png';
      }
      $img = '<img src="'.$img_src.'" class="avatar_list" />';
      
      $cache = '<tr>';
      $cache .= '<td class="d-none d-sm-table-cell align-middle">'.$row["id"].'</td>';
      $cache .= '<td class="align-middle">'.$status.'</td>';
      $cache .= '<td class="align-middle text-center">'.$img.'</td>';
      $cache .= '<td class="text-left align-middle"><div class="tagA"><strong>'.$row["nickname"].'</strong></div><br />'.$name.'</td>';
      $cas_registracia = date("j.n.Y H:i:s",strtotime($row["ts_created"]));
      if ($row["ts_confirmed"]>BP_DATE)
      {
        $cas_aktivacia = date("j.n.Y H:i:s",strtotime($row["ts_confirmed"]));
      }
      else
      {
        $cas_aktivacia = '-';
      }
      if ($row["ts_last_login"]>BP_DATE)
      {
        $cas_prihlasenie = date("j.n.Y H:i:s",strtotime($row["ts_last_login"]));
      }
      else 
      {
        $cas_prihlasenie = '-';
      }
      $cache .= '<td class="text-left align-middle" >'.$cas_registracia.'<br />'.$cas_aktivacia.'<br />'.$cas_prihlasenie.'</td>';
  $cache .= '<td class="text-left align-middle" >'.$row["email"].'<br />'.$row["phone"].'<br />'.$row["address"].', '.$row["city"].' '.$row["zip"].', '.$krajina.'</td>';
      $cache .= '<td class="text-center align-middle" >'.$action_btn.'</td>';
      
      $cache .= "</tr>";
      return $cache;
  }
  
  function getInfluencerRecord($row)
  {
      global $db_pdo;
      global $lang;

      $action_btn = '';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/influencer/'.$row["id"].'/detail.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Detail"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/influencer/'.$row["id"].'/edit.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Editovat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/msg/q/influencer/del-'.$row["id"].'.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/influencer/'.$row["id"].'/portfolio/p-1.html" class="adm_btn2">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Portfolio"].'</a>';
      
      if ($row["status"]==1)
      {
        $status = '<i class="fas fa-check-circle fa-1x col-dgreen"></i>';
      }
      else
      {
        $status = '<i class="fas fa-times-circle fa-1x col-dred"></i>';
      }
      
      if (strlen($row["last_name"])>0)
      {
        if (strlen($row["first_name"])>0)
        {
          $name = $row["last_name"].' '.$row["first_name"];
        }
        else 
        {
          $name = $row["last_name"];
        }
      }
      else
      {
        $name = '-';
      }
      
      $SQL = "SELECT * FROM ".TBL_COUNTRY." WHERE id = :id";
      $stmt = $db_pdo->prepare($SQL);
      $stmt->bindValue(':id', $row["id_country"], PDO::PARAM_INT);
      $stmt->execute();
      $record_country = $stmt->fetch(PDO::FETCH_ASSOC);
      $krajina = $record_country["country"];
      
      if ($row["avatar"]!='')
      {
        $img_src = DIR_WWW_ROOT.'files/img/'.$row["avatar"];
        
      }
      else
      {
        $img_src = DIR_WWW_ROOT.'files/img/no-avatar.png';
      }
      $img = '<img src="'.$img_src.'" class="avatar_list" />';
      
      $cache = '<tr>';
      $cache .= '<td class="d-none d-sm-table-cell align-middle">'.$row["id"].'</td>';
      $cache .= '<td class="align-middle">'.$status.'</td>';
      $cache .= '<td class="align-middle text-center">'.$img.'</td>';
      $cache .= '<td class="text-left align-middle"><div class="tagA"><strong>'.$row["nickname"].'</strong></div><br />'.$name.'</td>';
      $cas_registracia = date("j.n.Y H:i:s",strtotime($row["ts_created"]));
      if ($row["ts_confirmed"]>BP_DATE)
      {
        $cas_aktivacia = date("j.n.Y H:i:s",strtotime($row["ts_confirmed"]));
      }
      else
      {
        $cas_aktivacia = '-';
      }
      if ($row["ts_last_login"]>BP_DATE)
      {
        $cas_prihlasenie = date("j.n.Y H:i:s",strtotime($row["ts_last_login"]));
      }
      else 
      {
        $cas_prihlasenie = '-';
      }
      $cache .= '<td class="text-left align-middle" >'.$cas_registracia.'<br />'.$cas_aktivacia.'<br />'.$cas_prihlasenie.'</td>';
  $cache .= '<td class="text-left align-middle" >'.$row["email"].'<br />'.$row["phone"].'<br />'.$row["address"].', '.$row["city"].' '.$row["zip"].', '.$krajina.'</td>';
      $cache .= '<td class="text-center align-middle" >'.$action_btn.'</td>';
      
      $cache .= "</tr>";
      return $cache;
  }
  
  function getDesignerPortfolioRecord($row)
  {
      global $db_pdo;
      global $lang;

      $action_btn = '';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id_user"].'/portfolio/'.$row["id"].'/detail.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Detail"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id_user"].'/portfolio/'.$row["id"].'/edit.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Editovat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id_user"].'/gallery/'.$row["id"].'/detail.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Galeria"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/msg/q/designer-portfolio/del-'.$row["id"].'.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id_user"].'/portfolio/'.$row["id"].'/contact-influencer.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Poslat_ponuku"].'</a>';
      
      if ($row["status"]==1)
      {
        $status = '<i class="fas fa-check-circle fa-1x col-dgreen"></i>';
      }
      else
      {
        $status = '<i class="fas fa-times-circle fa-1x col-dred"></i>';
      }
      
      if ($row["file"]!='')
      {
        $img_src = DIR_WWW_ROOT.'files/img/portfolio/t-'.$row["file"];
        $img_src_org = DIR_WWW_ROOT.'files/img/portfolio/'.$row["file"];
        $img = '<a href="'.$img_src_org.'" data-toggle="lightbox" data-title="'.$row["title"].'" data-max-width="700">
                <img src="'.$img_src.'" class="img-fluid">
            </a>';
      }
      else
      {
        $img_src = DIR_WWW_ROOT.'files/img/no-image.png';
        $img = '<img src="'.$img_src.'" class="image" />';
      }
      
      
      
      $cache = '<tr>';
      $cache .= '<td class="d-none d-sm-table-cell align-middle">'.$row["id"].'</td>';
      $cache .= '<td class="align-middle text-center">'.$status.'</td>';
      $cache .= '<td class="align-middle">'.date("j.n.Y H:i:s",strtotime($row["ts_updated"])).'</td>';
      $cache .= '<td class="align-middle text-left">'.$row["title"].'</td>';
      $cache .= '<td class="align-middle text-center">'.$img.'</td>';
      $cache .= '<td class="text-center align-middle" >'.$action_btn.'</td>';
      
      $cache .= "</tr>";
      return $cache;
  }
  
  function getDesignerPortfolioGalleryRecord($row)
  {
      global $db_pdo;
      global $lang;

      $action_btn = '';
      //$action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/app/designer/'.$row["id_user"].'/gallery/'.$row["id"].'/edit.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Editovat"].'</a>';
      $action_btn .= '<a href="'.DIR_WWW_ROOT.'admin/msg/q/designer-gallery/del-'.$row["id"].'.html" class="adm_btn2 d_inline">'.$lang[$_SESSION["admin"]["global"]["lang"]]["Vymazat"].'</a>';
      
      if ($row["status"]==1)
      {
        $status = '<i class="fas fa-check-circle fa-1x col-dgreen"></i>';
      }
      else
      {
        $status = '<i class="fas fa-times-circle fa-1x col-dred"></i>';
      }
      
      if ($row["file"]!='')
      {
        $img_src = DIR_WWW_ROOT.'files/img/portfolio/t-'.$row["file"];
        $img_src_org = DIR_WWW_ROOT.'files/img/portfolio/'.$row["file"];
        $img = '<a href="'.$img_src_org.'" data-toggle="lightbox" data-title="'.$row["title"].'" data-max-width="700">
                <img src="'.$img_src.'" class="img-fluid">
            </a>';
      }
      else
      {
        $img_src = DIR_WWW_ROOT.'files/img/no-image.png';
        $img = '<img src="'.$img_src.'" class="image" />';
      }
      
      
      
      $cache = '<tr>';
      $cache .= '<td class="d-none d-sm-table-cell align-middle">'.$row["id"].'</td>';
      $cache .= '<td class="align-middle text-center">'.$status.'</td>';
      $cache .= '<td class="align-middle text-center">'.$img.'</td>';
      $cache .= '<td class="align-middle text-left"><strong>'.$row["title"].'</strong><br /><span class="record_popis">'.$row["description"].'</span></td>';
      $cache .= '<td class="text-center align-middle" >'.$action_btn.'</td>';
      
      $cache .= "</tr>";
      return $cache;
  }
  
  function getLogStringFromArray($arr)
  {
    $cache = '';
    foreach ($arr as $key=>$value)
    {
      $cache .= '<br>'.$key.' -&gt; <strong>'.$value.'</strong>';
    }
    $cache = substr($cache,4);
    return $cache;
  }
      
  function kontrolaPovinnychPoli($zkontroluj,$modul)
  {
    global $lang;
    
    $result = true;
    $result_array = array();
    
    foreach($zkontroluj as $z)
    {  
      switch($z["kontrola"])
      {
        case "heslo_zhoda":
          if (!$z["val_1"]==$z["val_2"])
          {
            $msg = $lang[$_SESSION["admin"]["global"]["lang"]]["Hesla_sa_nezhoduju"];
            $result = false;
            $result_array[] = array('msg' => $msg);
          }
          break;
        case "heslo_dlzka":
          if (!(strlen(trim($z["val_1"]))>=$z["par_1"]))
          {
            $msg = $lang[$_SESSION["admin"]["global"]["lang"]]["Heslo_dlzka"].' ('.MIN_PWD_STRING_LENGTH.')';
            $result = false;
            $result_array[] = array('msg' => $msg);
          }
          break;
        case "povinne":
          if (!strlen(trim($z["val_1"]))>0)
          {
            $msg = $lang[$_SESSION["admin"]["global"]["lang"]]["Chyba_povinne_pole"].' ('.$z["nazov"].')';
            $result = false;
            $result_array[] = array('msg' => $msg);
          }
          break;
        case "povinne_integer":
          if (!(int)$z["val_1"]>0)
          {
            $msg = $lang[$_SESSION["admin"]["global"]["lang"]]["Chyba_povinne_pole"].' ('.$z["nazov"].')';
            $result = false;
            $result_array[] = array('msg' => $msg);
          }
          break;
        case "email":
          if (!$this->isEmailValid($z["val_1"]))
          {
            $msg = $lang[$_SESSION["admin"]["global"]["lang"]]["Neplatny_email"];
            $result = false;
            $result_array[] = array('msg' => $msg);
          }
          break;
        case "gdpr_suhlas":
          if (!$z["val_1"]==1)
          {
            $msg = $lang[$_SESSION["site"]["global"]["region"]]["Neplatny_gdpr_suhlas"];
            $result = false;
            $result_array[] = array('msg' => $msg);
          }
          break;
      }
    }
    if ($result===false)
    {
      return array('result' => false, 'error' => $result_array);
    }
    else
    {
      return array('result' => true);
    }
  }
  
  function isEmailValid($email)
  {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        return true;
    }
    return false;
  }
  
  function checkPermission($modul,$id=0)
  {
    switch($modul)
    {
      case 'designer-delete':
        return $this->checkP_Designer_delete($id);
        break;
      case 'designer-portfolio-delete':
        return $this->checkP_DesignerPortfolio_delete($id);
        break;
      case 'designer-portfolio-gallery-delete':
        return $this->checkP_DesignerPortfolioGallery_delete($id);
        break;
      case 'influencer-delete':
        return $this->checkP_Influencer_delete($id);
        break;
    }
  }
  
  function checkP_Designer_delete($id)
  {
    if ($_SESSION["admin"]["auth"]["id_type"]==USER_TYPE_ADMIN)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  
  function checkP_DesignerPortfolioGallery_delete($id)
  {
    if ($_SESSION["admin"]["auth"]["id_type"]==USER_TYPE_ADMIN)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  
  function checkP_DesignerPortfolio_delete($id)
  {
    if ($_SESSION["admin"]["auth"]["id_type"]==USER_TYPE_ADMIN)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  
  function checkP_Influencer_delete($id)
  {
    if ($_SESSION["admin"]["auth"]["id_type"]==USER_TYPE_ADMIN)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  
  function deleteRecord($modul, $id)
  {
    switch($modul)
    {
      case 'designer':
        return $this->deleteAccount_Designer($id);
        break;
      case 'designer-portfolio':
        return $this->deleteRecord_DesignerPortfolio($id);
        break;
      case 'designer-portfolio-gallery':
        return $this->deleteRecord_DesignerPortfolioGallery($id);
        break;
    }
  }
  
  function deleteAccount_Designer($id)
  {
    global $db_pdo;
    global $log;
    
    $db_pdo->beginTransaction();
    $db_success = true;
    
    // vymazeme ucet
    
    $sql = "UPDATE ".TBL_USER." SET deleted = :deleted, ts_deleted = :ts_deleted "
            . "WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':deleted', 1, PDO::PARAM_INT);
    $stmt->bindValue(':ts_deleted', date("Y-m-d H:i:s"), PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
    
    
    if ($db_success===true)
    {
      // vymazeme portfolio
      
      $sql = "SELECT * FROM ".TBL_PORTFOLIO." WHERE id_user = :id";
      $stmt = $db_pdo->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount()>0)
      {
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($records as $record)
        {
          if (file_exists("../../files/img/portfolio/".$record["file"]))
          {
            rename("../../files/img/portfolio/".$record["file"], "../../files/deleted/".$record["file"]);
          }
          if (file_exists("../../files/img/portfolio/t-".$record["file"]))
          {
            unlink("../../files/img/portfolio/t-".$record["file"]);
          }
          
          // vymazeme portfolio - galeriu
      
          $sql = "SELECT * FROM ".TBL_P_GALLERY." WHERE id_portfolio = :id_portfolio";
          $stmt = $db_pdo->prepare($sql);
          $stmt->bindValue(':id_portfolio', $record["id"], PDO::PARAM_INT);
          $stmt->execute();
          if ($stmt->rowCount()>0)
          {
            $records_gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($records_gallery as $record_gallery)
            {
              if (file_exists("../../files/img/portfolio/".$record_gallery["file"]))
              {
                rename("../../files/img/portfolio/".$record_gallery["file"], "../../files/deleted/".$record_gallery["file"]);
              }
              if (file_exists("../../files/img/portfolio/t-".$record_gallery["file"]))
              {
                unlink("../../files/img/portfolio/t-".$record_gallery["file"]);
              }
              
              $id_log = $log->createEntry($_SESSION["admin"]["auth"]["id_user"],$_SESSION["admin"]["auth"]["id_type"],date("Y-m-d H:i:s"),LOG_TYPE_DEL,SITE_BE);
              $zaznam = $this->getLogStringFromArray($record_gallery);
              $zaznam = '<strong>TBL_P_GALLERY</strong><br />'.$zaznam;
              $log->addDeleteEntry($id_log, 'TBL_P_GALLERY', $record_gallery["id"], 'vymazanie záznamu', 1, $zaznam);
            }
            $sql = "DELETE FROM ".TBL_P_GALLERY." WHERE id_portfolio = :id_portfolio";
            $stmt = $db_pdo->prepare($sql);
            $stmt->bindValue(':id_portfolio', $record["id"], PDO::PARAM_INT);
            $stmt->execute();
          }
          
          $id_log = $log->createEntry($_SESSION["admin"]["auth"]["id_user"],$_SESSION["admin"]["auth"]["id_type"],date("Y-m-d H:i:s"),LOG_TYPE_DEL,SITE_BE);
          $zaznam = $this->getLogStringFromArray($record);
          $zaznam = '<strong>TBL_PORTFOLIO</strong><br />'.$zaznam;
          $log->addDeleteEntry($id_log, 'TBL_PORTFOLIO', $record["id"], 'vymazanie záznamu', 1, $zaznam);
        }
        $sql = "DELETE FROM ".TBL_PORTFOLIO." WHERE id_user = :id";
        $stmt = $db_pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
      }
    }
    
    if ($db_success===false)
    {
      $db_pdo->rollBack();
      return false;
    }
    else 
    {
      $db_pdo->commit();
      return true;
    }
  }
  
  function deleteRecord_DesignerPortfolio($id)
  {
    global $db_pdo;
    global $log;
    
    $db_pdo->beginTransaction();
    $db_success = true;
    
    // nacitame zaznam - portfolio
    
    $sql = "SELECT * FROM ".TBL_PORTFOLIO." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // vymazeme zaznam
    
    $sql = "DELETE FROM ".TBL_PORTFOLIO." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
    
    // nacitame zaznamy - portfolio - galeria
    
    $sql = "SELECT * FROM ".TBL_P_GALLERY." WHERE id_portfolio = :id_portfolio";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id_portfolio', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
    $records_gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $sql = "DELETE FROM ".TBL_P_GALLERY." WHERE id_portfolio = :id_portfolio";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id_portfolio', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
        
    if ($db_success===false)
    {
      $db_pdo->rollBack();
      return false;
    }
    else 
    {
      $db_pdo->commit();
      
      if (file_exists("../../files/img/portfolio/".$record["file"]))
      {
        rename("../../files/img/portfolio/".$record["file"], "../../files/deleted/".$record["file"]);
      }

      if (file_exists("../../files/img/portfolio/t-".$record["file"]))
      {
        unlink ("../../files/img/portfolio/t-".$record["file"]);
      }
      
      foreach($records_gallery as $record_gallery)
      {
        if (file_exists("../../files/img/portfolio/".$record_gallery["file"]))
        {
          rename("../../files/img/portfolio/".$record_gallery["file"], "../../files/deleted/".$record_gallery["file"]);
        }

        if (file_exists("../../files/img/portfolio/t-".$record_gallery["file"]))
        {
          unlink ("../../files/img/portfolio/t-".$record_gallery["file"]);
        }
        
        $id_log = $log->createEntry($_SESSION["admin"]["auth"]["id_user"],$_SESSION["admin"]["auth"]["id_type"],date("Y-m-d H:i:s"),LOG_TYPE_DEL,SITE_BE);
        $zaznam = $this->getLogStringFromArray($record_gallery);
        $zaznam = '<strong>TBL_P_GALLERY</strong><br />'.$zaznam;
        $log->addDeleteEntry($id_log, 'TBL_P_GALLERY', $record_gallery["id"], 'vymazanie záznamu', 1, $zaznam);
      }
      
      return true;
    }
  }
  
  function deleteRecord_DesignerPortfolioGallery($id)
  {
    global $db_pdo;
    
    $db_pdo->beginTransaction();
    $db_success = true;
    
    // nacitame zaznam
    
    $sql = "SELECT * FROM ".TBL_P_GALLERY." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // vymazeme zaznam
    
    $sql = "DELETE FROM ".TBL_P_GALLERY." WHERE id = :id";
    $stmt = $db_pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()==0)
    {
      $db_success = false;
    }
    
    
    if ($db_success===false)
    {
      $db_pdo->rollBack();
      return false;
    }
    else 
    {
      $db_pdo->commit();
      
      if (file_exists("../../files/img/portfolio/".$record["file"]))
      {
        rename("../../files/img/portfolio/".$record["file"], "../../files/deleted/".$record["file"]);
      }

      if (file_exists("../../files/img/portfolio/t-".$record["file"]))
      {
        unlink ("../../files/img/portfolio/t-".$record["file"]);
      }
      
      return true;
    }
  }
  
  function porovnajRozdiel($porovnat_rozdiel)
  {
    $zmena = 0;
    $i = 0;
    $rozdiel = '';
    $konflikt = array();
    foreach($porovnat_rozdiel as $p_r)
    {
      if ($p_r["valA"]!=$p_r["valB"])
      {
        $zmena++;
        
        if (strlen(trim($p_r["valA"]))==0)
        {
          $p_r["valA"] = '&nbsp;';
        }
        
        if (strlen(trim($p_r["valB"]))==0)
        {
          $p_r["valB"] = '&nbsp;';
        }
        
        $rozdiel .= $p_r["val"].': <span class="flag3">'.$p_r["valA"].'</span> &gt; <span class="flag1">'.$p_r["valB"].'</span>'.PHP_EOL;
        
      }
    }
    
    return array('zmena' => $zmena, 'rozdiel' => $rozdiel);
  }
  
  function getCodeTable($code)
  {
    $cache = "";
    for ($i=0;$i<strlen($code);$i++)
    {
      $char = substr($code,$i,1);
      $cache .= '<div class="code_cell">'.$char.'</div>';
    }
    return $cache;
  }

  function exportToXLS($file,$output)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=".$file);
        header("Content-Transfer-Encoding: binary");
        //echo chr(255).chr(254).$output;
        echo $output;
    }
  
  function categoryReCount()
    {
        global $db_pdo;
        
        $sql = "SELECT * FROM ".TBL_CATEGORY." ORDER BY id";
        $stmt = $db_pdo->prepare($sql);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($records as $record)
        {
            $sql = "SELECT count(".TBL_PRODUCT_CATEGORY.".id) as pocet FROM ".TBL_PRODUCT_CATEGORY.", ".TBL_PRODUCT." WHERE ".TBL_PRODUCT_CATEGORY.".id_product = ".TBL_PRODUCT.".id AND ".TBL_PRODUCT.".status = :status AND ".TBL_PRODUCT_CATEGORY.".id_category = :id_category";
            $stmt = $db_pdo->prepare($sql);
            $stmt->bindValue(':status', 1, PDO::PARAM_INT);
            $stmt->bindValue(':id_category', $record["id"], PDO::PARAM_INT);
            $stmt->execute();
            $pocet = $stmt->fetch(PDO::FETCH_ASSOC);
                        
            $sql = "UPDATE ".TBL_CATEGORY." SET ads_count = :ads_count WHERE id = :id";
            $stmt = $db_pdo->prepare($sql);
            $stmt->bindValue(':ads_count', $pocet["pocet"], PDO::PARAM_INT);
            $stmt->bindValue(':id', $record["id"], PDO::PARAM_INT);
            $stmt->execute();
        }
    }
  
  function convertStringToDate($value,$in,$out)
  {
    switch($in)
    {
      case "DDMMYYYY":
        switch($out)
        {
          case "YYYY-MM-DD":
            $tmp = substr($value,4,4)."-".substr($value,2,2)."-".substr($value,0,2);
            return $tmp;
            break;
          default :
            return false;
            break;
        }
        break;
      default :
        return false;
        break;
    }
  }
  
  function getIP()
  {
    echo "HTTP_CLIENT_IP:".$_SERVER['HTTP_CLIENT_IP']."<br>";
    echo "HTTP_X_FORWARDED_FOR:".$_SERVER['HTTP_X_FORWARDED_FOR']."<br>";
    echo "REMOTE_ADDR:".$_SERVER['REMOTE_ADDR']."<br>";
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
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
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return ip2long($ip); // pre dekodovani: SELECT INET_NTOA(ip) FROM ...
  }
  
    function zpracuj_fotku($src,$width,$height,$dest)
    {
        if (file_exists($src) && isset($dest))
        {
            $srcSize  = getImageSize($src);

            if ($srcSize[0] > $srcSize[1])
            {
                // landscape
                $ratio = $srcSize[1] / $height;
                $tmp_src_width = $srcSize[1];
                $tmp_src_height = $srcSize[1];
            }
            else
            {
                // portrait 
                $ratio = $srcSize[0] / $width;
                $tmp_src_width = $srcSize[0];
                $tmp_src_height = $srcSize[0];
            }
      
      
            $destImage = imageCreateTrueColor($width,$height);

            switch ($srcSize[2])
            {
                case 1: //GIF
                    $srcImage = imageCreateFromGif($src);
                break;
                case 2: //JPEG
                    $srcImage = imageCreateFromJpeg($src);
                break;
                case 3: //PNG
                    $srcImage = imageCreateFromPng($src);
                break;
                default:
                    return false;
                break;
            }

            imageCopyResampled($destImage, $srcImage, 0, 0, 0, 0,$width,$height,$tmp_src_width,$tmp_src_height);

            // generating image
            imagePng($destImage,$dest);

            return true;
            close($destImage);
            chmod($dest, 0777);
        }
        else
        {
            return false;
        }
    }
    
function make_seo_string($uri, $maxlength = false, $separator = "-")
{
    if (defined('ICONV_IMPL') && ICONV_IMPL != 'libiconv') {
        static $table = array(
            "\xc3\xa1"=>"a","\xc3\xa4"=>"a","\xc4\x8d"=>"c","\xc4\x8f"=>"d","\xc3\xa9"=>"e",
            "\xc4\x9b"=>"e","\xc3\xad"=>"i","\xc4\xbe"=>"l","\xc4\xba"=>"l","\xc5\x88"=>"n",
            "\xc3\xb3"=>"o","\xc3\xb6"=>"o","\xc5\x91"=>"o","\xc3\xb4"=>"o","\xc5\x99"=>"r",
            "\xc5\x95"=>"r","\xc5\xa1"=>"s","\xc5\xa5"=>"t","\xc3\xba"=>"u","\xc5\xaf"=>"u",
            "\xc3\xbc"=>"u","\xc5\xb1"=>"u","\xc3\xbd"=>"y","\xc5\xbe"=>"z","\xc3\x81"=>"A",
            "\xc3\x84"=>"A","\xc4\x8c"=>"C","\xc4\x8e"=>"D","\xc3\x89"=>"E","\xc4\x9a"=>"E",
            "\xc3\x8d"=>"I","\xc4\xbd"=>"L","\xc4\xb9"=>"L","\xc5\x87"=>"N","\xc3\x93"=>"O",
            "\xc3\x96"=>"O","\xc5\x90"=>"O","\xc3\x94"=>"O","\xc5\x98"=>"R","\xc5\x94"=>"R",
            "\xc5\xa0"=>"S","\xc5\xa4"=>"T","\xc3\x9a"=>"U","\xc5\xae"=>"U","\xc3\x9c"=>"U",
            "\xc5\xb0"=>"U","\xc3\x9d"=>"Y","\xc5\xbd"=>"Z"
        );
        $url = $uri;
        //setlocale(LC_CTYPE,0).'-';
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = strtr($url, $table);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        return $url;
    }
    $url = $uri;
    //setlocale(LC_CTYPE,0).'-';
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    //$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = iconv("utf-8", "ASCII//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
    }
    
    function image_createThumb($src,$dest,$maxWidth,$maxHeight,$quality,$type)
    {
      if (file_exists($src)  && isset($dest))
      {
        $destInfo  = pathInfo($dest);
        $srcSize  = getImageSize($src);
        // image dest size $destSize[0] = width, $destSize[1] = height

        switch($type)
        {
          case "AA":
              $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio
              $destRatio = $maxWidth/$maxHeight;
              if ($destRatio > $srcRatio)
              {
                  $destSize[1] = $maxHeight;
                  $destSize[0] = $maxHeight*$srcRatio;
                  $orientation = "portrait";

                  $startX = ($maxWidth - $destSize[0]) / 2;
                  $startY = 0;
              }
              else
              {
                  $destSize[0] = $maxWidth;
                  $destSize[1] = $maxWidth/$srcRatio;
                  $orientation = "landscape";

                  $startY = ($maxHeight - $destSize[1]) / 2;
                  $startX = 0;
              }
              // path rectification
              /*
              if ($destInfo['extension'] == "gif")
              {
                $dest = substr_replace($dest, 'jpg', -3);
              }
              */
              // true color image, with anti-aliasing
              $destImage = imageCreateTrueColor($maxWidth,$maxHeight);
              //imageAntiAlias($destImage,true);

              //set background colour
              $bg = ImageColorAllocate($destImage,255,255,255);
              ImageFilledRectangle ($destImage, 0, 0, $maxWidth, $maxHeight, $bg);

              // src image
              switch ($srcSize[2])
              {
                  case 1: //GIF
                    $srcImage = imageCreateFromGif($src);
                  break;
                  case 2: //JPEG
                    $srcImage = imageCreateFromJpeg($src);
                  break;
                  case 3: //PNG
                    $srcImage = imageCreateFromPng($src);
                  break;
                  default:
                    return false;
                  break;
              }
              // resampling
              imageCopyResampled($destImage, $srcImage, $startX, $startY, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);

              break;
          case "AA2":
              $destInfo  = pathInfo($dest);
              $srcSize  = getImageSize($src);
              // image dest size $destSize[0] = width, $destSize[1] = height

              $sW = $srcSize[0];
              $sH = $srcSize[1];

              if ($sW > $sH)
              {
                //landscape
                $sW2 = $sH;
                $sH2 = $sH;
              }
              else
              {
                //portratit
                $sW2 = $sW;
                $sH2 = $sW;
              }
              $sX = ($sW - $sW2) / 2;
              $sY = ($sH - $sH2) / 2;

              $destSize[1] = $maxHeight;
              $destSize[0] = $maxWidth;
              // path rectification
              /*
              if ($destInfo['extension'] == "gif")
              {
                $dest = substr_replace($dest, 'jpg', -3);
              }
              */
              // true color image, with anti-aliasing
              $destImage = imageCreateTrueColor($maxWidth,$maxHeight);
              //imageAntiAlias($destImage,true);

              //set background colour
              $bg = ImageColorAllocate($destImage,255,255,255);
              ImageFilledRectangle ($destImage, 0, 0, $maxWidth, $maxHeight, $bg);

              // src image
              switch ($srcSize[2])
              {
                  case 1: //GIF
                    $srcImage = imageCreateFromGif($src);
                  break;
                  case 2: //JPEG
                    $srcImage = imageCreateFromJpeg($src);
                  break;
                  case 3: //PNG
                    $srcImage = imageCreateFromPng($src);
                  break;
                  default:
                    return false;
                  break;
              }
              // resampling
              imageCopyResampled($destImage, $srcImage, 0, 0, $sX, $sY,$destSize[0],$destSize[1],$sW2,$sH2);


              break;
          case "AB":
              $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio
              $destRatio = $maxWidth/$maxHeight;
              if ($destRatio > $srcRatio)
              {
                $destSize[1] = $maxHeight;
                $destSize[0] = $maxHeight*$srcRatio;
              }
              else
              {
                $destSize[0] = $maxWidth;
                $destSize[1] = $maxWidth/$srcRatio;
              }

              // true color image, with anti-aliasing
              $destImage = imageCreateTrueColor($destSize[0],$destSize[1]);  

              // src image
              switch ($srcSize[2])
              {
                  case 1: //GIF
                    $srcImage = imageCreateFromGif($src);
                  break;
                  case 2: //JPEG
                    $srcImage = imageCreateFromJpeg($src);
                  break;
                  case 3: //PNG
                    $srcImage = imageCreateFromPng($src);
                    imagealphablending($destImage,false);
                    imagesavealpha($destImage, true);
                    imagecolortransparent($destImage);
                  break;
                  default:
                    return false;
                  break;
              }
              // resampling
              imageCopyResampled($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);
              break;
          case "AAAB":
            // upravime velkost a vytvorime biely stvorec do ktoreho upravenu fotku vlozime na stred
              $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio
              $destRatio = $maxWidth/$maxHeight;
              if ($destRatio > $srcRatio)
              {
                $destSize[0] = $maxHeight*$srcRatio;
                $destSize[1] = $maxHeight;
                $startX = ($maxWidth - $destSize[0]) / 2;
                $startY = 0;
              }
              else
              {
                $destSize[0] = $maxWidth;
                $destSize[1] = $maxWidth/$srcRatio;
                $startX = 0;
                $startY = ($maxHeight - $destSize[1]) / 2;
              }

              // true color image, with anti-aliasing
              $destImage = imageCreateTrueColor($maxWidth,$maxHeight);  

              //set background colour
              $bg = ImageColorAllocate($destImage,255,255,255);
              ImageFilledRectangle ($destImage, 0, 0, $maxWidth, $maxHeight, $bg);

              // src image
              switch ($srcSize[2])
              {
                  case 1: //GIF
                    $srcImage = imageCreateFromGif($src);
                  break;
                  case 2: //JPEG
                    $srcImage = imageCreateFromJpeg($src);
                  break;
                  case 3: //PNG
                    $srcImage = imageCreateFromPng($src);
                  break;
                  default:
                    return false;
                  break;
              }
              // resampling
              imageCopyResampled($destImage, $srcImage, $startX, $startY, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]);

              break;
          case "ABAB":
              $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio
              $destRatio = $maxWidth/$maxHeight;
              //echo $srcSize[0].', '.$srcSize[1].' : '.$maxWidth.', '.$maxHeight.'<br>';
              $destSize[0] = $maxWidth;
              $destSize[1] = $maxHeight;
              if ($destRatio < $srcRatio)
              {
                  //echo "ratio ($destRatio, $srcRatio): A<br>";
                  $tmpH = $srcSize[1];
                  $tmpW = round($tmpH * ($maxWidth/$maxHeight));
                  $startX = round(($srcSize[0]-$tmpW) / 2);
                  $startY = 0;
              }
              else
              {
                  //echo "ratio ($destRatio, $srcRatio): B<br>";
                  $tmpW = $srcSize[0];
                  //$tmpH = $srcSize[1]/($srcSize[0]/$maxWidth);
                  $tmpH = round($maxHeight * ($srcSize[0]/$maxWidth));
                  $startX = 0;
                  $startY = round(($srcSize[1]-$tmpH) / 2);
              }

              // true color image, with anti-aliasing
              $destImage = imageCreateTrueColor($destSize[0],$destSize[1]);  

              // src image
              switch ($srcSize[2])
              {
                  case 1: //GIF
                    $srcImage = imageCreateFromGif($src);
                  break;
                  case 2: //JPEG
                    $srcImage = imageCreateFromJpeg($src);
                  break;
                  case 3: //PNG
                    $srcImage = imageCreateFromPng($src);
                  break;
                  default:
                    return false;
                  break;
              }
              //echo "imageCopyResampled($destImage, $srcImage, 0, 0, $startX, $startY,$destSize[0],$destSize[1],$tmpW,$tmpH);";
              //imageCopyResampled(Resource id #16, Resource id #18, 0, 49.875, 0, 0,100,75,399,299.25); 399 x 399
              imageCopyResampled($destImage, $srcImage, 0, 0, $startX, $startY,$destSize[0],$destSize[1],$tmpW,$tmpH);

              break;
        }


        // generating image
        switch ($srcSize[2])
        {
          case 1:
            imagegif($destImage,$dest);
          case 2:
            imageJpeg($destImage,$dest,$quality);
          break;
          case 3:
            imagePng($destImage,$dest);
          break;
        }
        return true;
        close($destImage);
        chmod($dest, 0777);
      }
      else
      {
        return false;
      }
  }
    
    function del_check($table)
    {
        switch ($table)
        {
            case "krajina":
                $sql = "SELECT * FROM ".TBL_PR_KOMPARZ_KRAJINA." WHERE id_jazyk = :id_jazyk";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_jazyk', $g_id, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount()>0)
                {
                    return 0;
                }
                $sql = "SELECT * FROM ".TBL_PR_MODEL_KRAJINA." WHERE id_jazyk = :id_jazyk";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_jazyk', $g_id, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount()>0)
                {
                    return 0;
                }
                $sql = "SELECT * FROM ".TBL_PR_HOSTESKA_KRAJINA." WHERE id_jazyk = :id_jazyk";
                $stmt = $db_pdo->prepare($sql);
                $stmt->bindValue(':id_jazyk', $g_id, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount()>0)
                {
                    return 0;
                }
                return 1;
                break;
        }
    }
    
    function odosliSMS($telefon,$sms_msg)
    {
        $path = $_SERVER['DOCUMENT_ROOT'];
        $path .= "classes/sms.php";
        include_once($path);
        $sms = new SMS(SMS_KEY,SMS_ID);      
        $path_log = $_SERVER['DOCUMENT_ROOT'];
        $path_log .= "data/log_sms.txt";
        $file_handle = fopen($path_log,"a");
        
        $sms_text = str_replace(' ', '%20', $sms_msg);
        fwrite($file_handle, date("Y-m-d H:i:s").PHP_EOL);
        $r = $sms->send_message($telefon, $sms_text, 0);
        fwrite($file_handle, $telefon.", '".$text.", status: ".$r.PHP_EOL);
        fclose($file_handle);
        return $r;
    }
}
?>