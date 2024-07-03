<?php

    session_start();
    include('server.php');

    //what is date today
    date_default_timezone_set('Asia/Bangkok');
    $dt = new DateTime();
    $pay_time = $dt->format('Y-m-d H:i:s');

    $query = "SELECT ID_Member FROM member WHERE member.userID = '".$_SESSION['username']."'";
    $result = mysqli_query($conn,$query);
    $mid = "";
    while($row=mysqli_fetch_array($result))
    {
     $mid = mysqli_real_escape_string($conn, $row[0]);        
    }
    

    //ticket attri
    $query = "SELECT ID_Ticket,ID_Seat FROM `ticket` WHERE ID_Member = '$mid' AND Ticket_Status = 'N';";
    $result = mysqli_query($conn,$query);
    $idticket = array();
    $idSeat = array();
    while($row=mysqli_fetch_array($result))
    {
      $idticket[] = $row[0];
      $idSeat[] = $row[1];      
    }

    if(isset($_POST['tickpay']))
    {
        //change status ticket payment
        foreach($idticket as $each)
        {
            $query = "UPDATE `ticket` SET `Ticket_Status` = 'P' WHERE `ticket`.`ID_Ticket` = '$each'";
            $result = mysqli_query($conn,$query);            
        }
        echo '<br>';
        print_r($idticket);
        echo '<br>';
        //get lastest ticket
        $query = "SELECT Payment_ID FROM ticket_payment ORDER BY Payment_ID DESC LIMIT 1;";
        $result = mysqli_query($conn,$query);
        $last_payment = "";
        while($row=mysqli_fetch_array($result))
        {
            $last_payment = mysqli_real_escape_string($conn, $row[0]);
            echo $last_payment;
        }

        //generate new payment
        $price = "";
        foreach($idticket as $each)
        {
            $last_payment++;
            echo $last_payment;

            $query = "SELECT `ID_Seat` FROM `ticket` WHERE ID_Ticket = '$each';";
            $result = mysqli_query($conn,$query);
            while($row=mysqli_fetch_array($result))
            {
                $price = mysqli_real_escape_string($conn, $row[0]);
            }
            $query ="SELECT `Seat_price` FROM `seat_reserve` WHERE ID_Seat = '$price';";
            $result = mysqli_query($conn,$query);
            while($row=mysqli_fetch_array($result))
            {
                $price = mysqli_real_escape_string($conn, $row[0]);
                echo $price;
            }
            echo '<br>';

            $query = "INSERT INTO `ticket_payment`(`Payment_ID`, `ID_Ticket`, `ID_Member`, `payment_date_time`, `payment_amount`) 
            VALUES ('$last_payment','$each','$mid',' $pay_time','$price');";
            $result = mysqli_query($conn,$query);

            $query = "UPDATE `ticket` SET `Ticket_Status` = 'P' WHERE `ticket`.`ID_Ticket` = '$each'";
            $result = mysqli_query($conn,$query);            
        }
        
        //change seat status
        foreach($idSeat as $each)
            {
                $query = "UPDATE `seat_reserve` SET `Seat_status` = 'R' WHERE `seat_reserve`.`ID_Seat` = '$each'";
                $result = mysqli_query($conn,$query);
            }
        
    }

    if(isset($_POST['CCtickpay']) || isset($_POST['tickback']))
    {
        foreach($idSeat as $each)
        {
            $query = "UPDATE `seat_reserve` SET `Seat_status` = 'A' WHERE `seat_reserve`.`ID_Seat` = '$each'";
            $result = mysqli_query($conn,$query);
        }
        
        foreach($idticket as $each)
        {
            $query = "DELETE FROM `ticket` WHERE `ticket`.`ID_Ticket` ='$each'";
            $result = mysqli_query($conn,$query);            
        }
    }
    header('location: sumticket.php')
?>