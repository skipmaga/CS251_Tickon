<?php 
    date_default_timezone_set('Asia/Bangkok');
    session_start();
    include('server.php');

    $errors = array();


    if(isset($_POST['Pay'])) {
        $user = $_SESSION['username'];

        $findID = mysqli_query($conn,"SELECT * FROM member WHERE userID = '$user'");
        $idmember = '';
        if(mysqli_num_rows($findID)>0)
        {
            while($row = mysqli_fetch_row($findID)) {
                $idmember = $row[0];
            }
        }

        $result2 = mysqli_query($conn,"SELECT * FROM order_s INNER JOIN order_mer_detail WHERE order_s.ID_Order=order_mer_detail.ID_Order AND order_s.ID_Member = '$idmember'");
        if (mysqli_num_rows($result2)>0){
            while($row = mysqli_fetch_row($result2)) {
                if($row[7] == 'U')
                {
                    $query2 = mysqli_query($conn, "SELECT * FROM order_payment ORDER BY Payment_ID DESC LIMIT 1");
                    $ID_pre = '';
                    $ID_new = '';
                    while($row3=mysqli_fetch_array($query2))
                    {
                        echo $row3[0];
                        $ID_pre = $row3[0];
                        $ID_new = 'OP'. str_pad(intval(substr($ID_pre,2)) + 1, 5, '0', STR_PAD_LEFT);
                    }
                    $dt = new DateTime();
                    $pay_time = $dt->format('Y-m-d H:i:s');
                    $sqldb = "INSERT INTO order_payment (Payment_ID,ID_Order,ID_Member, payment_date_time, payment_amount) VALUES ('$ID_new','$row[0]', '$idmember', '$pay_time','$row[12]')";
                    mysqli_query($conn,$sqldb);

                    $query3 = mysqli_query($conn, "SELECT * FROM order_mer_detail WHERE ID_Order = '$row[0]'");
                    $IDmer = '';
                    $quantity = 0;
                    while($row4=mysqli_fetch_array($query3))
                    {
                        $IDmer = $row4[1];
                        $quantity = $row4[3];
                    }
                    $pay_time = $dt->format('Y-m-d');
                    $sqldb2 = "UPDATE merchandise_stock SET mer_qty = mer_qty-$quantity, update_date = '$pay_time' WHERE merchandise_stock.ID_Mer = '$IDmer'";
                    mysqli_query($conn,$sqldb2);

                    $sqldb3 = "UPDATE order_s SET Order_Status = 'P' WHERE order_s.ID_Order = '$row[0]' AND order_s.ID_Member = '$idmember'";
                    mysqli_query($conn,$sqldb3);


                }
            }
        } 

        header('location: mainpage.php');
    }
    
    
?>