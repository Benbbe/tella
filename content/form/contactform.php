<?php
function CreateBody($Name, $Phone, $Email, $Optradio, $Subject, $Message){
  // prepare email body text
  $Body = "<html><body>Contact depuis mosqueedepersan.fr <br>";
  $Body .= "Vous avez reçu une demande par mail : <br>";
  $Body .= "<b>Nom/Prénom</b> : ".$Name."<br>";
  $Body .= "<b>Téléphone</b> : ".$Phone."<br>";
  $Body .= "<b>Email</b> : ".$Email."<br>";
  $Body .= "<b>Type Contact</b> : ".$Optradio."<br>";
  $Body .= "<b>Objet</b> : ".$Subject."<br>";
  $Body .= "<b>Message</b> : ".$Message."<br>";
  $Body .= "</body></html>";

  return $Body;
}

function SendEmail($Optradio, $Body, $ReplyTo){

  $EmailFrom = "mailer@mosqueedepersan.fr";

  switch ($Optradio) {
    case "religieux":
        $EmailTo = "imams@mosqueedepersan.fr";
        break;
    case "ecole":
        $EmailTo = "ecole.arabe@mosqueedepersan.fr";
        break;
    case "autre":
        $EmailTo = "secretaire@mosqueedepersan.fr";
        break;
      }

  $Subject = "Contact depuis le site ACMP - ". Trim(stripslashes($_POST['subject']));

  // Header for reply to
  $Headers = "From: Contact Web<" . $EmailFrom .">\r\n" ;
  $Headers .= "Reply-To: " . $Name . " <" . $ReplyTo . ">" . "\r\n" ;
  $Headers .= "MIME-Version: 1.0\r\n";
  $Headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  // send email
  return mail($EmailTo, $Subject, $Body,  $Headers);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Lf6n-AUAAAAAABiScx7b9J9jHKnQfvkxntcRpmV';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score >= 0.5) {
        //extract the POST content
        $Name = Trim(stripslashes($_POST['name']));
        $Phone = Trim(stripslashes($_POST['phone']));
        $Email = Trim(stripslashes($_POST['email']));
        $Optradio = Trim(stripslashes($_POST['optradio']));
        $Subject = Trim(stripslashes($_POST['subject']));
        $Message = Trim(stripslashes($_POST['message']));

        $Body = CreateBody($Name, $Phone, $Email, $Optradio, $Subject, $Message);
        $success = SendEmail($Optradio, $Body, $Email);

        // redirect to success page
        if ($success){
          echo "Votre demande a été envoyée, nous prenons contact avec vous dans les plus brefs délais";
        }
        else{
          status_header(501);
          error_log("Echec de l'envoi du message ");
        }
    } else {
        // Not verified - show form error
        error_log("Echec de la validation du recaptcha -- Body du mail" . $Body);
    }
}

?>
