<?php

// For debugging
// ini_set('display_errors', 1);
// TODO: takes care of the form submission

// 4. Return the proper info in JSON format.[Checked]
//   a. What is AJAX?
//       AJAX is a way for the browser to send data without reloading the page.
//   b. What is JSON (in PHP)
//       JSON is a filetype that works natively with JS. It took over XML 
//   c. How to build JSON (in PHP) 

header('Access-Control-Allow-Origin*'); 
header('Content-Type: application/json; charset=UTF-8');  
$results =[];
$visitor_name = '';
$visitor_email = '';
$visitor_message = '';
$required_type = '';



// 1. Check the submission --> Validate the data [is there non-mailable items?]
// $results = $_POST;



if(isset($_POST['firstname'])){
    $visitor_name = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
} 


if(isset($_POST['lastname'])){
    $visitor_name .= ' '.filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
}

if (isset($_POST['email'])){
    $visitor_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
}

if (isset($_POST['message'])){
    $visitor_message = filter_var(htmlspecialchars($_POST['message']), FILTER_SANITIZE_STRING);
}

//Validate the data for dropdown field 

if(isset($_POST['required_type'])) {
    $required_type = filter_var($_POST['required_type'], FILTER_SANITIZE_STRING);
   
}
  
$results['name'] = $visitor_name;
$results['message'] = $visitor_message;

// 2. Prepare the email [Prepare out the label and put on the package / prepare it in a certain format.]

// trigger email to different recipients (depends of topic)
if($required_type == "cooperation") {
    $email_recepient = 'cooperation@elche.work';
}
else if($required_type == "support") {
    $email_recepient = 'support@elche.work';
}
else if($required_type == "other") {
    $email_recepient = 'other@elche.work';
}
else {
    $email_recepient = 'contact@elche.work';
}

$email_subject = 'Inquiry from Portfolio Site';

// adding the topic to the email
$email_message = sprintF('Name: %s, Email: %s, Topic: %s, Message: %s', $visitor_name, $visitor_email, $required_type, $visitor_message);  

$email_headers = array(
    // best practice, but it may need to you have a mail setup in noreply@yourdomain.ca
    // 'From'=>'noreply@elche.work',
    'Reply-To' => $visitor_email,
    'From'=> $visitor_email
    
);


// 3. Send out the email [Send out the package]
$email_result = mail($email_recepient, $email_subject, $email_message, $email_headers);

if($email_result) {
    $results['message'] = sprintf('Thank you for contacting us, %s. You should receive a reply within 24 hours', $visitor_name);
} else {
    $results['message'] = sprintf('%s, we are sorry, but the email did not send.');
}
// if(empty($_POST['firstname'])) {
//     header('HTTP/1.1 488 I need your email!');
//     die(json_encode(['message' => "form subm f"]));

// } else {
//     echo json_encode(['message' => 'hi']);
// }



echo json_encode($results);

?>