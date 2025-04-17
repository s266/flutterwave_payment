<?php
session_start();

if (isset($_GET['txref'])) {
  $ref = $_GET['txref'];
  $amount = $_GET['amount']; //Get the correct amount of your product
  $email = $_GET['email'];
  $currency = "USD"; //Correct Currency from Server

  $query = array(
    "SECKEY" => "FLWSECK_TEST-37c4f5c7626f1b7e8db0b47a19d4ff20-X",
    "txref" => $ref
  );

  $data_string = json_encode($query);

  $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');                                                                      
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

  $response = curl_exec($ch);
  
 
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $header = substr($response, 0, $header_size);
  $body = substr($response, $header_size);

  curl_close($ch);

  $resp = json_decode($response, true);
// print_r($resp['data']['custemail']);
// // print_r($_SESSION['userid']);
// die;
    // $userid = $_SESSION['userid'];
  $emailid = $resp['data']['custemail'];
  $paymentid = $resp['data']['paymentid'];
  $paymentStatus = $resp['data']['status'];
  $chargeResponsecode = $resp['data']['chargecode'];
  $chargeAmount = $resp['data']['amount'];
  $chargeCurrency = $resp['data']['currency'];
    $wtype="Add Fund";
  if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
  
            $conn=mysqli_connect('localhost','cashcroptoken_user','z~&Zr[oVB}$r','cashcroptoken_db');

            // print_r($conn); die;
            
            //   $urls="http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            
               $wtype='Fund Wallet';
               
             $rand = $result['payment_id'];
             
             $uesr_reg =mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM user_registration where email='$emailid'"));
				//   print_r($uesr_reg['user_id']); die;
             $user_id = $uesr_reg['user_id'];
             
            //   mysqli_query($conn, "UPDATE `naira` SET `status`=1,`payment_id`='$paymentid' WHERE id=".$data['id']);
            mysqli_query($conn, "UPDATE `naira` SET `status` = 1, `payment_id` = '$paymentid' WHERE id = ( SELECT id FROM (SELECT id FROM `naira` WHERE user_id = $user_id ORDER BY id DESC LIMIT 1 ) AS last_record) ");
              
            mysqli_query($conn,"INSERT INTO `final_e_wallet_history` (`payment_id`,`user_id`,`amount`,`remarks`,`date`) VALUES('$paymentid','".$user_id."','".$chargeAmount."','Naira Funds','".date('Y-m-d')."')");
               
            mysqli_query($conn, "update final_e_wallet set amount=(amount+'".$chargeAmount."') where user_id='".$user_id."'");
                
            mysqli_query($conn, "INSERT INTO `credit_debit` (`id`, `transaction_no`, `user_id`, `credit_amt`, `debit_amt`, `admin_charge`, `receiver_id`, `sender_id`, `receive_date`, `ttype`, `TranDescription`, `Cause`, `Remark`, `invoice_no`, `product_name`, `status`, `ewallet_used_by`, `ts`) VALUES (NULL, '$rand
                        ','".$user_id."', '".$chargeAmount."', '0', '0', '".$user_id."', '123456', '".date('Y-m-d')."', 'Fund Deposit',  'Bulk Bonus', 'Fund Credited By Admin','Fund Credited By Admin', '".$paymentid."', 'Naira Funds', '0', '$wtype', CURRENT_TIMESTAMP)");    
                
      
           
    header('location: success.php');
  } else {
    //Dont Give Value and return to Failure page
    // var_dump($resp);
    header('location: error.html');
  }
}
else {
  die('No reference supplied');
}

?>
