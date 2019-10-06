<?php
    session_start();

    if (!$_SESSION['isAuth'])
    {
        header("Location: ../auth.html");
    }

    require_once "./db.php";

    function calculateNextWriteoffDate()
    {
        $date = date_create();
        date_add($date, date_interval_create_from_date_string('1 month'));
        $date = date_format($date, 'Y-m-d');
        return $date;
    }

    $fio = $_GET['fio'];
    $amountEntered = (int)$_GET['amount_entered'];
    $bonuses = floor($amountEntered / 500) * 10;
    $nextWriteoffDate = calculateNextWriteoffDate();

?>
<head>
    <link rel="stylesheet" href="./../css/index.css">
</head>

<div id="info">
<?
    $lastId = Database::query("SELECT id_client FROM clients ORDER BY id_client DESC")['id_client'];
    $lastId++;
    echo "<p> <strong> ID клиента - $lastId </strong> </p>";
    Database::queryExecute("INSERT INTO clients VALUES (NULL, '$fio', '$bonuses', '$nextWriteoffDate')");
    echo '<p> <strong> Количество бонусов, которое было изначально зачислено: </strong> <br>' . $bonuses . '</p>';
    echo '<p> <strong> Вы будете перенаправлены на главную страницу через: </strong> <br> <span id="timeout">5</span></p>';
?>
</div>

<script>
    var timeout = document.querySelector('#timeout');
    var timer = parseInt(timeout.textContent);
    var currentValue = timer;

    setInterval(function () 
    {
        currentValue--;
        timeout.textContent = currentValue;
    }, 1000);

    setTimeout(function () 
    {
        location.href = '../index.php';
    }, 5000);
    
</script>