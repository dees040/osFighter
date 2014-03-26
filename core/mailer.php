<?php
/**
 * Mailer.php
 *
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if your server is not setup to send mail.
 *
 * If you are running Windows and want a mail server, check
 * out this website to see a list of freeware programs:
 * <http://www.snapfiles.com/freeware/server/fwmailserver.html>
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Updated by: The Angry Frog
 * Last Updated: April 4, 2012
 */

class Mailer
{
   
   /*
    * sendActivation - Sends an activation e-mail to the newly
    * registered user with a link to activate the account.
    */
	
  function sendActivation($user, $email, $pass, $token, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']." "
      ."with the following username:\n\n"
      ."Username: ".$user."\n\n"
      ."Please visit the following link in order to activate your account: "
      .$config['WEB_ROOT']."register.php?mode=activate&user=".urlencode($user)."&activatecode=".$token." \n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
      
   /**
    * adminActivation - Sends an activation e-mail to the newly
    * registered user explaining that admin will activate the account.
    */
   
  function adminActivation($user, $email, $pass, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']." "
      ."with the following username:\n\n"
      ."Username: ".$user."\n\n"
      ."Your account is currently inactive and will need to be approved by an administrator. "
      ."Another e-mail will be sent when this has occured.\n\n"
      ."Thank you for registering.\n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
      
   /**
    * activateByAdmin - Sends an activation e-mail to the admin
    * to allow him or her to activate the account. E-mail will appear
    * to come FROM the user using the e-mail address he or she registered
    * with.
    */
   
  function activateByAdmin($user, $email, $pass, $token, $config){
  	$from = "From: ".$user." <".$email.">";
  	$subject = $config['SITE_NAME']." - User Account Activation!";
  	$body = "Hello Admin,\n\n"
      .$user." has just registered at ".$config['SITE_NAME']
      ." with the following details:\n\n"
      ."Username: ".$user."\n"
      ."E-mail: ".$email."\n\n"
      ."You should check this account and if neccessary, activate it. \n\n"
      ."Use this link to activate the account.\n\n"
      .$config['WEB_ROOT']."register.php?mode=activate&user=".urlencode($user)."&activatecode=".$token." \n\n"
      ."Thanks.\n\n"
      .$config['SITE_NAME'];
	
    $adminemail = $config['EMAIL_FROM_ADDR'];
    return mail($adminemail,$subject,$body,$from);
   }
    
	/**
    * adminActivated - Sends an e-mail to the user once
    * admin has activated the account.
    */
   
  function adminActivated($user, $email, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']." "
      ."with the following username:\n\n"
      ."Username: ".$user."\n\n"
      ."Your account has now been activated by an administrator. "
      ."Please click here to login - "
      .$config['WEB_ROOT']."\n\nThank you for registering.\n\n"
      .$config['SITE_NAME'];
	
    return mail($email,$subject,$body,$from);
   }
    
   /**
    * sendWelcome - Sends an activation e-mail to the newly
    * registered user with a link to activate the account.
    */
   
  function sendWelcome($user, $email, $pass, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']
      ."with the following information:\n\n"
      ."Username: ".$user."\n\n"
      ."Please keep this e-mail for your records. Your password is stored safely in "
      ."our database. In the event that it is forgotten, please visit the site and click "
      ."the Forgot Password link. "
      ."Thank you for registering.\n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
   
   /**
    * sendNewPass - Sends the newly generated password
    * to the user's email address that was specified at
    * sign-up.
    */
   
   function sendNewPass($user, $email, $pass, $config){
      $from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
      $subject = $config['SITE_NAME']." - Your New Password!";
      $body = $user.",\n\n"
        ."We've generated a new password for you at your "
        ."request, you can use this new password with your "
        ."username to log in to ".$config['SITE_NAME']."\n\n"
        ."Username: ".$user."\n"
        ."New Password: ".$pass."\n\n"
        ."It is recommended that you change your password "
        ."to something that is easier to remember, which "
        ."can be done by going to the My Account page "
        ."after signing in.\n\n"
        .$config['SITE_NAME'];
             
      return mail($email,$subject,$body,$from);
   }
};

/* Initialize mailer object */
$mailer = new Mailer;
 
?>