<?PHP

if(!defined("HYN")) { exit; }

require_once "lib/lib/swift_required.php";

function sendEmail( Swift_Message $message ) {
	global $MultiSite;
	if( 	MultiSite::setting( "smtp-host" , "email-out" ) 
			&& MultiSite::setting( "smtp-username" , "email-out" ) 
			&& MultiSite::setting( "smtp-password" , "email-out" ) 
			&& MultiSite::setting( "smtp-port" , "email-out" ) ) {
		$transport = Swift_SmtpTransport::newInstance(
				MultiSite::setting( "smtp-host" , "email-out" ) -> get("value"), 
				MultiSite::setting( "smtp-port" , "email-out" ) -> get("value"))
		  ->setUsername(MultiSite::setting( "smtp-username" , "email-out" ) -> get("value"))
		  ->setPassword(MultiSite::setting( "smtp-password" , "email-out" ) -> get("value"))
		;
	}
	
	
	if( !isset($transport)) {
		throw new Exception( "No settings found for e-mail out." );
	}
	$mailer = Swift_Mailer::newInstance($transport);
	return $mailer->send($message);
}