<?php
/**
 * PHP Email Handler
 * PHP Version 5
 * @package PHP Email Handler
 * @link https://github.com/FunkyJamma/PHP-Email-Handler
 * @author Angel Mendez <angelmendez94@gmail.com>
 * @copyright 2018 Angel Mendez
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/*
This first bit sets the email address that you want the form to be submitted to and the email address it will be coming from.
You will need to change this value to a valid email address that you can access.
*/
$webmaster_email = "Receiving@Email.com"; /* Replace with receiving email */
$headers = 'From: sending@email.com' . "\r\n" . /* Replace with sending email */
   				 'Reply-To: reply@email.com' . "\r\n" . /* Replace with reply email */
   		 		 'X-Mailer: PHP/' . phpversion();
$subject = "Replace with subject"; /* Replace with subject */

/*
This bit sets the URLs of the supporting pages.
If you change the names of any of the pages, you will need to change the values here.
*/
$feedback_page = "index.html";
$error_page = "error_message.html";
$thankyou_page = "thank_you.html";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
*/
$email = $_REQUEST['email'] ;
$message = $_REQUEST['message'] ;
$name = $_REQUEST['name'] ;
$phone = $_REQUEST['phone'] ;
$msg =
"First Name: " . $name . "\r\n" .
"Email: " . $email . "\r\n" .
"Phone Number: " . $phone . "\r\n" .
"Message: " . $message ;

/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
*/
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['email'])) {
header( "Location: $feedback_page" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($name) || empty($email)) {
header( "Location: $error_page" );
}

/*
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
elseif ( isInjected($email) || isInjected($name) || isInjected($phone) || isInjected($message) ) {
header( "Location: $error_page" );
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else {

	mail( "$webmaster_email", "$subject", $msg, $headers );
	header( "Location: $thankyou_page" );
}
?>
