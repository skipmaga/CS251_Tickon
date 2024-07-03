<?php 
    session_start();
    include('server.php');

    $user = $_SESSION['username'];

    $findID = mysqli_query($conn,"SELECT * FROM member WHERE userID = '$user'");
    $idmember = '';
    $membername = '';
    if(mysqli_num_rows($findID)>0)
    {
        while($row = mysqli_fetch_row($findID)) {
            $idmember = $row[0];
            $membername = $row[2] . " " . $row[3] . " " . $row[4];
        }
    }

     //what is date today
  date_default_timezone_set('Asia/Bangkok');
  $dt = new DateTime();
  $order_time = $dt->format('Y-m-d H:i:s');


  function DateThai($strDate)
    {
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("",",มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "$strDay $strMonthThai $strYear";
    }

function TimeThai($strTime)
    {
        $strHour= date("H",strtotime($strTime));
        $strMinute= date("i",strtotime($strTime));
        return "$strHour:$strMinute น."; 
    }

    $_SESSION['history'] = '';
    $result = mysqli_query($conn,"SELECT * FROM order_payment INNER JOIN order_mer_detail ON order_payment.ID_Order=order_mer_detail.ID_Order AND order_payment.ID_Member = '$idmember'");
    if (mysqli_num_rows($result)>0){
        $_SESSION['history'] .= "<p class=\"wow fadeInDown \" ><strong>รายการซื้อสินค้า</strong></p>";
        while($row = mysqli_fetch_row($result)) {
            $result2 = mysqli_query($conn,"SELECT * FROM order_mer_detail INNER JOIN merchandise WHERE order_mer_detail.ID_Mer=merchandise.ID_Mer AND order_mer_detail.ID_Mer = '$row[6]' AND order_mer_detail.ID_Order = '$row[5]'");
            if(mysqli_num_rows($result2)>0)
            {
                while($row2 = mysqli_fetch_row($result2))
                {
                    $_SESSION['history'] .= '<p>&emsp;&emsp;หมายเลขคำสั่งซื้อ (Order No.) : ' . $row2[0] . '</p>';
                    $_SESSION['history'] .= '<p>ชื่อผู้สั่งซื้อ (Name) : ' . $membername . '</p>';
                    $_SESSION['history'] .= '<p>ชื่อสินค้า : '. $row2[7] . '</p>';
                    $_SESSION['history'] .= '<p>ราคาต่อชิ้น : '. strval($row2[2]) . ' บาท</p>';
                    $_SESSION['history'] .= '<p>จำนวนที่ต้องการซื้อ : '. strval($row2[3]) . ' ชิ้น</p>';
                    $_SESSION['history'] .= '<p>ราคารวมของสินค้าชิ้นนี้ : '. strval($row2[4]) . ' บาท</p><br>';
                    $totalPrice += $row2[4];
                }
            }
        }
        $_SESSION['history'] .= '<p>&emsp;&emsp;&emsp;&emsp;ราคารวมทั้งหมดของการซื้อสินค้า : ' . strval($totalPrice) . ' บาท</p>';
    } 
    else
    {
        $_SESSION['history'] .= '<p>ไม่พบรายการสั่งซื้อสินค้า</p>';
    }

   //ticket history

   $_SESSION['Thistory'] = "";
    
   $query =  mysqli_query($conn,"SELECT ID_Ticket,payment_date_time FROM `ticket_payment` WHERE ID_Member = '$idmember';");
   if (mysqli_num_rows($query)>0)
   {
       $_SESSION['Thistory'] .= "<p class=\"wow fadeInDown \" ><strong>รายการจองตั๋ว</strong></p>";
       $bdate = array();
       $temp = "";
       while($row = mysqli_fetch_row($query))
       {
           $temp = date_create($row[1]);
           $bdate[$temp->format('Y-m-d')][] =  $row[0];
                   
       }
       print_r($bdate);
       echo '<br>';
       
       //unique date
       $datarr = array();
       foreach($bdate as $row => $col)
       {            
           $datarr[] = $row;
           echo $row;
       }
       $datarr = array_unique($datarr);
       //print_r($datarr);

       //find ticket that day
       $tick = array();
        foreach($datarr as $row)
        {
            $tick[] = $bdate[$row][0];
            $_SESSION['Thistory'] .= "<hr><p>วันที่จอง : $row</p><br>";
       

       foreach($tick as $t)
       {
           //group by date            
           $result = mysqli_query($conn,"SELECT ID_Event,ID_Seat FROM `ticket` WHERE CCstatus = 'N' AND ID_Member = '$idmember' AND ID_Ticket = '$t';");
           while($row = mysqli_fetch_row($result))
           {
               $query = mysqli_query($conn,"SELECT event_name,event_date,show_time_start FROM `event_s` WHERE ID_Event = '$row[0]';");
               while($row2 = mysqli_fetch_row($query))
               {
                   $_SESSION['Thistory'] .= '<p>&emsp;&emsp;เลขตั๋ว (Ticket No.) : ' . $row[0] . '</p>';
                   $_SESSION['Thistory'] .= '<p>ชื่อผู้สั่งซื้อ (Name) : ' . $membername . '</p>';
                   $_SESSION['Thistory'] .= '<p>Title : '. $row2[0] . '</p>';
                   $_SESSION['Thistory'] .= '<p>วันที่ : '. DateThai($row2[1]) . '</p>';
                   $_SESSION['Thistory'] .= '<p>เริ่ม : '. TimeThai($row2[2]) . '</p>';
                                           
               }
                           
               $query = mysqli_query($conn,"SELECT Seat_number,Seat_zone,Seat_price FROM `seat_reserve` WHERE ID_Seat = '$row[1]';");
               while($row3 = mysqli_fetch_row($query))
               {
                   if($row3[1]!= null)
                   {
                       $_SESSION['Thistory'] .= '<p>ที่นั่ง : ' . $row3[1].$row3[0] . '</p>';
                       $_SESSION['Thistory'] .= '<p>ราคา : ' . $row3[2] . ' บาท </p>';
                   }
                   else
                   {                        
                       $_SESSION['Thistory'] .= '<p>ราคา : ' . $row3[2] . ' บาท </p>';
                   }
               
               }

           }                        
       }
    }
        //total
        $query = 
        "SELECT SUM(seat_reserve.Seat_price) FROM ticket 
        INNER JOIN seat_reserve
        ON ticket.ID_Seat = seat_reserve.ID_Seat
        WHERE ID_Member = '$idmember' AND Ticket_Status = 'P';";
        $result = mysqli_query($conn,$query);
        $total = "";
        while($row=mysqli_fetch_array($result))
        {
            $total = mysqli_real_escape_string($conn, $row[0]);        
        }

        $_SESSION['Thistory'] .= '<br><br><p>&emsp;&emsp;&emsp;&emsp;จำนวนเงินที่ซื้อบัตรทั้งหมด : ' . $total . ' บาท </p>';
   }
   else
    {
        $_SESSION['Thistory'] .= '<p>ไม่พบรายการสั่งจองบัตร</p>';
    }


    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>TICKON</title>

    <!-- login -->
    <meta charset="UTF-8">

    <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
    <link rel="stylesheet" href="style.css">
    <!-- Favicons -->
    <link rel="shortcut icon" href="img/logotickon.png" sizes="80x40">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet" />
    
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Prompt">

    <!-- Icon -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css'>
    
    <!-- Bootstrap CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- CSS Files For Plugin -->
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="css/magnific-popup.css" rel="stylesheet" />
    <link href="css/YTPlayer.css" rel="stylesheet" />
    <link href="inc/owlcarousel/css/owl.carousel.min.css" rel="stylesheet" />
    <link href="inc/owlcarousel/css/owl.theme.default.min.css" rel="stylesheet" />
    <link href="inc/revolution/css/settings.css" rel="stylesheet" />
    <link href="inc/revolution/css/layers.css" rel="stylesheet" />
    <link href="inc/revolution/css/navigation.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link href="css/style2.css" rel="stylesheet">
    
  </head>
  <body class="homepage_slider" data-spy="scroll" data-target=".navbar-fixed-top" data-offset="100">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <style>
        body {
            font-family:prompt;
        }
    </style>
    
    <!-- Preloader -->
    <div id="preloader">
        <div id="spinner"></div>
    </div>
    <!-- End Preloader-->

    
    <!-- Start Navigation -->
    <header class="nav-solid" id="home">
        <nav class="navbar navbar-fixed-top">
            <header class="header">

                <div class="icons">
                    <div class="nav-icon">
                        
                    <img class="icon-bar"style = "width : 75px; height :40px; margin-bottom: 5px;" src="img/logotickon.png">
                        
                    </div>
                    <p style="font-family: prompt; text-align: right; color: whitesmoke; font-size: 12px; padding-top: 9px;">Copyright © 2022 TICKON. All rights reserved.</p>
                </div>
                
               </header>
            <div class="navigation">
                <div class="container-fluid">
                    <div class="row">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                                <span class="sr-only">TICKON</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>

                           
                        </div> <!-- end navbar-header -->

                       <div class="col-md-8 col-xs-12 nav-wrap">
                            <div class="collapse navbar-collapse" id="navbar-collapse">

                                <ul class="nav navbar-nav navbar-right">
                                    <li><a href="mainpage.php"><i class='bx bx-home-alt' style="font-size:14px;">&nbsp</i>HOME</a></li>
                                </ul>
                            </div>
                        </div> <!-- /.col -->

                    </div> <!-- /.row -->
                </div> <!--/.container -->
            </div> <!-- /.navigation-overlay -->
        </nav> <!-- /.navbar -->

    </header>
    <!-- End Navigation -->

    
    <!-- Section Title Tracking -->
    <div class="section-title-bg text-center">
        <h2 class="wow fadeInDown no-margin" data-wow-duration="1s" data-wow-delay="0.6s"><strong>HISTORY</strong></h2>
        <div class="divider-center-small wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s"></div>
        <i class="section-subtitle wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s">ประวัติการสั่งซื้อ</i>
    </div>


    <!-- Start Tracking -->
    <section id="tracking" class="p-top-80 p-bottom-80">
        <div class="container">
        <ul class="tracking">
                <?php if (isset($_SESSION['Thistory'])) : ?>
                    <p><?php echo $_SESSION['Thistory']; unset($_SESSION['Thistory']); ?> </p>
                <?php endif ?>
        </ul><br><hr><br>
            <!-- Tracking-filter -->
            <ul class="tracking">
                <?php if (isset($_SESSION['history'])) : ?>
                    <p><?php echo $_SESSION['history']; unset($_SESSION['history']); ?> </p>
                <?php endif ?>
            </ul><br><br>

            <!--<div class="tracking-bg">
            <table style="width:100%; font-size: 15px;" align="center">
                <tr>
                    <th>Tracking Number : XXXXXXXXTH</th>
                    <th style="text-align:right;">Delivery Method : Flash Express</th>
                </tr>
            </table>
            <hr>
            <table style="width:100%; font-size: 15px;" align="center">
                <tr>
                    <td>29 มีนาคม 2022 : 09.58 น. ผู้ส่งกำลังเตรียมพัสดุ</td>
                </tr>
                <tr>
                    <td>29 มีนาคม 2022 : 14.03 น. บริษัทขนส่งเข้ารับพัสดุเรียบร้อย</td>
                </tr>
                <tr>
                    <td>29 มีนาคม 2022 : 18.55 น. พัสดุถึงศูนย์คัดแยกสินค้า FWNWA - ยานนาวา (G-003)</td>
                </tr>
                <tr>
                    <td>29 มีนาคม 2022 : 14.03 น. พัสดุออกจากศูนย์คัดแยกสินค้า</td>
                </tr>
                <tr>
                    <td>29 มีนาคม 2022 : 22.43 น. จัดส่งพัสดุเรียบร้อย</td>
                </tr>
            </table>
            
            </div><br><br>
            <table style="background-color: white; width:70%; margin: 0px auto;">
                <tr>
                    <td style="text-align:left;"><br>ยืนยันผู้รับของเรียบร้อย</td>
                    <td style="text-align:right;">ลายเซ็นต์ผู้รับของ<div class="tk-img"><a href="img/signature.png" class="pf-style lightbox-image mfp-image">
                        <div class="pf-image">
                            <img src="img/signature.png" alt="" style="width: 100px;"></div></td></div>
                </tr>
            </table>
            <br>
            <br>
            <div class="tracking-button">
            <center>
                <input type="button" id="back" value="ย้อนกลับ" onclick="document.location='mainpage.html'" style="width: 100px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">&emsp;&emsp;
                <input type="button" id="acceptproduct" value="ยอมรับ" data-toggle="modal" data-target="#exampleModal" style="width: 100px; background-color: #4c8a77; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;  ">
            </center>
            </div>-->
    </section>
             
                        <!-- Modal -->
                        <!--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Prompt', sans-serif;">ยืนยัน</h5>
                                    </div>
                                    <div class="modal-body">
                                        กดเพื่อยืนยันการตรวจสอบและยอมรับสินค้า
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" style="width: 50px; background-color: #666666; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                                            data-dismiss="modal">ปิด</button>
                                        <button type="button"   style="width: 90px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                                        onclick="document.location='mainpage.html'">ยืนยัน</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>-->

    <!-- Start Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="social-icon pull-right">
                <a href="#"><i class="fa fa-facebook"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-pinterest"></i></a>
                <a href="#"><i class="fa fa-google"></i></a>
                <a href="#"><i class="fa fa-rss"></i></a>
                <a href="#"><i class="fa fa-globe"></i></a>
            </div>
            <!-- /social-icon -->
        </div>
        <!-- /container -->
    </footer>
    <!-- End Footer -->

    <!-- Back to top -->
    <a href="#" id="back-to-top" title="Back to top"><i class="fa fa-angle-up"></i></a>
    <!-- /Back to top -->

    
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>
    
    <!-- Bootstrap -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    
    <!-- Components Plugin -->
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/smooth-scroll.js"></script>
    <script src="js/jquery.appear.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/jquery.mb.YTPlayer.js"></script>
    <script src="js/retina.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="inc/owlcarousel/js/owl.carousel.min.js"></script>

    <!-- Contact Form -->
    <script src="js/contact.js"></script>
    
    <!-- Custom Plugin -->
    <script src="js/custom.js"></script>
    
  </body>
</html>