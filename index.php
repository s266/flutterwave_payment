<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Wallet</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
</head>
<body>
    <div class="container-xxl flex-grow-1 container-p-y">
     <div class="row">
       <div class="col-xl">
         <div class="card mb-4">
           <div class="card-header d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Fund account with Flutterwave</h5>
            </div>
            <div class="card-body">
              <form method="post" action="process_payment.php" id="form">                  
                <div class="mb-3">
                  <label class="form-label" for="phone-number">Amount*</label>
                   <input type="tel" name="amount" class="form-control" required>
                </div>

                <div class="mb-3">
                  <input type="email" name="email" class="form-control"  required>
                </div>
                <div class="mb-3">
                  <input type="tel" name="phone" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary action" name="pay"> Pay</button>
              </form>
            </div>
          </div>
        </div>

      </div>          
   </div>
  <script src="jquery.min.js"></script>
  <script src="bootstrap.min.js"></script>
</body>
</html>