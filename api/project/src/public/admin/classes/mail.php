<?php
class htmlMail
{
    public function __construct() {
    }
    
    function mailSignUp($par)
    {  
      global $lang;
      
      $cache = '<table style="width:100%;" cellpadding="5" cellspacing="0">'
              . '<tr>'
              . '<td colspan="2" style="text-align:center;">'
              . '<strong style="color: #23395B; font-size: 18px;">'.$par['nazov'].'</strong>'
              . '</td>'
              . '</tr>'
              . '<tr>'
              . '<td style="text-align:right;">'
              . $lang[$par["lang"]]["Prihlasovacie_meno"]
              . '</td>'
              . '<td style="text-align:left;">'
              . $par["email"]
              . '</td>'
              . '</tr>'
              . '<tr>'
              . '<td style="text-align:right;">'
              . $lang[$par["lang"]]["Prihlasovacie_heslo"]
              . '</td>'
              . '<td style="text-align:left;">'
              . $par["password"]
              . '</td>'
              . '</tr>'
              . '<tr>'
              . '<td style="text-align:right;">'
              . $lang[$par["lang"]]["Cas_registracie"]
              . '</td>'
              . '<td style="text-align:left;">'
              . $par["ts"]
              . '</td>'
              . '</tr>'
              . '<tr>'
              . '<td colspan="2" style="text-align:center;">'
              . '<strong style="color: #23395B; font-size: 18px;">'.$lang[$par["lang"]]["Aktivacny_link"].'</strong>' 
              . '<div style="text-align:center;"><a href="'.DIR_WWW_ROOT.'activate-account/'.urlencode($par["email"]).'/'.$par["hash"].'/'.$par["id"].'/" style="background-color: #23395B; padding: 16px 32px; color: white; display: inline-block; margin: 20px 0px; text-decoration: none;">'.$lang[$par["lang"]]["Aktivovat_ucet"].'</a></div>'
              . '</td>'
              . '</tr>'
              . '</table>';
      
      $mail_data["result"] = "ok";
      $mail_data["subject"] = $lang[$par["lang"]]["Novy_ucet"].': '.$par["nazov"];
      $mail_data["html_mail"] = $cache;
      $mail_data["text_mail"] = $lang[$par["lang"]]["Novy_ucet"].': '.$par["nazov"].' - '.MAIL_DISCL.' - Použite emailového klienta s podporou HTML';
      $mail_data["emails"]["to"][] = $par["email"];
      $mail_data["header"] = $par["header"];
      if (strlen(trim(MAIL_TEST_RECEIVER))>5)
      {
        $mail_data["emails"]["bcc"][] = MAIL_TEST_RECEIVER;
      }
      
      return $mail_data;
    }
    
    function mailDistributor($par)
    {  
      $mail_data["result"] = "ok";
      $mail_data["html_mail"] = $par["email_content"];
      $mail_data["text_mail"] = 'Distribútor - '.MAIL_DISCL.' - Použite emailového klienta s podporou HTML';
      $mail_data["emails"]["to"][] = MAIL_ORDER_RECEIVER;
      if (strlen(trim(MAIL_TEST_RECEIVER))>5)
      {
        $mail_data["emails"]["bcc"][] = MAIL_TEST_RECEIVER;
      }
      $mail_data["subject"] = 'Distribútor: '.$par["ts"];
      return $mail_data;
    }
    
    
    function getBrowser() 
    {
        global $user_agent;
        $browser = "neznámy";
        $browser_array  =   array(
                         '/msie/i'       =>  'Internet Explorer',
                         '/firefox/i'    =>  'Firefox',
                         '/safari/i'     =>  'Safari',
                         '/chrome/i'     =>  'Chrome',
                         '/opera/i'      =>  'Opera',
                         '/netscape/i'   =>  'Netscape',
                         '/maxthon/i'    =>  'Maxthon',
                         '/konqueror/i'  =>  'Konqueror',
                         '/mobile/i'     =>  'Handheld Browser'
                   );

        foreach ($browser_array as $regex => $value) 
        {
            if (preg_match($regex, $user_agent)) 
            {
                $browser = $value;
            }
        }
        return $browser;
    }
  
}
?>
