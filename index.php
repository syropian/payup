<?php
require 'lib/Stripe.php';
function getRequestProtocol() {
    return $_SERVER['HTTP_X_FORWARDED_PROTO'];
}
if(getRequestProtocol() == 'http') {
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $redirect");
}
if ($_POST) {
  //Test
  //Stripe::setApiKey("YourTestPrivateKey");
  
  //Production
  Stripe::setApiKey("YourProductionPrivateKey");
  $error = '';
  $success = '';
  try {
    if (!isset($_POST['stripeToken']))
      throw new Exception("The Stripe Token was not generated correctly");
  Stripe_Charge::create(array("amount" => $_POST['chargeAmount'],
    "currency" => "cad",
    "card" => $_POST['stripeToken']));
  $success = 'Your payment was successful.';
}
catch (Exception $e) {
    $error = $e->getMessage();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link rel="apple-touch-icon-precomposed" href="img/mobile-icon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Pay Up!</title>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="animate.css">
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
    <!-- jQuery is used only for this example; it isn't required to use Stripe -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="jquery.payment.js"></script>
    <script type="text/javascript" src="payup.js"></script>
    <script type="text/javascript">document.addEventListener("touchstart", function() {},false);</script>
    <script type="text/javascript">
        if (window.screen.height==568) { // iPhone 4"
            document.querySelector("meta[name=viewport]").content="width=320.1, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0";
    }
    </script>
</head>
<body onload="setTimeout(function() { window.scrollTo(0, 1) }, 100);">
    <div class="container animated fadeIn">
        <h3>Pay Up!</h3>
        <p>All transactions are handled securely through <a href="http://stripe.com">Stripe</a></p>
        <!-- to display errors returned by createToken -->
        <?php if(isset($error) && $error != ''):?>
        <span class="payment-errors"><?= $error ?></span>
    <?php endif;?>
    <?php if(isset($success) && $success != ''):?>
    <span class="payment-success"><?= $success ?></span>
<?php endif;?>
<form action="" method="POST" id="payment-form" autocomplete="on">
    <div class="form-row">
        <input type="text" class="buyer-name" placeholder="Your Full Name" />
    </div>
    <div class="form-row">
        <input type="text" class="charge-amount" placeholder="Amount" pattern="\d*" />
    </div>
    <div class="form-row">
        <input type="text" class="card-number" placeholder="Card Number" pattern="\d*" />
    </div>
    <div class="form-row">
        <div class="halfsies"><input type="text" maxlength="4" autocomplete="off" class="card-cvc" placeholder="CVC" pattern="\d*" /></div>
        <div class="halfsies"><input type="text" class="card-expiry" placeholder="MM / YY" pattern="\d*"/></div>
    </div>
    <div class="cf"></div>
    <div class="form-row">
        <button type="submit" class="submit-button">Submit Payment</button>
    </div>
</form>
</div>
</body>
</html>