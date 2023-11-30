<?php
class SMS {
  var $var_key = "";
  var $var_ID = "";
  
  function __construct($key, $ID)
  {
    $this->var_key = $key;  
    $this->var_ID = $ID;
  }

    function send_message ($phone, $message)
    {
        $md5 = md5($this->var_key.$phone); 
        $key=substr($md5,10,11); 
        $url='http://as.eurosms.com/sms/Sender?action=send1SMSHTTP&i='.$this->var_ID.'&s='.$key.'&d=1&sender='.SMS_SENDER.'&number='.$phone.'&msg='.$message; 
        $result = file_get_contents($url);

        return $result;
    }   
}
?>
