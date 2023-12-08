<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $qr = "2|99|0377457747|||0|0|100000|hello|transfer_p2p";
    // $qr = "00020101021238540010A00000072701240006963388011003774577470208QRIBFTTA53037045405200005802VN62220818Test";
    // echo timestamp
    $timeStamp = time()*1000;
    echo "Thời điểm hiện tại: " . $timeStamp . "<br>";
    echo date('d-m-Y H:i:s', ($timeStamp + 300000)) . "<br>";
    ?>

    <img src="https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=<?=$qr?>;&choe=UTF-8"/>
    <img src='https://api.vietqr.io/image/963388-0377457747-Tcntxkf.jpg?accountName=BUI%20HUU%20HAU&amount=123123&addInfo=Hehe'/>


</body>

</html>