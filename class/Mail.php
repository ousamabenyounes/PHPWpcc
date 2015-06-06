<?php

namespace Wpcc;

class Mail 
{

    public static function getTransporter($root_dir)
    {
	$smtpLogin = Config::getVarFromCOnfig('smtpLogin', $root_dir);
	if ('' === $smtpLogin) {
	   $transporter = \Swift_SmtpTransport::newInstance(
                                        Config::getVarFromCOnfig('smtpHost', $root_dir),
                                        Config::getVarFromCOnfig('smtpPort', $root_dir)
	   );
        } else {
       	  $transporter = \Swift_SmtpTransport::newInstance(
                                        Config::getVarFromCOnfig('smtpHost', $root_dir),
                                        Config::getVarFromCOnfig('smtpPort', $root_dir),
                                        'ssl'
	  );
	  $transporter->setUsername(Config::getVarFromCOnfig('smtpLogin', $root_dir))
                     ->setPassword(Config::getVarFromCOnfig('smtpPassword', $root_dir));
	}
	return $transporter;
    }    


    public static function sendMail($mailContent, $root_dir = '')
    {
	$mailsToArray = Config::getVarFromCOnfig('mailsTo', $root_dir);
	$transporter = self::getTransporter($root_dir);
        $mailer = \Swift_Mailer::newInstance($transporter);
	$message = \Swift_Message::newInstance()
	   // Give the message a subject
  	   ->setSubject(
		Config::getVarFromCOnfig('namespace', $root_dir) . ' ' .  
                Config::getVarFromCOnfig('projectName', $root_dir) . ' ' .
		'Tests Reporting '
	   )

  	   // Set the From address with an associative array
  	   ->setFrom(Config::getVarFromCOnfig('mailFrom', $root_dir))

  	   // Set the To addresses with an associative array
  	   ->setTo($mailsToArray)

  	   // Give it a body
  	   ->setBody($mailContent)
	   ->setContentType("text/html");
	   
	   $mailer->send($message);
    }

}

?>

