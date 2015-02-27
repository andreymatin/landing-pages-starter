<?php

/**
 * Email Form
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	foreach($_POST as $key => $value) {
		if (ini_get('magic_quotes_gpc')) {
			$_POST[$key] = stripslashes($_POST[$key]);
		}

		$_POST[$key] = htmlspecialchars(strip_tags($_POST[$key]));
	}
	
	$name = $_POST["name"];
	$email = $_POST["email"];
	$textarea = $_POST["textarea"];


	// Test input values for errors
	$errors = array();
	if(strlen($name) < 2) {
		if(!$name) {
			$errors[] = "You must enter a name.";
		} else {
			$errors[] = "Name must be at least 2 characters.";
		}
	}
	
	if(!$email) {
		$errors[] = "You must enter an email.";
	} else if(!valid_email($email)) {
		$errors[] = "You must enter a valid email.";
	}
	
	if(strlen($textarea) < 10) {
		if(!$textarea) {
			$errors[] = "You must enter a message.";
		} else {
			$errors[] = "Message must be at least 10 characters.";
		}
	}

	if($errors) {
		// Output errors and die with a failure message
		$errortext = "";
			foreach($errors as $error) {
			$errortext .= "<li>".$error."</li>";
		}
		// die("<span class='failure'>The following errors occured:<ul>". $errortext ."</ul></span>");
	} else {
		try{
			$mail = new PHPMailer;
			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			$mail->isSMTP();                                     // Set mailer to use SMTP
			$mail->Host = '';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = '';                 // SMTP username
			$mail->Password = '';                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                              // TCP port to connect to

			$mail->From = '';
			$mail->FromName = '';
			$mail->addAddress('', '');     // Add a recipient

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = '[contact form]';
			$mail->Body    = 'Name: ' . $name . '<br /> Email: ' . $email . '<br /> Message: <br /> ' . $textarea;
			$mail->AltBody = 'Name: ' . $name . '<br /> Email: ' . $email . '<br /> Message: <br /> ' . $textarea;

			if(!$mail->send()) {
				// echo 'Message could not be sent.';
				// echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				// echo 'Message has been sent';
			}
		} catch (Exception $e) {
			  // echo $e->getMessage();  //Boring error messages from anything else!
		}
	}
}

/**
 * A function that checks to see if an email is valid
 */
function valid_email($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}