<?php
class basic
{
  var $show_errors = true;

  function generateHPList($records,$url_part = '')
  {
    $cache = '';
    foreach($records as $record) {
      $cache .= '<li><a href="'.DIR_WWW_ROOT.$url_part.'form/'.$record["topic_id"].'.html">'.$record["topic"].'</a><div><span>ID: '.$record["topic_id"].'</span>'.$record["notes"].'</div></li>';
    }
    $cache = '<ul>'.$cache.'</ul>';
    return $cache;
  }

  function checkMsgScope($scope)
  {
    if (isset($_SESSION["admin"]["msg"]["scope"])) {
      if ($scope != $_SESSION["admin"]["msg"]["scope"]) {
        unset($_SESSION["admin"]["msg"]);
        $_SESSION["admin"]["msg"]["status"] = false;
      }
    }
  }


  /* #region  function: searchForId */
  function searchForId($id, $k, $array)
  {
    foreach ($array as $key => $val) {
      if ($val[$k] === $id) {
        return $key;
      }
    }
    return null;
  }
  /* #endregion */

  // function resetOrderSession()
  // {
  //   $_SESSION["site"]["obj_zlava_kredit"] = 0;
  //   $_SESSION["site"]["obj_zlava_kod"] = '';
  //   $_SESSION["site"]["obj_zlava_kod_vyska"] = 0;
  // }


  // function zkontrolujTelefon($phone)
  // {
  //   return $this->isPhoneNumberValid($phone);
  // }

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
      return ip2long($ip); // pre dekodovanie: SELECT INET_NTOA(ip) FROM ...
    }
  }


  function getRandomString($length = 8)
  {
    $characters = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
      $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
  }


  function getMonth($str)
  {
    switch ($str) {
      case "01":
        return "január";
        break;
      case "02":
        return "február";
        break;
      case "03":
        return "marec";
        break;
      case "04":
        return "apríl";
        break;
      case "05":
        return "máj";
        break;
      case "06":
        return "jún";
        break;
      case "07":
        return "júl";
        break;
      case "08":
        return "august";
        break;
      case "09":
        return "september";
        break;
      case "10":
        return "október";
        break;
      case "11":
        return "november";
        break;
      case "12":
        return "december";
        break;
    }
  }
}
