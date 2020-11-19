<?php

/**
 * 
 * Helper functions
 * 
 */


/**
 * Generate token and return HTML
 * @return String  HTML hidden token field
 */
function token()
{
	if(!isset($_SESSION['token']))
		$_SESSION['token'] = md5(uniqid(true));

	return "<input type='hidden' name='_token' value='" . $_SESSION['token'] . "'/>";
}


/**
 * Fetch GET and POST input
 * @param  String $key 	Key of the data to fetch
 * @return Mixed     	
 */
function input($key)
{
	$input = array_merge($_GET, $_POST);

	return (isset($input[$key])) ? $input[$key] : '';
}

// session flash
function flash($key, $value = null)
{
	if(! is_null($value))
		$_SESSION['flash'][$key] = $value;
	else
	{
		if(isset($_SESSION['flash'][$key]))
		{
			$flash = $_SESSION['flash'][$key];
			unset($_SESSION['flash'][$key]);


			if($key == 'msg')
				return "<div class='msgBlue'>" . $flash . "</div>";

			if($key == 'error')
				return "<div class='msgRed'>" . $flash . "</div>";

			return $flash;
		}
			
		return false;
	}
}

// Set and get errors 
function errorFlash($key, $value = null)
{
	if(!isset($value))
	{
		$params = explode('.', $key);

		if(isset($key) && isset($_SESSION['errors'][$params[0]][$params[1]]))
		{
			$old = $_SESSION['errors'][$params[0]][$params[1]];
			unset($_SESSION['errors'][$params[0]][$params[1]]);
			return $old;
		}
		else
		{
		return false;
		}		
	}
	else
	{
		if(isset($key, $value) && is_array($value))
			$_SESSION['errors'][$key] = $value;
		else
			return false;
	}


}

// Old Input Flash
function oldInputFlash()
{
	$_SESSION['old_input'] = $_POST;
}

// Old input
function oldInput($key, $default = false)
{
	if(isset($_SESSION['old_input'][$key]))
	{
		$old = $_SESSION['old_input'][$key];

		return $old;
	}

	return $default;
}


// Windows ID thingy..

function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid =  substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);

        return $uuid;
    }
}

function sendEmail($to, $name, $subject, $html) {

	try {

	$mandrill = new Mandrill('PLNwU6T89LpbMlWTvgEKeQ');

    curl_setopt($mandrill->ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($mandrill->ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($mandrill->ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');

	$message = array(
        'html' => $html,
        'subject' => $subject,
        'from_email' => 'do_not_reply@mceinsurance.com',
        'from_name' => 'People Portal',
        'to' => array(
            array(
                'email' => $to,
                'name' => $name,
                'type' => 'to'
            )
        ),
        'tags' => array('mce-emails')
        );

	$mandrill->messages->send($message, true);

	} catch(Mandrill_Error $e) {

    	echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();

    	throw $e;
	}

}



function get_words($sentence, $count = 10) {
	preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
	return $matches[0];
}


function nl2p($string)
{
    $paragraphs = '';

    foreach (explode("\n", $string) as $line) {
        if (trim($line)) {
            $paragraphs .= '<p>' . $line . '</p>';
        }
    }

    return $paragraphs;
}

function validateDateFormat($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

