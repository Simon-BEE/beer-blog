<?php

namespace Core\Controller\Helpers;

class MailController
{
    public static function envoiMail($objet, $mailto, $msg, $cci = true)//:string
{
	if(!is_array($mailto)){
		$mailto = [ $mailto ];
	}
	// Create the Transport
	$transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
	->setUsername("montluconaformac2019@gmail.com")
	->setPassword("aformac03000");
	// Create the Mailer using your created Transport
	$mailer = new \Swift_Mailer($transport);
	// Create a message
	$message = (new \Swift_Message($objet))
		->setFrom(["montluconaformac2019@gmail.com"]);
	if ($cci){
		$message->setBcc($mailto);
	}else{
		$message->setto($mailto);
	}
	if(is_array($msg) && array_key_exists("html", $msg) && array_key_exists("text", $msg))
	{
		$message->setBody($msg["html"], 'text/html');
		// Add alternative parts with addPart()
		$message->addPart($msg["text"], 'text/plain');
	}else if(is_array($msg) && array_key_exists("html", $msg) ){
		$message->setBody($msg["html"], 'text/html');
		$message->addPart($msg["html"], 'text/plain');
	}else if(is_array($msg) && array_key_exists("text", $msg)){
		$message->setBody($msg["text"], 'text/plain');
	}else if(is_array($msg)){
		die('erreur une clé n\'est pas bonne'); 
	}else{
		$message->setBody($msg, 'text/plain');
	}
	
	// Send the message
	return $mailer->send($message);
}
}