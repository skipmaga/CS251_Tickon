<?php 
    date_default_timezone_set('Asia/Bangkok');
    session_start();
    include('server.php');

    $errors = array();


    if(isset($_POST['Buy'])) {
        $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    }
    
    if(count($errors)==0){
        $query = "SELECT ID_Member FROM member WHERE member.userID = '".$_SESSION['username']."'";
        $result = mysqli_query($conn,$query);
        $mid = "";
        while($row=mysqli_fetch_array($result))
        {
            $mid = mysqli_real_escape_string($conn, $row[0]);
            echo $mid;
        }
        $query2 = mysqli_query($conn, "SELECT * FROM order_s ORDER BY ID_Order DESC LIMIT 1");
        $ID_pre = '';
        $ID_new = '';
        while($row=mysqli_fetch_array($query2))
        {
            $ID_pre = $row[0];
            $ID_new = 'OR'. str_pad(intval(substr($ID_pre,2)) + 1, 5, '0', STR_PAD_LEFT);
        }
        $merID =  $_SESSION['idMer'];
        $merUnit = $_SESSION['Mer_price'];
        $dt = new DateTime();
        $order_time = $dt->format('Y-m-d H:i:s');
        $sqldb = "INSERT INTO order_s (ID_Order,ID_Member, CCstatus, order_date,Shipping_date,Shipping_Address,Tracking_Num,Order_Status) VALUES ('$ID_new','$mid', '-', '$order_time','2022-00-00 00:00:00','บ้าน','-','U')";
        mysqli_query($conn,$sqldb);
        $sqldb2 = "INSERT INTO order_mer_detail (ID_Order,ID_Mer, unit_price, quantity) VALUES ('$ID_new','$merID', '$merUnit', '$quantity')";
        mysqli_query($conn,$sqldb2);
        header('location: cart.php');
    }
?>