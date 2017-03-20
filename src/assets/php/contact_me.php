<?php

// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
       /* "No arguments Provided!" */
	echo "noarg";
   }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Construct the Google verification API request link.
    $params = array();
    $params['secret'] = urlencode(''); // Secret key
    if (!empty($_POST) && isset($_POST['grecaptcharesponse'])) {
        $params['response'] = urlencode($_POST['grecaptcharesponse']);
    }
    $params['remoteip'] = urlencode($_SERVER['REMOTE_ADDR']);

    //url-ify the data for the POST
    $params_string = http_build_query($params);

    //extract data from the post
    //set POST variables
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    //open connection
    $ch = curl_init();

    /* set the url, set method as POST, include parameters,
       accepts any certificate given to it rather than verifying them (fails with this url if not set to false),
       return the transfer as a STRING of the return value of curl_exec() instead of outputting it out directly,
       don't include header in output
    */
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $params_string);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false);

    //execute post. curl_response JSON string
    $response = curl_exec($ch);

    //close connection
    curl_close($ch);

    // convert JSON string to array
    $response = @json_decode($response, true);

    if ($response["success"]) {

        /* successful
           '{
              "success": true,
              "challenge_ts": "2016-03-10T14:05:46Z",
              "hostname": "localhost"
            }'
         */

        $name = $_POST['name'];
        $email_address = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];
/*
        // Create the email and send the message
        $to = 'yourname@yourdomain.com'; // Add your email address in between the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
        $email_subject = "Website Contact Form:  $name";
        $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";
        $headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
        $headers .= "Reply-To: $email_address";
        mail($to,$email_subject,$email_body,$headers);
*/
        echo "sent";

    } else {
        /* unsuccessful
           '{
              "success": false,
              "error-codes": [
                "missing-input-response"
              ]
            }'
         */
        echo "robot";
    }
}
