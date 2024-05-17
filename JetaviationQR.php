></div>";<?php
  ini_set('display_errors','1');
  ini_set('display_startup_errors','1');
  error_reporting(E_ALL);
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST");
  header("Access-Control-Allow-Headers: X-Requested-With");
  try
  {
    $db = new PDO('sqlite:users.jetaviation.db');
    $db->exec("CREATE TABLE IF NOT EXISTS users (firstname TEXT NOT NULL,lastname TEXT NOT NULL,email TEXT NOT NULL,company TEXT NOT NULL,regdate TIMESTAMP NOT NULL,jobTitle TEXT NOT NULL,industry TEXT NOT NULL,receiveInfo TEXT NOT NULL);");
    $db->exec("CREATE VIEW IF NOT EXISTS reglog AS SELECT firstname, lastname, email, company, jobTitle, industry, receiveInfo, strftime('%d/%m/%Y %H:%M:%S', datetime(regdate/1000, 'unixepoch', 'localtime')) regdatetime FROM users;");
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $company = $_POST["company"];
    $jobTitle = $_POST["jobTitle"];
    $industry = $_POST["industry"];
    $receiveInfo = $_POST["receiveInfo"];
    $regdate = intval($_POST["regdate"]);
    $dbq = $db->prepare("INSERT INTO `users` (firstname,lastname,email,company,regdate,jobTitle,industry,receiveInfo) VALUES (:firstname,:lastname,:email,:company,:regdate,:jobTitle,:industry,:receiveInfo);");
    $dbq->execute(['firstname'=>$firstname,'lastname'=>$lastname,'email'=>$email,'company'=>$company, 'regdate'=>$regdate, 'jobTitle'=>$jobTitle,'industry'=>$industry,'receiveInfo'=>$receieveInfo]);
    $db = NULL;
    echo "Database updated";
  }
  catch(PDOException $e)
  {
    echo 'SQLite exception: '.$e->getMessage();
  }
  require "Exception.php";
  require "PHPMailer.php";
  require "SMTP.php";
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  $mail = new PHPMailer(true);
  try
  {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    // $mail->Username = "jetaviationebace24@gmail.com";
    // $mail->Password = "jqvhjkbawirulsrd";
    $mail->Username = "noreply@elementz.live";
    $mail->Password = "birzjbellcxqqwzy";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->addAddress($email);
    $mail->setFrom("jetaviationebace24@gmail.com","Jet Aviation");
    $mail->addReplyTo("jetaviationebace24@gmail.com", "Jet Aviation");
    $mail->isHTML(true);
    $mail->addStringEmbeddedImage(base64_decode($_POST['qrpng']),"qrpng","qr.png","base64","image/png");
    $mail->addStringEmbeddedImage(base64_decode("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"),"ezlogo","ezlogo.png","base64","image/png");
    $mail->Subject = "JET AVIATION @ EBACE24";
    $mail->Body = "<div dir='ltr' lang='en'>Hello {$_POST['firstname']} {$_POST['lastname']},<br/><br/>Thank you for registering for the Jet Aviation stand at EBACE24.<br/><br/>Attached is your personalized QR code.<br/><br/>Best regards,<br/><br/>Jet Aviation<br/><br/></div><div><img src='cid:qrpng' alt='' /><br/><img src='cid:ralogo' alt='' /><br/></div>";
    $mail->send();
  }
    catch (Exception $e)
  {
    echo 'Mailer Error: {$mail->ErrorInfo}';
  }
  finally
  {
    http_response_code(200);
  }
  echo 'Done...';
?>
