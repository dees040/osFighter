<?php
/**
 * Session.php
 * 
 * The Session class is meant to simplify the task of keeping
 * track of logged in users and also guests.
 * 
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Updated by: The Angry Frog
 * Last Updated: December 26, 2011
 * 
 */
include("database.php");
include("mailer.php");
include("form.php");

class Session
{
   public $username;     //Username given on sign-up
   public $userid;       //Random value generated on current login
   public $userlevel;    //The level to which the user pertains
   public $time;         //Time user was last active (page loaded)
   public $logged_in;    //True if user is logged in, false otherwise
   public $userinfo = array();  //The array holding all user info
   public $url;          //The page url current being viewed
   public $referrer;     //Last recorded site page viewed
   /**
    * Note: referrer should really only be considered the actual
    * page referrer in process.php, any other time it may be
    * inaccurate.
    */

   /* Class constructor */
   function Session(){
      $this->time = time();
      $this->startSession();
   }

   /**
    * startSession - Performs all the actions necessary to 
    * initialize this session object. Tries to determine if the
    * the user has logged in already, and sets the variables 
    * accordingly. Also takes advantage of this page load to
    * update the active visitors tables.
    */
   function startSession(){
      global $database;  //The database connection
      session_start();   //Tell PHP to start the session

      /* Determine if user is logged in */
      $this->logged_in = $this->checkLogin();

      /**
       * Set guest value to users not logged in, and update
       * active guests table accordingly.
       */
      if(!$this->logged_in){
         $this->username = $_SESSION['username'] = GUEST_NAME;
         $this->userlevel = GUEST_LEVEL;
         $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
      }
      /* Update users last active timestamp */
      else{
         $database->addActiveUser($this->username, $this->time);
      }
      
      /* Remove inactive visitors from database */
      $database->removeInactiveUsers();
      $database->removeInactiveGuests();
      
      /* Set referrer page */
      if(isset($_SESSION['url'])){
         $this->referrer = $_SESSION['url'];
      }else{
         $this->referrer = "/";
      }

      /* Set current url */
      $this->url = $_SESSION['url'] = $_SERVER['PHP_SELF'];
   }

   /**
    * checkLogin - Checks if the user has already previously
    * logged in, and a session with the user has already been
    * established. Also checks to see if user has been remembered.
    * If so, the database is queried to make sure of the user's 
    * authenticity. Returns true if the user has logged in.
    */
   function checkLogin(){
      global $database;  //The database connection
      /* Check if user has been remembered */
      if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
         $this->username = $_SESSION['username'] = $_COOKIE['cookname'];
         $this->userid   = $_SESSION['userid']   = $_COOKIE['cookid'];
      }

      /* Username and userid have been set and not guest */
      if(isset($_SESSION['username']) && isset($_SESSION['userid']) &&
         $_SESSION['username'] != GUEST_NAME){
         /* Confirm that username and userid are valid */
         if($database->confirmUserID($_SESSION['username'], $_SESSION['userid']) != 0){
            /* Variables are incorrect, user not logged in */
            unset($_SESSION['username']);
            unset($_SESSION['userid']);
            return false;
         }

         /* User is logged in, set class variables */
         $this->userinfo  = $database->getUserInfo($_SESSION['username']);
         $this->username  = $this->userinfo['username'];
         $this->userid    = $this->userinfo['userid'];
         $this->userlevel = $this->userinfo['userlevel'];
         return true;
      }
      /* User not logged in */
      else{
         return false;
      }
   }

   /**
    * login - The user has submitted his username and password
    * through the login form, this function checks the authenticity
    * of that information in the database and creates the session.
    * Effectively logging in the user if all goes well.
    */
   function login($subuser, $subpass, $subremember){
      global $database, $form;  //The database and form object

      /* Username error checking */
      $field = "user";  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered");
      }
      else{
         /* Check if username is not alphanumeric */
      	 if(!preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subuser)){
            $form->setError($field, "* Username not alphanumeric");
         }
      }

      /* Password error checking */
      $field = "pass";  //Use field name for password
      if(!$subpass){
         $form->setError($field, "* Password not entered");
      }
      
      /* Return if form errors exist */
      if($form->num_errors > 0){
         return false;
      }

      /* Checks that username is in database and password is correct */
      $subuser = stripslashes($subuser);
      $result = $database->confirmUserPass($subuser, $subpass);

      /* Check error codes */
      
      if($result == 1 || $result == 2){
		$field = "user";
		$form->setError($field, "* Login is invalid. Please try again");
      }
      else if($result == 3){
  	  	$field = "user";
  	  	$form->setError($field, "* Your account has not been activated yet");
	  }
      else if($result == 4){
  	  	$field = "user";
  	  	$form->setError($field, "* Your account has not been activated by admin yet");
	  }
      
      /* Return if form errors exist */
      if($form->num_errors > 0){
         return false;
      }

      /* Username and password correct, register session variables */
      $this->userinfo  = $database->getUserInfo($subuser);
      $this->username  = $_SESSION['username'] = $this->userinfo['username'];
      $this->userid    = $_SESSION['userid']   = $this->generateRandID();
      $this->userlevel = $this->userinfo['userlevel'];
      
      /* Insert userid into database and update active users table */
      $database->updateUserField($this->username, "userid", $this->userid);
      $database->addActiveUser($this->username, $this->time);
      $database->removeActiveGuest($_SERVER['REMOTE_ADDR']);

      /**
       * This is the cool part: the user has requested that we remember that
       * he's logged in, so we set two cookies. One to hold his username,
       * and one to hold his random value userid. It expires by the time
       * specified in the admin configuration panel. Now, next time he comes to 
       * our site, we will log him in automatically, but only if he didn't log 
       * out before he left.
       */
      if($subremember){
      	
		$config = $database->getConfigs();
        $cookie_expire = $config['COOKIE_EXPIRE'];
        $cookie_path = $config['COOKIE_PATH'];
        
        setcookie("cookname", $this->username, time()+60*60*24*$cookie_expire, $cookie_path);
        setcookie("cookid",   $this->userid,   time()+60*60*24*$cookie_expire, $cookie_path);
      }

      /* Login completed successfully */
      return true;
   }

   /**
    * logout - Gets called when the user wants to be logged out of the
    * website. It deletes any cookies that were stored on the users
    * computer as a result of him wanting to be remembered, and also
    * unsets session variables and demotes his user level to guest.
    */
   function logout(){
      global $database;  //The database connection

      /**
       * Delete cookies - the time must be in the past,
       * so just negate what you added when creating the
       * cookie.
       */
      if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookid'])){
      	
		$config = $database->getConfigs();
      	$cookie_expire = $config['COOKIE_EXPIRE'];
      	$cookie_path = $config['COOKIE_PATH'];
      
         setcookie("cookname", "", time()-60*60*24*$cookie_expire, $cookie_path);
         setcookie("cookid",   "", time()-60*60*24*$cookie_expire, $cookie_path);
      }

      /* Unset PHP session variables */
      unset($_SESSION['username']);
      unset($_SESSION['userid']);

      /* Reflect fact that user has logged out */
      $this->logged_in = false;
      
      /**
       * Remove from active users table and add to
       * active guests tables.
       */
      $database->removeActiveUser($this->username);
      $database->addActiveGuest($_SERVER['REMOTE_ADDR'], $this->time);
      
      /* Set user level to guest */
      $this->username  = GUEST_NAME;
      $this->userlevel = GUEST_LEVEL;
      
      /* Destroy session */
      session_destroy();
   }

   /**
    * register - Gets called when the user has just submitted the
    * registration form. Determines if there were any errors with
    * the entry fields, if so, it records the errors and returns
    * 1. If no errors were found, it registers the new user and
    * returns 0. Returns 2 if registration failed.
    */
   function register($subuser, $subpass, $subconf_pass, $subemail, $subconf_email){
      global $database, $form, $mailer;  //The database, form and mailer object
      $token = $this->generateRandStr(16);	
      $config = $database->getConfigs();
      /* Username error checking */
      $field = "user";  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered");
      }
      else{
         /* Spruce up username, check length */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < $config['min_user_chars']){
            $form->setError($field, "* Username below ".$config['min_user_chars']."characters");
         }
         else if(strlen($subuser) > $config['max_user_chars']){
            $form->setError($field, "* Username above ".$config['max_user_chars']."characters");
         }
         /* Check if username is not alphanumeric */
         else if(!preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subuser)){      	
            $form->setError($field, "* Username not alphanumeric");
         }
         /* Check if username is reserved */
         else if(strcasecmp($subuser, GUEST_NAME) == 0){
            $form->setError($field, "* Username reserved word");
         }
         /* Check if username is already in use */
         else if($database->usernameTaken($subuser)){
            $form->setError($field, "* Username already in use");
         }
         /* Check if username is banned */
         else if($database->usernameBanned($subuser)){
            $form->setError($field, "* Username banned");
         }
      }

      /* Password error checking */
      $field = "pass";  //Use field name for password
      if(!$subpass){
         $form->setError($field, "* Password not entered");
      }
      else{
         /* Spruce up password and check length*/
         $subpass = stripslashes($subpass);
         if(strlen($subpass) < $config['min_pass_chars']){
            $form->setError($field, "* Password too short");
         }
         /* Check if password is too long */
         else if(strlen($subpass) > $config['max_pass_chars'] ){
         	$form->setError($field, "* Password too long");
         }
         /* Check if password is not alphanumeric */
         else if(!preg_match("/^([0-9a-z])+$/i", ($subpass = trim($subpass)))){
            $form->setError($field, "* Password not alphanumeric");
         }
          /* Check if passwords match */
         else if($subpass != $subconf_pass){
            $form->setError($field, "* Passwords do not match");
         }
      }
      
      /* Email error checking */
      $field = "email";  //Use field name for email
      if(!$subemail || strlen($subemail = trim($subemail)) == 0){
         $form->setError($field, "* Email not entered");
      }
      else{
         /* Check if valid email address using PHPs filter_var */
         if(!filter_var($subemail, FILTER_VALIDATE_EMAIL)){
            $form->setError($field, "* Email invalid");
         }
       /* Check if emails match, not case-sensitive */
         else if (strcasecmp($subemail, $subconf_email)){
            $form->setError($field, "* Email addresses do not match");
         }
         $subemail = stripslashes($subemail);   
      }

      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         return 1;  //Errors with form
      }
      /* No errors, add the new account to the database */
      else{
      $usersalt = $this->generateRandStr(8);	
      if($database->addNewUser($subuser, $subpass, $subemail, $token, $usersalt)){
  	  	/* Check Account activation setting and process accordingly. */
      	
  	  	/* E-mail Activation */
      	if($config['ACCOUNT_ACTIVATION'] == 2){
  	  	$config = $database->getConfigs();
      	$mailer->sendActivation($subuser,$subemail,$subpass,$token,$config);
      	$successcode = 3;
  		}
  		/* Admin Activation */
        else if($config['ACCOUNT_ACTIVATION'] == 3){
  	  	$config = $database->getConfigs();
      	$mailer->adminActivation($subuser,$subemail,$subpass,$config);
      	$mailer->activateByAdmin($subuser,$subemail,$subpass,$token,$config);
      	$successcode = 4;
  		}
  		/* No Activation Needed but E-mail going out */
  		else if($config['EMAIL_WELCOME'] && $config['ACCOUNT_ACTIVATION'] == 1 ){
      	$config = $database->getConfigs();
  		$mailer->sendWelcome($subuser,$subemail,$subpass,$config);
  		$successcode = 5;	
  		}
  		else {
  		/* No Activation Needed and NO E-mail going out */
  		$successcode = 0;
  		}
  	  return $successcode;  //New user added succesfully
	  }else{
  	  return 2;  //Registration attempt failed
	  }
     }
   }
   
   /**
    * editConfigs - edits the site configurations in the database
    */
   function editConfigs($subsitename, $subsitedesc, $subemailfromname, $subadminemail, $subwebroot, $subhome_page, $subactivation, $submin_user_chars, $submax_user_chars, $submin_pass_chars, $submax_pass_chars, $subsend_welcome, $subenable_login_question, $sub_captcha, $sub_all_lowercase, $subuser_timeout, $subguest_timeout, $subcookie_expiry, $subcookie_path){
      global $database, $form;  //The database and form object
      
      /* New Sitename entered */
        if($subsitename){
      	   /* Sitename error checking */
      	   $field = "sitename";
           if(!$subsitename){
                $form->setError($field, "* Sitename not entered");
           }
           else if(strlen($subsitename) > 40) {
      	    $form->setError($field, "* Sitename above 40 characters");
           }
           else if(!preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subsitename)){      	
            $form->setError($field, "* Sitename not alphanumeric");
        }
      }
      
         /* New Site Description entered */
        if($subsitename){
      	   /* Site description error checking */
      	   $field = "sitedesc";
           if(!$subsitedesc){
                $form->setError($field, "* Site description not entered");
           }
           else if(strlen($subsitedesc) > 60) {
      	    $form->setError($field, "* Site description above 60 characters");
           }
           else if(!preg_match("/^[a-z0-9]([0-9a-z_.-\s])+$/i", $subsitedesc)){      	
            $form->setError($field, "* Site description not alphanumeric");
        }
      }
        
      /* New E-mail From Name */
        if($subemailfromname){
        	/* Site Email From Name error checking */
        	$field = "emailfromname";
        	if(!$subemailfromname){
        		$form->setError($field, "* Email From Name not entered");
        	}
        	else if(strlen($subemailfromname) > 60) {
      	    $form->setError($field, "* From Name above 60 characters");
            }
            else if(!preg_match("/^[a-z0-9]([0-9a-z_.-\s])+$/i", $subemailfromname)){      	
            $form->setError($field, "* From Name not alphanumeric");
         }
        }
        
      /* New Admin Email Address */
        if($subadminemail){
        	/* Site Admin Email error checking */
        	$field = "adminemail";
        	if(!$subadminemail){
        		$form->setError($field, "* Admin Email not entered");
        	} else
         /* Check if valid email address using PHPs filter_var */
         if(!filter_var($subadminemail, FILTER_VALIDATE_EMAIL)){
            $form->setError($field, "* Email invalid");
         }
        } 
        
      /* New Minimum Username Characters */
        if($submin_user_chars){
        	/* Minimum Username Characters error checking */
        	$field = "min_user_chars";
        	if(!$submin_user_chars){
        		$form->setError($field, "* No minimum username length entered");
        	}
            else if(!preg_match("/^([0-9])+$/i", ($submin_user_chars = trim($submin_user_chars)))){
            	$form->setError($field, "* Minimum username field not numerical");
            }
        	else if ($submin_user_chars < 3){
        		$form->setError($field, "* Minimum username is below recommended level of 3");
        	}
            else if ($submin_user_chars > 20){
        		$form->setError($field, "* Minimum username is above recommended level of 20");
        	}
        }
        
      /* New Maximum Username Characters */
        if($submax_user_chars){
        	/* Maximum Username Characters error checking */
        	$field = "max_user_chars";
        	if(!$submax_user_chars){
        		$form->setError($field, "* No maximum username length entered");
        	}
            else if(!preg_match("/^([0-9])+$/i", ($submax_user_chars = trim($submax_user_chars)))){
            	$form->setError($field, "* Maximum username field not numerical");
            }
        	else if ($submax_user_chars < 6){
        		$form->setError($field, "* Maximum username is below recommended level of 6");
        	}
            else if ($submax_user_chars > 40){
        		$form->setError($field, "* Maximum username is above recommended level of 40");
        	}
        }   
        
         /* New Minimum Password Characters */
        if($submin_pass_chars){
        	/* Minimum Username Characters error checking */
        	$field = "min_pass_chars";
        	if(!$submin_pass_chars){
        		$form->setError($field, "* No minimum username length entered");
        	}
            else if(!preg_match("/^([0-9])+$/i", ($submin_pass_chars = trim($submin_pass_chars)))){
            	$form->setError($field, "* Minimum username field not numerical");
            }
        	else if ($submin_pass_chars < 4){
        		$form->setError($field, "* Minimum password is below recommended level of 4");
        	}
            else if ($submin_pass_chars > 10){
        		$form->setError($field, "* Minimum password is above recommended level of 10");
        	}
        }

      /* New Maximum Password Characters */
        if($submax_pass_chars){
        	/* Maximum Username Characters error checking */
        	$field = "max_pass_chars";
        	if(!$submax_pass_chars){
        		$form->setError($field, "* No maximum password length entered");
        	}
            else if(!preg_match("/^([0-9])+$/i", ($submax_pass_chars = trim($submax_pass_chars)))){
            	$form->setError($field, "* Maximum password field not numerical");
            }
        	else if ($submax_pass_chars < 10){
        		$form->setError($field, "* Maximum password is below recommended level of 10");
        	}
            else if ($submax_pass_chars > 110){
        		$form->setError($field, "* Maximum password is above recommended level of 110");
        	}
        }

        /* Cookie expiry */
        if($subcookie_expiry){
        	/* Check for number */
        	$field = "cookie_expiry";
        	if(!$subcookie_expiry){
        		$form->setError($field, "* No cookie expiry number entered");
        	}
            else if(!filter_var($subcookie_expiry, FILTER_VALIDATE_INT, array("options" => array("max_range"=>366)))){
            	$form->setError($field, "* Please enter a number between 0 and 365");
            }
        } 
        
     /* Errors exist, have user correct them */
       if($form->num_errors > 0){
         return false;  //Errors with form
      }
      
     /* Update site name since there were no errors */
      if($subsitename){
         $database->updateConfigs($subsitename,"SITE_NAME");
      }
      
      if($subsitedesc){
         $database->updateConfigs($subsitedesc,"SITE_DESC");
      }
      
      if($subemailfromname){
         $database->updateConfigs($subemailfromname,"EMAIL_FROM_NAME");
      }
      
      if($subadminemail){
         $database->updateConfigs($subadminemail,"EMAIL_FROM_ADDR");
      }
      
      if($subwebroot){
         $database->updateConfigs($subwebroot,"WEB_ROOT");
      }
      
   	  if($subhome_page){
         $database->updateConfigs($subhome_page,"home_page");
      }
      
      if($submin_user_chars){
         $database->updateConfigs($submin_user_chars,"min_user_chars");
      }
      
      if($submax_user_chars){
         $database->updateConfigs($submax_user_chars,"max_user_chars");
      }
      
      if($submin_pass_chars){
         $database->updateConfigs($submin_pass_chars,"min_pass_chars");
      }
      
      if($submax_pass_chars){
         $database->updateConfigs($submax_pass_chars,"max_pass_chars");
      }
      
      // Check for the existance of 0 otherwise IF will return false and not update.
      if($subsend_welcome == 0 || 1){
         $database->updateConfigs($subsend_welcome,"EMAIL_WELCOME");
      }
      
      if($subenable_login_question  == 0 || 1){
         $database->updateConfigs($subenable_login_question,"ENABLE_QUESTION");
      }
      
      if($sub_captcha  == 0 || 1){
         $database->updateConfigs($sub_captcha,"ENABLE_CAPTCHA");
      }
      
      if(filter_var($subactivation, FILTER_VALIDATE_INT)){
         $database->updateConfigs($subactivation,"ACCOUNT_ACTIVATION");
      }
	  
	  if($sub_all_lowercase == 0 || 1){
      	$database->updateConfigs($sub_all_lowercase,"ALL_LOWERCASE");
      }
	  
	  if($subuser_timeout){
         $database->updateConfigs($subuser_timeout,"USER_TIMEOUT");
      }
      
	  if($subguest_timeout){
         $database->updateConfigs($subguest_timeout,"GUEST_TIMEOUT");
      }
      
   	  if($subcookie_expiry){
         $database->updateConfigs($subcookie_expiry,"COOKIE_EXPIRE");
      }
      
      if($subcookie_path){
         $database->updateConfigs($subcookie_path,"COOKIE_PATH");
      }
      
      
      /* Success! */
      return true;
      
   }
   
   /**
    * adminEditAccount - function for admin to edit the user's account
    * details.
    */
   function adminEditAccount($subusername, $subnewpass, $subconfnewpass, $subemail, $subuserlevel, $subusertoedit){
      global $database, $form;  //The database and form object
      
      /* New password entered */
      if($subnewpass){
         /* New Password error checking */
         $field = "newpass";  //Use field name for new password
         /* Spruce up password and check length*/
         $subnewpass = stripslashes($subnewpass);
         if(strlen($subnewpass) < $config['min_pass_chars']){
            $form->setError($field, "* New Password too short");
         }
         /* Check if password is not alphanumeric */
         else if(!preg_match("/^([0-9a-z])+$/i", ($subnewpass = trim($subnewpass)))){
            $form->setError($field, "* New Password not alphanumeric");
         }
         /* Check if passwords match */
         else if($subnewpass != $subconfnewpass){
            $form->setError($field, "* Passwords do not match");
         }
      }
      
      /* New password entered */
      if($subuserlevel){
      	/* User level error checking */
      	$field = "userlevel";  //Use field name for userlevel
      	if(!preg_match("/^([0-9])+$/i", ($subuserlevel = trim($subuserlevel)))){
           $form->setError($field, "* Userlevel not numerical");
        }
      }
      
      /* New username entered */
      if($subusername){
      	/* Username error checking */
      	$field = "username";  //Use field name for userlevel
        if(!preg_match("/^[a-z0-9]([0-9a-z_-\s])+$/i", $subusername)){      	
            $form->setError($field, "* Username not alphanumeric");
         }
         /* Check if username is reserved */
         else if(strcasecmp($subusername, GUEST_NAME) == 0){
            $form->setError($field, "* Username reserved word");
         }
         /* Check if username is already in use */
         else if($subusertoedit !== $subusername && $database->usernameTaken($subusername)){
            $form->setError($field, "* Username already in use");
         }
         /* Check if username is banned */
         else if($database->usernameBanned($subusername)){
            $form->setError($field, "* Username banned");
         }
      }
      
      /* Email error checking */
      $field = "email";  //Use field name for email
      if($subemail && strlen($subemail = trim($subemail)) > 0){
         /* Check if valid email address */
         if(!filter_var($subemail, FILTER_VALIDATE_EMAIL)){
            $form->setError($field, "* Email invalid");
         }
         $subemail = stripslashes($subemail);
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         return false;  //Errors with form
      }
      
      /* Update userlevel since there were no errors */
      if($subuserlevel){
         $database->updateUserField($subusertoedit,"userlevel",$subuserlevel);
      }
      
      /* Update password since there were no errors */
      if($subnewpass){
         $usersalt = $this->generateRandStr(8);
         $database->updateUserField($subusertoedit,"usersalt", $usersalt);
         $database->updateUserField($subusertoedit,"password", sha1($usersalt.$subnewpass));
      }
      
      /* Change Email */
      if($subemail){
         $database->updateUserField($subusertoedit,"email",$subemail);
      }
      
   /* Update username - this MUST GO LAST otherwise the username 
    * will change and subsequent changes like e-mail will not be changed.
    */
      if($subusername){
         $database->updateUserField($subusertoedit,"username",$subusername);
      }
      
      /* Success! */
      return true;
   }
   
   /**
    * editAccount - Attempts to edit the user's account information
    * including the password, which it first makes sure is correct
    * if entered, if so and the new password is in the right
    * format, the change is made. All other fields are changed
    * automatically.
    */
   function editAccount($subcurpass, $subnewpass, $subconfnewpass, $subemail){
      global $database, $form;  //The database and form object
      /* New password entered */
      if($subnewpass){
         /* Current Password error checking */
         $field = "curpass";  //Use field name for current password
         if(!$subcurpass){
            $form->setError($field, "* Current Password not entered");
         }
         else{
            /* Check if password too short or is not alphanumeric */
            $subcurpass = stripslashes($subcurpass);
            if(strlen($subcurpass) < $config['min_pass_chars'] ||
               !preg_match("/^([0-9a-z])+$/i", ($subcurpass = trim($subcurpass)))){
               $form->setError($field, "* Current Password incorrect");
            }
            /* Password entered is incorrect */
            if($database->confirmUserPass($this->username,$subcurpass) != 0){
               $form->setError($field, "* Current Password incorrect");
            }
         }
         
         /* New Password error checking */
         $field = "newpass";  //Use field name for new password
         /* Spruce up password and check length*/
         $subnewpass = stripslashes($subnewpass);
         if(strlen($subnewpass) < 4){
            $form->setError($field, "* New Password too short");
         }
         /* Check if password is not alphanumeric */
         else if(!preg_match("/^([0-9a-z])+$/i", ($subnewpass = trim($subnewpass)))){
            $form->setError($field, "* New Password not alphanumeric");
         }
         /* Check if passwords match */
         else if($subnewpass != $subconfnewpass){
            $form->setError($field, "* Passwords do not match");
         }
      }
      /* Change password attempted */
      else if($subcurpass){
         /* New Password error reporting */
         $field = "newpass";  //Use field name for new password
         $form->setError($field, "* New Password not entered");
      }
      
      /* Email error checking */
      $field = "email";  //Use field name for email
      if($subemail && strlen($subemail = trim($subemail)) > 0){
         /* Check if valid email address */
         if(!filter_var($subemail, FILTER_VALIDATE_EMAIL)){
            $form->setError($field, "* Email invalid");
         }
         $subemail = stripslashes($subemail);
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         return false;  //Errors with form
      }
      
      /* Update password since there were no errors */
      if($subcurpass && $subnewpass){
      	 $usersalt = $this->generateRandStr(8);
      	 $subnewpass = sha1($usersalt.$subnewpass);
         $database->updateUserField($this->username,"password",$subnewpass);
         $database->updateUserField($this->username,"usersalt",$usersalt);
      }
      
      /* Change Email */
      if($subemail){
         $database->updateUserField($this->username,"email",$subemail);
      }
      
      /* Success! */
      return true;
   }
   
   /**
    * isAdmin - Returns true if currently logged in user is
    * an administrator, false otherwise.
    */
   function isAdmin(){
      return ($this->userlevel == ADMIN_LEVEL ||
              $this->username  == ADMIN_NAME);
   }
   
   /**
    * isUserlevel - Returns true if currently logged in user is
    * at a certain userlevel, false otherwise.
    */
   function isUserlevel($level){
      return ($this->userlevel == $level);
   }
   
   /**
    * overUserlevel - Returns true if currently logged in user is
    * over a certain userlevel, false otherwise.
    */
   function overUserlevel($level){
      if ($this->userlevel > $level) { return true; }
      else {
      	return false;
      }
   }
   
   /**
    * generateRandID - Generates a string made up of randomized
    * letters (lower and upper case) and digits and returns
    * the md5 hash of it to be used as a userid.
    */
   function generateRandID(){
      return md5($this->generateRandStr(16));
   }
   
   /**
    * generateRandStr - Generates a string made up of randomized
    * letters (lower and upper case) and digits, the length
    * is a specified parameter.
    */
   function generateRandStr($length){
      $randstr = "";
      for($i=0; $i<$length; $i++){
         $randnum = mt_rand(0,61);
         if($randnum < 10){
            $randstr .= chr($randnum+48);
         }else if($randnum < 36){
            $randstr .= chr($randnum+55);
         }else{
            $randstr .= chr($randnum+61);
         }
      }
      return $randstr;
   }
};

/**
 * Initialize session object - This must be initialized before
 * the form object because the form uses session variables,
 * which cannot be accessed unless the session has started.
 */
$session = new Session;

/* Initialize form object */
$form = new Form;
?>