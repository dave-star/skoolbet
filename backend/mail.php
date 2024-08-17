<?php
    use PHPMailer\PHPMailer\PHPMailer;

    function Send ( $email, $name, $subject, $body )
    {
        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        $mail = new PHPMailer ( );

        // SMTP Settings
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail   -> IsSMTP ( );
        $mail   ->Host = "smtp.gmail.com";
        $mail   ->SMTPAuth  = true;
        $mail   ->Username  = "skoolbet@gmail.com";
        $mail   ->Password  = "qcix jkzu zedo ussu";
        $mail   ->Port      = 587; // TLS = 465
        $mail   ->SMTPSecure= "tls";

        // Email Settings
        $mail   -> isHTML ( true );
        $mail   -> setFrom ( $email, $name );
        $mail   -> addAddress ( $email, $name );
        $mail   -> Subject = $subject;
        $mail   -> Body = $body;

        // Send it
        if ( $mail ->  send ( ) )
        {
            $msg = array (
                "status"    => 1,
                "message"   => "Email sent. Please check your mail!",
            );
        }
        else
        {
            $msg = array (
                "status"    => 0,
                "message"   => "Server not responding. Please try again!"
            );
        }

        return $msg;
    }

    function MailBody ( $type, $word )
    {
        if ( $type == 'forgot' )
        {
            return '
            Your password has changed to <div style="color:#0069cf; width: 100%;padding: 10px;font-size: 20px;">
                ' . $word . '</div>
            You can change it to your preferred password when you login.';
        }
        else if ( $type == 'withdraw' )
        {
            return '
                Payment request made '. $word .'
            ';
        }
    }
?>