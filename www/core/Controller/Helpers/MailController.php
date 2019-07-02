<?php

namespace Core\Controller\Helpers;

class MailController
{
    public static function envoiMail($objet, $mailto, $msg, $cci = true)//:string
    {
        if (!is_array($mailto)) {
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
        if ($cci) {
            $message->setBcc($mailto);
        } else {
            $message->setto($mailto);
        }
        if (is_array($msg) && array_key_exists("html", $msg) && array_key_exists("text", $msg)) {
            $message->setBody($msg["html"], 'text/html');
            // Add alternative parts with addPart()
            $message->addPart($msg["text"], 'text/plain');
        } elseif (is_array($msg) && array_key_exists("html", $msg)) {
            $message->setBody($msg["html"], 'text/html');
            $message->addPart($msg["html"], 'text/plain');
        } elseif (is_array($msg) && array_key_exists("text", $msg)) {
            $message->setBody($msg["text"], 'text/plain');
        } elseif (is_array($msg)) {
            die('erreur une clé n\'est pas bonne');
        } else {
            $message->setBody($msg, 'text/plain');
        }
        
        // Send the message
        return $mailer->send($message);
    }

    public static function setMsg($url, $user)
    {
        return
        "<body style='border: 20px solid #1b1b1e;background-color: #efefef;'>
			<div class='title' style='margin: 0 auto;margin-bottom: 10%;'>
					<h1 style='color: #CFA616;font-size: 2em;letter-spacing: 4px;text-align:center;'><span class='span-title' style='color: #857555;'>Ze</span>Brewery</h1>
			</div>
			<section style='margin: 0 auto;background-color: #fff;width: 60%;padding: 5%;'>
				<h1 style='letter-spacing: 1.5px;'>Veuillez confirmer votre compte</h1>
				<p style='line-height: 25px;'>Merci pour inscription, il ne reste plus qu'une étape pour profiter pleinement de toutes les fonctions de notre site internet, il suffit de vérifier que votre adresse email correspond bien aux informations que vous nous avez envoyé.</p>
				<p style='line-height: 25px;'>Pour ce faire, veuillez cliquer sur <a style='color: #857555;text-decoration: none;' href='$url'>ce lien</a></p>
				<p style='line-height: 25px;'>A très bientôt $user, sur notre site !</p>
			</section>
			<footer style='background: #1B1B1E;text-align:center;height: 100%;color: #fff;padding: 4% 0;width: 100%;margin-top: 27%;'>
				<p class='footer' style='line-height: 25px;margin: 1% 0;'>
					© 2019 <span class='span-title'>ZeBrewery</span> // All rights reserved
				</p>
				<p style='line-height: 25px;margin: 1% 0;'>by <span class='span-title'>SKYMON</span></p>
			</footer>
		</body>";
    }
}
