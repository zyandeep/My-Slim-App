<?php
// eGRAS will call this page for two reasons -- Make payment and Verify payment with Bank(getCIN) 
// Transaction status
/*
    Y => Successful
    P => Pending
    N => Fail
*/


require '../../src/controller/EgrasResponse.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get all the parameters POSTed by e-GRAS

    $egras = new EgrasResponse();

    $dept_id = trim(filter_var($_POST['DEPARTMENT_ID'], FILTER_SANITIZE_STRING));
    $grn = trim(filter_var($_POST['GRN'], FILTER_SANITIZE_STRING));
    $amount = trim(filter_var($_POST['AMOUNT'], FILTER_SANITIZE_STRING));
    $status = trim(filter_var($_POST['STATUS'], FILTER_SANITIZE_STRING));
    $bank_code = trim(filter_var($_POST['BANKCODE'], FILTER_SANITIZE_STRING));
    $cin = trim(filter_var($_POST['BANKCIN'], FILTER_SANITIZE_STRING));
    $prn = trim(filter_var($_POST['PRN'], FILTER_SANITIZE_STRING));
    $date_time = trim(filter_var($_POST['TRANSCOMPLETIONDATETIME'], FILTER_SANITIZE_STRING));
    $name = trim(filter_var($_POST['PARTYNAME'], FILTER_SANITIZE_STRING));
    $tax_id = trim(filter_var($_POST['TAXID'], FILTER_SANITIZE_STRING));
    $bank_name = trim(filter_var($_POST['BANKNAME'], FILTER_SANITIZE_STRING));
    $entry_date = trim(filter_var($_POST['ENTRY_DATE'], FILTER_SANITIZE_STRING));         // challan date

    // get OFFICE_CODE 
    $office_code = $egras->getOfficeCode($dept_id);

    // make GETGRN call to verify the transaction status
    $arr =  $egras->getGRN($dept_id, $office_code, $amount);

    if ($arr != null) {
        $cin = trim(filter_var($arr[10], FILTER_SANITIZE_STRING));
        $prn = trim(filter_var($arr[12], FILTER_SANITIZE_STRING));
        $date_time = trim(filter_var($arr[14], FILTER_SANITIZE_STRING));
        $status = trim(filter_var($arr[16], FILTER_SANITIZE_STRING));

        // update the $_POST array
        $_POST['BANKCIN'] = $cin;
        $_POST['PRN'] = $prn;
        $_POST['TRANSCOMPLETIONDATETIME'] = $date_time;
        $_POST['STATUS'] = $status;
    }
    else {
        // unsecccessfull GETGRN call
    }

    if ($status == 'Y') {
        // let the user download the challan right from the web page
        // so, build the POST parameters
        
        $params = "DEPARTMENT_ID=$dept_id&GRN=$grn&OFFICE_CODE=$office_code&AMOUNT=$amount&ACTION_CODE=Y";
        $url = "http://download_challan?$params";
    }
    elseif ($status == 'P') {
        // let the user verify the transaction status right from the web page
        // so, build the POST parameters
        
        $params = "DEPARTMENT_ID=$dept_id&OFFICE_CODE=$office_code&AMOUNT=$amount&ACTION_CODE=GETCIN&SUB_SYSTEM=GRAS-APP";
        $url = "http://verify_transaction?$params";
    }

    // writting to egras_response
    $egras->updateTransaction(array(
        $grn,
        json_encode($_POST),                                        // responseparameters
        $amount,
        $cin,
        $entry_date,
        $status,
        'O-' . $bank_code,                                          // mop
        $dept_id
    ));
    
    // writting to egras_log
    $egras->updateLog(array(json_encode($_POST), $dept_id));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        a {
  		    color: #FF9800;
  		    text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($status == 'Y'): ?>
            <div class="card-panel z-depth-3 green darken-1">
                <div class="white-text center-align">PAYMENT SUCCESSFUL!</div>
            </div>
            <p class="flow-text">
                <a href="<?php echo isset($url) ? $url : ''; ?>">You can now download the respective challan.</a> 
            </p>

        <?php elseif ($status == 'P'): ?>
            <div class="card-panel z-depth-3 yellow lighten-1">
                <div class="black-text center-align">PAYMENT IS PENDING!</div>
            </div>
            <p class="flow-text">
                <a href="<?php echo isset($url) ? $url : ''; ?>">Click here to verify transaction status...</a>
            </p>

        <?php else: ?>
            <div class="card-panel z-depth-3 red lighten-2">
                <div class="white-text center-align">PAYMENT FAILED!</div>
            </div>
            <p class="flow-text">
                We are sorry that your transaction couldn't be processed. Try again later.
            </p>

        <?php endif; ?>
        
        <div class="section">
            <strong>GRN No:</strong>
            <p><?php echo $grn; ?></p>
        </div>
        <div class="divider"></div>
        <div class="section">
            <strong>Payee Name:</strong>
            <p><?php echo $name; ?></p>
        </div>
        <div class="divider"></div>
        <div class="section">
            <strong>Amount:</strong>
            <p>&#8377;<?php echo $amount; ?></p>
        </div>
        <div class="divider"></div>
        <div class="section">
            <?php if ($status == 'Y'): ?>
                <strong>Date and Time:</strong>
                    <p>
                        <?php echo $entry_date . " @" . substr($date_time, -6, 2) . ":" . substr($date_time, -4, 2);?>
                    </p>
            <?php else: ?>
                <strong>Date:</strong>
                <p><?php echo $entry_date; ?></p>
            <?php endif; ?>
        </div>
        <div class="divider"></div>
        <div class="section">
            <strong>Bank Name:</strong>
            <p><?php echo $bank_name; ?></p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>