<?php
class sec
{
  function cleanInput($input) {

    $search = array(
      '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
      '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
      '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
      '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

      $output = preg_replace($search, '', $input);
      return $output;
    }

  function sanitize($input) {
      if (is_array($input)) {
          foreach($input as $var=>$val) {
              $output[$var] = $this->sanitize($val);
          }
      }
      else {
          if (get_magic_quotes_gpc()) {
              $input = stripslashes($input);
          }
          $output  = $this->cleanInput($input);
          //$output = mysql_real_escape_string($input);
      }
      return $output;
  }

  function csrf_token() {
    return md5(uniqid(rand(), TRUE));
  }

  // Generate and store CSRF token in user session.
  // Requires session to have been started already.
  function create_csrf_token() {
    $token = $this->csrf_token();
    $_SESSION['csrf_token'] = $token;
    $_SESSION['csrf_token_time'] = time();
//    $file = "log_sec.txt";
//    $file_handle = fopen($file,"a");
//    fwrite($file_handle, date("Y-m-d H:i:s").PHP_EOL);
//    fwrite($file_handle, $_SESSION['csrf_token'].", ".$_SESSION['csrf_token_time'].PHP_EOL);
//    fclose($file_handle);
    return $token;
  }

  // Destroys a token by removing it from the session.
  function destroy_csrf_token() {
    $_SESSION['csrf_token'] = null;
    $_SESSION['csrf_token_time'] = null;
    return true;
  }

  // Return an HTML tag including the CSRF token
  // for use in a form.
  // Usage: echo csrf_token_tag();
  function csrf_token_tag() {
    $token = $this->create_csrf_token();
    return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\">";
  }

  // Returns true if user-submitted POST token is
  // identical to the previously stored SESSION token.
  // Returns false otherwise.
  function csrf_token_is_valid() {
    if(isset($_POST['csrf_token'])) {
      $user_token = $_POST['csrf_token'];
      $stored_token = $_SESSION['csrf_token'];
      return $user_token === $stored_token;
    } else {
      return false;
    }
  }

  // You can simply check the token validity and
  // handle the failure yourself, or you can use
  // this "stop-everything-on-failure" function.
  function die_on_csrf_token_failure() {
    if(!csrf_token_is_valid()) {
      die("CSRF token validation failed.");
    }
  }

  // Optional check to see if token is also recent
  function csrf_token_is_recent() {
    $max_elapsed = MAX_INACTIVITY;
    if(isset($_SESSION['csrf_token_time'])) {
      $stored_time = $_SESSION['csrf_token_time'];
      return ($stored_time + $max_elapsed) >= time();
    } else {
      // Remove expired token
      destroy_csrf_token();
      return false;
    }
  }

  function request_is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
  }

  function request_is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

//  function request_is_same_domain() {
//    if(!isset($_SERVER['HTTP_REFERER'])) {
//      // No refererer sent, so can't be same domain
//      return false;
//    } else {
//      $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
//      $server_host = $_SERVER['HTTP_HOST'];
//
//      // Uncomment for debugging
//      // echo 'Request from: ' . $referer_host . "<br />";
//      // echo 'Request to: ' . $server_host . "<br />";
//
//      return ($referer_host == $server_host) ? true : false;
//    }
//  }

    function request_is_same_domain() {
    if(!isset($_SERVER['HTTP_REFERER'])) {
      // No refererer sent, so can't be same domain
        if ($server_host=='projects.net')
        {
           return true;
        }
        else
        {
          return false;
        }

    } else {
      $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
      $server_host = $_SERVER['HTTP_HOST'];

      // Uncomment for debugging

      if ($server_host=='projects.net')
      {
         return true;
      }
      else
      {
        return ($referer_host == $server_host) ? true : false;
      }
    }
  }

  function checkPostForm()
  {
    $request_err = 0;
    if($this->request_is_post())
    {
      if ($this->request_is_same_domain())
      {
        if($this->csrf_token_is_valid())
        {
          if($this->csrf_token_is_recent())
          {
            $request_err = 1;
          }
          else
          {
            $request_err = "token_expired";
          }
        }
        else
        {
          $request_err = "token_mismatch";
        }
      }
      else
      {
        $request_err = "invalid_domain_request";
      }
    }
    else
    {
      $request_err = "invalid_request";
    }
    return $request_err;
  }

  function end_session($modul)
  {
    // Use both for compatibility with all browsers
    // and all versions of PHP.

    global $log;
    global $lang;

    $_SESSION["admin"]["msg"]["status"] = true;
    $_SESSION["admin"]["msg"]["nadpis"] = 'Odhlásenie';
    $_SESSION["admin"]["msg"]["popis"] = 'Odhlásenie z dôvodu nečinnosti';
    $_SESSION["admin"]["msg"]["scope"] = 'logout';
    $_SESSION["admin"]["msg"]["back_url"] = DIR_WWW_ROOT.'app/index.html';
    unset($_SESSION[$modul]["auth"]);
    unset($_SESSION[$modul]["form"]);
    HEADER("Location: ".DIR_WWW_ROOT."msg/i/account/logout.html");
    exit();
  }

  // Does the request IP match the stored value?
  function request_ip_matches_session($module) {
    // return false if either value is not set
    if(!isset($_SESSION[$module]['auth_ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
      return false;
    }
    if($_SESSION[$module]['auth_ip'] === $_SERVER['REMOTE_ADDR']) {
      return true;
    } else {
      return false;
    }
  }

  // Does the request user agent match the stored value?
  function request_user_agent_matches_session($module) {
    // return false if either value is not set
    if(!isset($_SESSION[$module]['auth_user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
      return false;
    }
    if($_SESSION[$module]['auth_user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
      return true;
    } else {
      return false;
    }
  }

  // Has too much time passed since the last login?
  function last_login_is_recent($module) {
    $max_elapsed = $_SESSION[$module]['auth']['max_idle_time'];
    // return false if value is not set
    if(!isset($_SESSION[$module]['auth']['sess_time'])) {
      return false;
    }
    if(($_SESSION[$module]['auth']['sess_time'] + $max_elapsed) >= time()) {
      return true;
    } else {
      return false;
    }
  }

  // Should the session be considered valid?
  function is_session_valid($module) {
    $check_ip = true;
    $check_user_agent = true;
    $check_last_login = true;

//    if($check_ip && !$this->request_ip_matches_session($module)) {
//      $_SESSION["global_err_scope"]="login";
//      $_SESSION["global_err"]="request_ip_matches_session";
//      return false;
//    }
//    if($check_user_agent && !$this->request_user_agent_matches_session($module)) {
//      $_SESSION["global_err_scope"]="login";
//      $_SESSION["global_err"]="request_user_agent_matches_session";
//      return false;
//    }
    if($check_last_login && !$this->last_login_is_recent($module)) {
      return false;
    }
    $_SESSION[$module]["auth"]["sess_time"] = time();
    return true;
  }

  // If session is not valid, end and redirect to login page.
  function confirm_session_is_valid($module)
  {
    global $lang;

    if(!$this->is_session_valid($module))
    {
      $this->end_session($module);
      if ($module=="admin")
      {
        $zaznam = ' <strong>'.$_SESSION["admin"]["auth"]["email"].'</strong> z dôvodu nečinnosti';
        $id_log = $log->createEntry($_SESSION["admin"]["auth"]["id_user"],2,date("Y-m-d H:i:s"),1,1);
        $log->addLogOutEntry($id_log, '###C:odhlásenie servisu:###'.$zaznam);
        $_SESSION["admin"]["err"]["status"] = true;
        $_SESSION["admin"]["err"]["nadpis"] = $lang[$_SESSION["admin"]["global"]["region"]]["odhlasenie"];
        $_SESSION["admin"]["err"]["popis"] = $lang[$_SESSION["admin"]["global"]["region"]]["necinnost_odhlasenie"];
        $_SESSION["admin"]["err"]["scope"] = 'necinnost-odhlasenie';
        HEADER("Location: ".DIR_WWW_ROOT."msg/i/necinnost-odhlasenie/prihlasenie.html");
      }
      else
      {
        $_SESSION["admin"]["err"]["status"] = true;
        $_SESSION["admin"]["err"]["nadpis"] = $lang[$_SESSION["admin"]["global"]["region"]]["odhlasenie"];
        $_SESSION["admin"]["err"]["popis"] = $lang[$_SESSION["admin"]["global"]["region"]]["necinnost_odhlasenie"];
        $_SESSION["admin"]["err"]["scope"] = 'necinnost-odhlasenie';
        HEADER("Location: ".DIR_WWW_ROOT."msg/i/necinnost-odhlasenie/prihlasenie.html");
      }
      exit;
    }
  }


  // Is user logged in already?
  function is_logged_in() {
    return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
  }

  // If user is not logged in, end redirect to login page.
  function confirm_user_logged_in($modul) {
    if(!$this->is_logged_in()) {
      $this->end_session($module);
      // Note that header redirection requires output buffering
      // to be turned on or requires nothing has been output
      // (not even whitespace).
      header("Location: ".DIR_WWW_ROOT."odhlasenie.html");
      exit;
    }
  }

  // Actions to preform after every successful logout
  function after_successful_logout($modul) {
    $this->end_session($modul);
  }

  function allowed_get_params($allowed_params = array()) {
    $allowed_array = array();
    foreach($allowed_params as $param) {
      if(isset($_GET[$param])) {
        $allowed_array[$param] = $this->sanitize($_GET[$param]);
      } else {
        $allowed_array[$param] = NULL;
      }
    }
    return $allowed_array;
    // example: $get_params = allowed_get_params(['username', 'password']);
  }

  function allowed_post_params($allowed_params = array()) {
    $allowed_array = array();
    foreach($allowed_params as $param)
    {
      if(isset($_POST[$param])) {
        $allowed_array[$param] = $this->sanitize($_POST[$param]);
      } else {
        $allowed_array[$param] = NULL;
      }
    }
    return $allowed_array;
  }

  function has_inclusion_in($value, $set=array()) {
    return in_array($value, $set);
  }

  protected $passwordMaxLength =  20;
	protected $passwordMinLength =  8;

	public function validatePassword($password)
	{
		$message = array();
		// minimum length check
		if (strlen($password) < $this->passwordMinLength) {
			$message[] = 'The password is too short';
		} elseif (strlen($password) > $this->passwordMaxLength) {
		// maximum length check
			$message[] = 'The password is too long';
		}
		// UPPERCASE
		if (!preg_match('/[A-Z]/', $password)) {
			$message[] = 'You must have at least 1 UPPERCASE letter';
		}
		// lowercase
		if (!preg_match('/[a-z]/', $password)) {
			$message[] = 'You must have at least 1 lowercase letter';
		}
		// numbers
		if (!preg_match('/[0-9]/', $password)) {
			$message[] = 'You must have at least 1 number';
		}
		// special characters
		if (!preg_match('/[^\w]/', $password)) {
			$message[] = 'You must have at least 1 special character';
		}
		return $message;
	}

  function checkPassword($pwd)
  {
    if (strlen($pwd) < 8)
    {
      $errors[] = "Password too short!";
      $err_str .= "1";
    }
    else
    {
      $err_str .= "0";
    }

    if (!preg_match("#[0-9]+#", $pwd))
    {
      $errors[] = "Password must include at least one number!";
      $err_str .= "1";
    }
    else
    {
      $err_str .= "0";
    }

    if (!preg_match("#[a-zA-Z]+#", $pwd))
    {
      $errors[] = "Password must include at least one letter!";
      $err_str .= "1";
    }
    else
    {
      $err_str .= "0";
    }
    return $err_str;
  }

  function getSalt($cost)
  {
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    // Prefix information about the hash so PHP knows how to verify it later.
    // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
    return sprintf("$2a$%02d$", $cost) . $salt;
  }

  function getActivationSalt()
  {
    return strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
  }

  /**
  * $resource - string > predstavuje URL pristup ku ktorej chceme kontrolovat
  * $rate - maximalne povoleny pocet pristupov pocas 'window'
  * $window - casovy usek v sekundach pocas ktoreho kontrolujeme maximalny pocet pristupov
  * $any_user - false == $_SERVER['REMOTE_ADDR'] (IP klienta), true == maximalny pocet pristupov kontrolujeme globalne (dobre pre DDoS prevenciu)
  */

  // cesta k suboru do ktoreho ukladame historiu (cache) pristupov
  const ACCESSCACHE_FILE = "./accessCache.txt";

  public static function accessResource($resource, $rate = 7, $window = 60, $any_user = false)
  {
    // nacitame cache zo suboru (php array)
    $cache = file_exists(self::ACCESSCACHE_FILE) ? self::varFromFile(self::ACCESSCACHE_FILE) : array();

    $user  = $any_user ? '0.0.0.0' : $_SERVER['REMOTE_ADDR'];

    // inicializacia cache premennych
    if (!isset($cache[$user]))
    {
      $cache[$user] = array();
    }
    if (!isset($cache[$user][$resource]))
    {
      $cache[$user][$resource] = array();
    }

    // timestamp aktualneho resourcu
    $cache[$user][$resource][] = time();
    // ulozime iba potrebny pocet timestamp-sov
    if (count($cache[$user][$resource]) > $rate)
    {
      array_shift($cache[$user][$resource]);
    }

    // kontrola ci resource dosiahol maximalny povoleny pocet pristupov
    $deny_access = false;
    $attempts = $cache[$user][$resource];
    if (count($attempts) < $rate)
    {
      $deny_access = false; // nedosiahol
    }
    elseif ($attempts[0] + $window > time())
    {
      $deny_access = true; // dosiahol
    }
    else
    {
      $deny_access = false; // najstarsi access nebol v povolenom okne, takze nedosiahol
    }

    // upratanie cache nech sa nenabaluje
    foreach ($cache as $ip => $resources)
    {
      foreach ($resources as $res => $attempts)
      {
        if ($attempts)
        { //if resource access zaznam existuje
          if ($attempts[count($attempts) - 1] + $window < time())
          { //ak najstarsi zaznam je starsi ako povolene okno
            unset($cache[$ip][$res]); // vymazat
          }
        }
      }
      if (!$resources)
      {
        unset($cache[$ip]); // ak IP record nema ziadne resource > vymazat
      }
    }

    //ulozime cache spat do suboru
    self::varToFile(self::ACCESSCACHE_FILE, $cache);

    if ($deny_access)
    {
      throw new Exception('Maximalny pocet poziadaviek bol dosiahnuty.');
    }
  }

  public static function varToFile($filename, $var)
  {
    $data = gzcompress(serialize($var), 9);
    return self::stringToFile($filename, $data);
  }

  public static function varFromFile($filename)
  {
    if (file_exists($filename))
    {
      $data = unserialize(gzuncompress(file_get_contents($filename)));
      if ($data)
      {
        return $data;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }

  public static function fileToString($filename)
  {
    return file_get_contents($filename);
  }

  public static function stringToFile($filename, $data)
  {
    $file = fopen($filename, "w");
    fwrite($file, $data);
    fclose($file);
    return true;
  }
}
?>
