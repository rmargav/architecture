<!DOCTYPE html>
<html lang="zxx">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- color of address bar in mobile browser -->
	<meta name="theme-color" content="#28292C">
	<!-- favicon  -->
	<link rel="shortcut icon" href="img/light/favicon.png" type="image/x-icon">
	<!-- bootstrap css -->
	<link rel="stylesheet" href="css/plugins/bootstrap.min.css">
	<!-- font awesome css -->
	<link rel="stylesheet" href="css/plugins/font-awesome.min.css">
	<!-- swiper css -->
	<link rel="stylesheet" href="css/plugins/swiper.min.css">
	<!-- fancybox css -->
	<link rel="stylesheet" href="css/plugins/fancybox.min.css">
	<!-- mapbox css -->
	<link href="css/plugins/mapbox-style.css" rel='stylesheet'>
	<!-- main css -->
	<link rel="stylesheet" href="css/style-light.css">

	<title>Seeking Roots</title>

</head>

<body onLoad="setTimeout('delayedRedirect()', 5000)">

	<?php

	$errors = ''; // Initialize the errors variable

	/* Validate User Inputs
	==================================== */

	// Name
	if (isset($_POST['firstName']) && $_POST['firstName'] != '') {
		// Sanitizing (Updated for PHP 8+ compatibility)
		$_POST['firstName'] = htmlspecialchars(strip_tags($_POST['firstName']));
		if ($_POST['firstName'] == '') {
			$errors .= 'Please enter a valid first name.<br/>';
		}
	} else {
		$errors .= 'Please enter your first name.<br/>';
	}

	// Last Name
	if (isset($_POST['lastName']) && $_POST['lastName'] != '') {
		// Sanitizing
		$_POST['lastName'] = htmlspecialchars(strip_tags($_POST['lastName']));
		if ($_POST['lastName'] == '') {
			$errors .= 'Please enter a valid last name.<br/>';
		}
	} else {
		$errors .= 'Please enter your last name.<br/>';
	}

	// Email
	if (isset($_POST['email']) && $_POST['email'] != '') {
		// Sanitizing
		$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		// Validation
		$_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		if(!$_POST['email']) {
			$errors .= 'Please enter a valid email address.<br/>';
		}
	} else {
		$errors .= 'Please enter your email address.<br/>';
	}

	// Phone
	if (isset($_POST['phone']) && $_POST['phone'] != '') {
		// Sanitizing
		$_POST['phone'] = htmlspecialchars(strip_tags($_POST['phone']));
		// Validation
		$pattern_phone = array('options'=>array('regexp'=>'/^\+{1}[0-9]+$/'));
		if(!filter_var($_POST['phone'], FILTER_VALIDATE_REGEXP, $pattern_phone)) {
			$errors .= 'Please enter a valid phone number like: +919879403481<br/>';
		}
	}

	// Message
	if (isset($_POST['message']) && $_POST['message'] != '') {
		// Sanitizing
		$_POST['message'] = htmlspecialchars(strip_tags($_POST['message']));
		if($_POST['message'] == '') {
			$errors .= 'Please enter a valid message.<br/>';
		}
	} else {
        $errors .= 'Please enter your message.<br/>';
    }

	// Continue if NO errors found after validation
	if (!$errors) {

		// Customer Details
		$customer_first_name = $_POST['firstName'];
		$customer_last_name  = $_POST['lastName'];
		$customer_mail       = $_POST['email'];
		$customer_phone      = isset($_POST['phone']) ? $_POST['phone'] : 'Not provided';
		$customer_message    = $_POST['message'];

		/* Mail Sending
		==================================== */

		// Setup for site owner
		$to = "seekingroots09@gmail.com"; 
		$subject = "New Contact Form Submission - Seeking Roots";
		
        // Headers for site owner email
        $headers = "From: Seeking Roots Website <noreply@seekingroots.co.in>\r\n";
        $headers .= "Reply-To: " . $customer_mail . "\r\n"; // Allows client to hit "Reply" directly to the customer
        
		$message = "A new request has arrived with the following details:\n\n";
		$message .= "CONTACT DATA\n";
		$message .= "-----------------------\n";
		$message .= "First Name: " . $customer_first_name . "\n";
		$message .= "Last Name: " . $customer_last_name . "\n";
		$message .= "Email: " . $customer_mail . "\n";
		$message .= "Phone: " . $customer_phone . "\n\n";
		$message .= "MESSAGE\n";
		$message .= "-----------------------\n";
		$message .= $customer_message . "\n";

		// Send to site owner
		mail($to, $subject, $message, $headers);

		// Setup for the user (Auto-responder)
		$usersubject = "Thank you for reaching out to Seeking Roots";
        $user_headers = "From: Seeking Roots <seekingroots09@gmail.com>\r\n";
        
		$usermessage = "Dear " . $customer_first_name . " " . $customer_last_name . ",\n\n";
        $usermessage .= "Thank you for contacting us. We have received your message and will reply shortly.\n\n";
		$usermessage .= "Best Regards,\n";
		$usermessage .= "Seeking Roots Team";

		// Send auto-reply to the user
		mail($customer_mail, $usersubject, $usermessage, $user_headers);

		// Success Page
		echo '<div id="success">';
		echo '<div class="icon icon-order-success svg">';
		echo '<svg width="72px" height="72px">';
		echo '<g fill="none" stroke="#02b843" stroke-width="2">';
		echo '<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>';
		echo '<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>';
		echo '</g>';
		echo '</svg>';
		echo '</div>';
		echo '<h4>Thank you for contacting us.</h4>';
		echo '<small>We will get back to you soon.</small>';
		echo '</div>';
        
        // Ensure redirect works even if JS file is missing
        echo '<meta http-equiv="refresh" content="5;url=contact.html">';
		echo '<script src="js/redirect.js"></script>';

	} else {
		// Error Page
		echo '<div style="color: #e9431c; text-align: center; margin-top: 100px;">' . $errors . '</div>';
		echo '<div id="success">';
		echo '<h4>Something went wrong.</h4>';
		echo '<a class="mry-link" href="contact.html">Go Back</a>';
		echo '</div>';
	}
	?>

</body>
</html>