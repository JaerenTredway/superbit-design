<?php
/**
 * mailer.php
 *
 * This file handles secure mail transport using the Swiftmailer
 * library with Google reCAPTCHA integration.
 *
 * @author Will Tredway <jwilliamtredway@gmail.com>
 **/
// require all composer dependencies
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
// require mail-config.php
require_once("mail-config.php");


use \SendGrid\Mail;
$sendgrid = new \SendGrid($smtpSecret);
// verify user's reCAPTCHA input
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
$resp = $recaptcha->verify($_POST["g-recaptcha-response"], $_SERVER["REMOTE_ADDR"]);
try {

	//if there's a reCAPTCHA error, throw an exception:
	if (!$resp->isSuccess()) {
		throw(new Exception("reCAPTCHA error!"));
	}

	/*
	 * sanitize the inputs from the form. Assumes jQuery will AJAX submit the form
	 */
	$name = filter_input(INPUT_POST, "contactName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$email = filter_input(INPUT_POST, "contactEmail", FILTER_SANITIZE_EMAIL);
	$subject = filter_input(INPUT_POST, "contactSubject", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$message = filter_input(INPUT_POST, "contactMessage", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	// create SendGrid object
	$emailObject = new \SendGrid\Mail\Mail();
	/**
	 * Attach the sender to the message.
	 * This takes the form of an associative array where $email is the key for the real name.
	 **/
	$emailObject->setFrom($email, $name);
	/**
	 * Attach the recipients to the message.
	 * $MAIL_RECIPIENTS is set in mail-config.php
	 **/
	$recipients = $MAIL_RECIPIENTS;
	$emailObject->addTo($recipients[0], $recipients[1]);
	// attach the subject line to the message
	$emailObject->setSubject("SuperBit Contact");
	/**
	 * Attach the actual content for the email.
	 **/
	$emailObject->addContent("text/plain", $message);
	/**
	 * using the sendgrid object from above call the send method and use the emailObject as an argument.
	 */
	$response = $sendgrid->send($emailObject);
	// report a successful send!
	echo "<div class=\"alert alert-success\" role=\"alert\">Email successfully sent.</div>";
} catch(\Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh darn!</strong> Unable to send email: " . $exception->getMessage() . "</div>";
}