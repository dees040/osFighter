<?php
/**
 * Process.php
 * 
 * The Process class is meant to simplify the task of processing user submitted 
 * forms, redirecting the user to the correct pages if errors are found, or if 
 * form is successful, either way. Also handles the logout procedure.
 *
 * Originally written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated by The Angry Frog : January 11th, 2012
 */
include("include/session.php");

class Process
{
   /* Class constructor */
   function Process(){
      global $session;
      /* User submitted login form */
      if(isset($_POST['sublogin'])){
         $this->procLogin();
      }
      /* User submitted registration form */
      else if(isset($_POST['subjoin'])){
         $this->procRegister();
      }
      /* User submitted forgot password form */
      else if(isset($_POST['subforgot'])){
         $this->procForgotPass();
      }
      /* User submitted edit account form */
      else if(isset($_POST['subedit'])){
         $this->procEditAccount();
      }
      /**
       * The only other reason user should be directed here
       * is if he wants to logout, which means user is
       * logged in currently.
       */
      else if($session->logged_in){
         $this->procLogout();
      }
      /**
       * Should not get here, which means user is viewing this page
       * by mistake and therefore is redirected.
       */
       else{
          header("Location: ".$config['WEB_ROOT'].$config['home_page']);
       }
   }

   /**
    * procLogin - Processes the user submitted login form, if errors
    * are found, the user is redirected to correct the information,
    * if not, the user is effectively logged in to the system.
    */
   function procLogin(){
      global $session, $form;
      /* Login attempt */
      $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));
      
      /* Login successful */
      if($retval){
         header("Location: ".$session->referrer);
      }
      /* Login failed */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procLogout - Simply attempts to log the user out of the system
    * given that there is no logout form to process.
    */
   function procLogout(){
      global $database, $session;
      $config = $database->getConfigs();
	  $retval = $session->logout();
	  header("Location: ".$config['WEB_ROOT'].$config['home_page']);
   }
   
   /**
    * procRegister - Processes the user submitted registration form,
    * if errors are found, the user is redirected to correct the
    * information, if not, the user is effectively registered with
    * the system and an email is (optionally) sent to the newly
    * created user.
    */
   function procRegister(){
      global $database, $session, $form;
      $config = $database->getConfigs();
	  
	  /* Checks if registration is disabled */
	  if($config['ACCOUNT_ACTIVATION'] == 4){
	  	$_SESSION['reguname'] = $_POST['user'];
        $_SESSION['regsuccess'] = 6;
		header("Location: ".$session->referrer);
	  }
	  
      /* Convert username to all lowercase (by option) */
      if($config['ALL_LOWERCASE'] == 1){
         $_POST['user'] = strtolower($_POST['user']);
      }
      /* Hidden form field captcha deisgned to catch out auto-fill spambots */
      if (!empty($_POST['killbill'])) { $retval = 2; } else {
      /* Registration attempt */
      $retval = $session->register($_POST['user'], $_POST['pass'], $_POST['conf_pass'], $_POST['email'], $_POST['conf_email']);
      }
      
      /* Registration Successful */
      if($retval == 0){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 0;
         header("Location: ".$session->referrer);
      }
      /* E-mail Activation */
      else if($retval == 3){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 3;
         header("Location: ".$session->referrer);
      }
      /* Admin Activation */
      else if($retval == 4){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 4;
         header("Location: ".$session->referrer);
      }
      /* No Activation Needed but E-mail going out */
      else if($retval == 5){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 5;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Registration attempt failed */
      else if($retval == 2){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = 2;
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procForgotPass - Validates the given username then if
    * everything is fine, a new password is generated and
    * emailed to the address the user gave on sign up.
    */
   function procForgotPass(){
      global $database, $session, $mailer, $form;
      $config = $database->getConfigs();
      /* Username error checking */
      $subuser = $_POST['user'];
      $subemail = $_POST['email'];
      $field = "user";  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered<br>");
      }
      else{
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < $config['min_user_chars'] || strlen($subuser) > $config['max_user_chars'] ||    
            !preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subuser) ||
            (!$database->usernameTaken($subuser))){
            $form->setError($field, "* Username does not exist<br>");
          } 
          else if ($database->checkUserEmailMatch($subuser, $subemail) == 0){
          	$form->setError($field, "* No Match<br>");
       }
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
      }
      /* Generate new password and email it to user */
      else{
         /* Generate new password */
         $newpass = $session->generateRandStr(8);
         
         /* Get email of user */
         $usrinf = $database->getUserInfo($subuser);
         $email  = $usrinf['email'];
         
         /* Attempt to send the email with new password */
         if($mailer->sendNewPass($subuser,$email,$newpass,$config)){
            /* Email sent, update database */
            $usersalt = $session->generateRandStr(8);
      	    $newpass = sha1($usersalt.$newpass);
            $database->updateUserField($subuser,"password",$newpass);
            $database->updateUserField($subuser,"usersalt",$usersalt);
            $_SESSION['forgotpass'] = true;
         }
         /* Email failure, do not change password */
         else{
            $_SESSION['forgotpass'] = false;
         }
      }
      
      header("Location: ".$session->referrer);
   }
   
   /**
    * procEditAccount - Attempts to edit the user's account
    * information, including the password, which must be verified
    * before a change is made.
    */
   function procEditAccount(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['conf_newpass'], $_POST['email']);

      /* Account edit successful */
      if($retval){
         $_SESSION['useredit'] = true;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
};

/* Initialize process */
$process = new Process;

?>
