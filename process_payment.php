<?php
session_start();
$conn=mysqli_connect('localhost','cashcroptoken_user','z~&Zr[oVB}$r','cashcroptoken_db');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";

$query = "INSERT INTO `naira` (`user_id`, `amount`, `status`, `created_at`) 
          VALUES ('" . $_POST['userid'] . "',
                  '" .$_POST['amount'] . "',
                  '0', 
                  '" . date("Y-m-d") . "')";
                  
$result = mysqli_query($conn, $query);
// print_r($conn); die;
    $curl = curl_init();
    $_SESSION['userid'] = $_POST['userid'];
    $customer_email = $_POST['email']; 
    $amount_pay = $_POST['amount'];
    $userid = $_POST['userid'];
    $currency = "USD";
    $txref = "rave" . uniqid(); // ensure you generate unique references per transaction.
    // get your public key from the dashboard.

  
    $PBFPubKey = "FLWPUBK_TEST-ff4396449f3732d7cd80a17beecdf69e-X"; 
    $redirect_url = "https://cashcroptoken.com/userpanel/flutterwave/redirect.php?email=$customer_email&amount=$amount_pay&userid=$userid";

     curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount'=>$amount_pay,
        'customer_email'=>$customer_email,
        'userid'=>$userid,
        'currency'=>$currency,
        'txref'=>$txref,
        'PBFPubKey'=>$PBFPubKey,
        'redirect_url'=>$redirect_url,
      ]),
      CURLOPT_HTTPHEADER => [
        "content-type: application/json",
        "cache-control: no-cache"
      ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if($err){
      // there was an error contacting the rave API
      die('Curl returned error: ' . $err);
    }

    $transaction = json_decode($response);

    if(!$transaction->data && !$transaction->data->link){
      // there was an error from the API
      print_r('API returned error: ' . $transaction->message);
    }

    // redirect to page so User can pay

    header('Location: ' . $transaction->data->link); 

?>