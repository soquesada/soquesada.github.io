<!-- Ooops... Didn't realise Github Pages doesn't support PHP -->

<?php
    if (!isset($_POST['submit'])) {
        echo "Error: You need to submit the form.";
    }

    $errors = [];

    if (!empty($_POST)) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Validate
        if (empty($name)) {
            $errors[] = 'Name is empty';
        }
        if (empty($email)) {
            $errors[] = 'Email is empty';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is invalid';
        }
        if (empty($message)) {
            $errors[] = 'Message is empty';
        }
    }

    if (empty($errors)) {
        $toEmail = 'sophia_elisha@yahoo.co.uk';
        $emailSubject = 'New email from your contact form';
        // $headers = ['From' => $email, 'Reply-To' => $email, 'Content-type' => 'text/html; charset=iso-8859-1'];
        $headers = "From: ".$email;
        // $bodyParagraphs = ["Name: {$name}", "Email: {$email}", "Message:", $message];
        // $body = join(PHP_EOL, $bodyParagraphs);
        $body = "You have received an email from ".$name.".\n\n".$message;
        
        if (mail($toEmail, $emailSubject, $body, $headers)) {
            header('Location: index.php?mailsend');
        } else {
            $errorMessage = 'Oops, something went wrong. Please try again later';
        }
    } else {
        $allErrors = join('<br/>', $errors);
        $errorMessage = "<p style='color: red;'>{$allErrors}</p>";
    }

    if (IsInjected($name) || IsInjected($visitor_email) || IsInjected($message)) {
        echo "Bad value";
        exit;
    }

    // $email_from = "sophia_elisha@yahoo.co.uk";
    // $email_subject = "Portfolio Website Contact";
    // $email_body = "You have received a new message from the user $name.\n" . 
    //               "Email address: $visitor_email\n" . 
    //               "Message:\n $message";

    // $to = $_POST['email'];
    // $headers = "From: $email_from \r\n";

    // // Send the email
    // $retval = mail($to, $email_subject, $email_body, $headers);

    // if ($retval == true) {
    //     echo "Message sent successfully.";
    // } else {
    //     echo "Message could not be sent.";
    // }

    // Function to validate against any email injection attempts
    function IsInjected($str) {
        $injections = array(
            '(\n+)',
            '(\r+)',
            '(\t+)',
            '(%0A+)',
            '(%0D+)',
            '(%08+)',
            '(%09+)'
        );

        $inject = join('|', $injections);
        $inject = "/$inject/i";
        if (preg_match($inject, $str)) {
            return true;
        } else {
            return false;
        }
    }
?>