<?php
    session_start();
    include('server.php');

    //who booked
    $query = "SELECT ID_Member FROM member WHERE member.userID = '".$_SESSION['username']."'";
    $result = mysqli_query($conn,$query);
    $mid = "";
    while($row=mysqli_fetch_array($result))
    {
        $mid = mysqli_real_escape_string($conn, $row[0]);
        echo $mid;
    }

    //what is date today
    date_default_timezone_set('Asia/Bangkok');
    $dt = new DateTime();
    $order_time = $dt->format('Y-m-d H:i:s');
    echo $order_time;

    $errors = array();

    //which location?
    $query = "SELECT ID_Location FROM event_s WHERE event_s.ID_Event = '".$_SESSION['eventid']."'";
    $result = mysqli_query($conn,$query);
    $loc = "";
    while($row=mysqli_fetch_array($result))
    {
        $loc = mysqli_real_escape_string($conn, $row[0]);
        echo $loc;
    }
    echo '<br>';

    $query = "SELECT DISTINCT Seat_zone FROM `seat_reserve` WHERE ID_Location = '$loc'AND Seat_zone IS NOT NULL;";
    $result = mysqli_query($conn,$query);
    $temp = "";
    while($row=mysqli_fetch_array($result))
    {
        $temp = $row[0];
        //echo '<br>';
        print_r($row);
    }
    //echo $temp;

    if(isset($_POST['firmseat'])) {

        //with zone
        if(!empty($temp))
        {            
            $seat = mysqli_real_escape_string($conn, $_POST['seat']);            
            $szone = $seat[0];
            $snum = (int)$seat[1];
           
            //select seatid
            $query ="SELECT ID_Seat FROM seat_reserve WHERE ID_Location = '$loc' AND Seat_zone = '$szone' AND (Seat_status = 'A') AND Seat_number = $snum;";
            $result = mysqli_query($conn,$query);
            $seatat = "";

            while($row=mysqli_fetch_array($result))
            {
                $seatat = $row[0];
                //echo '<br>';                
            }
            echo $seatat;

            //update seat status            
            $seatat = mysqli_real_escape_string($conn, $seatat);
            $query = "UPDATE `seat_reserve` SET `Seat_status` = 'R' WHERE `seat_reserve`.`ID_Seat` = '$seatat';";
            $result = mysqli_query($conn,$query);
            
            //get lastest ticket
            $query = "SELECT ID_Ticket FROM ticket ORDER BY ID_Ticket DESC LIMIT 1;";
            $result = mysqli_query($conn,$query);
            $last_ticket = "";
            while($row=mysqli_fetch_array($result))
            {
                $last_ticket = mysqli_real_escape_string($conn, $row[0]);
                echo $last_ticket;
            }

            //generate new ticket
            $evetid = $_SESSION['eventid'];
            $last_ticket++;
            echo $last_ticket;
                
            //update
            $query = "INSERT INTO `ticket`(`ID_Ticket`, `ID_Member`, `ID_Event`, `ID_Seat`, `CCstatus`, `booking_date`, `Ticket_Status`) 
                        VALUES ('$last_ticket','$mid','$evetid','$seatat','N','$order_time','N');";                
            $result = mysqli_query($conn,$query);
            
            
        }

        //without seat zone
        if(empty($temp))
        {
            $ticket_qty = mysqli_real_escape_string($conn, $_POST['ticket_qty']);
            echo '<br>'.$ticket_qty.'<br>';

            //select seatid
            $query ="SELECT ID_Seat FROM `seat_reserve` WHERE ID_Location = '$loc' AND Seat_status = 'A' LIMIT $ticket_qty;";
            $result = mysqli_query($conn,$query);
            $seatat = array();
            
            while($row=mysqli_fetch_array($result))
            {
                $seatat[] = $row[0];
                //echo '<br>';                
            }
            print_r($seatat);
            
            /*
            //update seat status
            foreach($seatat as $aseat)
            {
                $aseat = mysqli_real_escape_string($conn, $aseat);
                $query = "UPDATE `seat_reserve` SET `Seat_status` = 'R' WHERE `seat_reserve`.`ID_Seat` = '$aseat';";
                $result = mysqli_query($conn,$query);    
            }
            */

            //get lastest ticket
            $query = "SELECT ID_Ticket FROM ticket ORDER BY ID_Ticket DESC LIMIT 1;";
            $result = mysqli_query($conn,$query);
            $last_ticket = "";
            while($row=mysqli_fetch_array($result))
            {
                $last_ticket = mysqli_real_escape_string($conn, $row[0]);
                echo $last_ticket;
            }

            //generate new ticket
            $evetid = $_SESSION['eventid'];
            for((int)$i = 0; $i<$ticket_qty;$i++)
            {
                $last_ticket++;
                echo $last_ticket;
                $sat = $seatat[$i];
                //update
                $query = "INSERT INTO `ticket`(`ID_Ticket`, `ID_Member`, `ID_Event`, `ID_Seat`, `CCstatus`, `booking_date`, `Ticket_Status`) 
                            VALUES ('$last_ticket','$mid','$evetid','$sat','N','$order_time','N');";                
                $result = mysqli_query($conn,$query);
            }


            
        }
        
        
    }



    header('location: sumticket.php');
?>