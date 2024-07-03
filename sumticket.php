<?php
  session_start();
  include('server.php');


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
    <link rel="stylesheet" href="css/style.css">
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
        <h2 class="wow fadeInDown no-margin" data-wow-duration="1s" data-wow-delay="0.6s"><strong>Ticket</strong></h2>
        <div class="divider-center-small wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s"></div>
        <i class="section-subtitle wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s">รายละเอียดบัตรที่จอง</i>
    </div>
    
    <?php
      //who booked
      $query = "SELECT ID_Member FROM member WHERE member.userID = '".$_SESSION['username']."'";
      $result = mysqli_query($conn,$query);
      $mid = "";
      while($row=mysqli_fetch_array($result))
      {
        $mid = mysqli_real_escape_string($conn, $row[0]);        
      }

      //name user
      $query = "SELECT fname,lname FROM member WHERE member.userID = '".$_SESSION['username']."'";
      $result = mysqli_query($conn,$query);
      $name = "";
      while($row=mysqli_fetch_array($result))
      {
        $name = mysqli_real_escape_string($conn, $row[0]." ".$row[1]);        
      }

      //tel
      $query = "SELECT PhoneNumber FROM `member_phone` WHERE ID_Member = '$mid';";
      $result = mysqli_query($conn,$query);
      $tel = array();
      while($row=mysqli_fetch_array($result))
      {
        $tel[] = mysqli_real_escape_string($conn, $row[0]);        
      }

      //total
      $query = 
      "SELECT SUM(seat_reserve.Seat_price) FROM ticket 
      INNER JOIN seat_reserve
      ON ticket.ID_Seat = seat_reserve.ID_Seat
      WHERE ID_Member = '$mid' AND Ticket_Status = 'N';";
      $result = mysqli_query($conn,$query);
      $total = "";
      while($row=mysqli_fetch_array($result))
      {
        $total = mysqli_real_escape_string($conn, $row[0]);        
      }

      $_SESSION['ticktotal'] = $total;

      //ticket_qty
      $query = 
      "SELECT count(seat_reserve.ID_Seat) FROM ticket 
      INNER JOIN seat_reserve
      ON ticket.ID_Seat = seat_reserve.ID_Seat
      WHERE ID_Member = '$mid' AND Ticket_Status = 'N';";
      $result = mysqli_query($conn,$query);
      $qty = ""; 
      while($row=mysqli_fetch_array($result))
      {
        $qty = mysqli_real_escape_string($conn, $row[0]);        
      }

      //ticket attri
      $query = "SELECT ID_Ticket,ID_Event,ID_Seat FROM `ticket` WHERE ID_Member = '$mid' AND Ticket_Status = 'N';";
      $result = mysqli_query($conn,$query);
      $idticket = array();
      $idev = array();
      while($row=mysqli_fetch_array($result))
      {
        $idticket[] = $row[0];
        $idticket[$row[0]][] = $row[2];
        $idev[] = mysqli_real_escape_string($conn, $row[1]);
      }


    ?>

    <!-- Start Tracking -->
    <section id="tracking" class="p-top-80 p-bottom-80">
        <div class="container">

            <!-- Tracking-filter -->
            <ul class="tracking">
                <p id="name">ชื่อผู้สั่งซื้อ (Name) : <?php echo $name;?></p>
                <p id="tick_total">ยอดรวม (Total) : <?php echo $total;?></p>
            </ul><br><br>

            <div class="tracking-bg">
            <table style="width:100%; font-size: 15px;" align="center">
                <tr>
                    <th>Ticket Details</th>                    
                </tr>
            </table>
            <hr>
            <table style="width:100%; font-size: 15px;" align="center">
                <?php
                    $tell_ticket = "";
                  for((int)$i = 1; $i<=$qty;$i++)
                  {
                    $ev = $idev[$i-1];
                    $query = "SELECT event_name,event_date,show_time_start FROM `event_s` WHERE ID_Event = '$ev';";
                    $result = mysqli_query($conn,$query);
                    $txt = "";
                    while($row=mysqli_fetch_array($result))
                    {
                        $txt .= "<tr><td>Ticket ".$idticket[$i-1]." : ".$row[0]." วันที่ ".DateThai($row[1])." เริ่ม ".TimeThai($row[2])."</td></tr>";
                    }                    
                    echo $txt;
                  }
                ?>
                
            </table>
            
        </div><br><br>         
           
    </section>
       

    <!-- Start Service -->
    <section id="shows" class="p-top-80 p-bottom-80">
        <div class="container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <!-- Section Title -->
                    <div class="section-title text-center m-bottom-40">
                        <h2 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.6s"><strong style="font-family:prompt;">ระบบชำระเงิน</strong></h2>
                        <div class="divider-center-small wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s"></div>
                    </div>
                </div> <!-- /.col -->
            </div>  <!-- /.row -->


            <div class="row">
                <form action="payment_db.php" method="post" data-toggle="modal" id="payment_form" >
                <!-- Service Item 1 -->                  
                <div class="col-md-16 col-sm-16">              
                    <div class="service wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s">               
                        <h4 style="font-family:prompt; text-align: center;"><strong>จ่ายเงินด้วยบัตร</strong></h4>
                        <br>
                        <hr>
                        <br>
                        <div class="service-text">
                            <p>หมายเลขบัตร : <input type="text" name ="creditID" id="creditID" style="width:35%; height:40px;" required>
                            &nbspวันหมดอายุ : <input type="month" name="exp" id="exp" style="width:20%; height:40px;" required>
                            &nbspรหัสบัตร : <input type="text" name="creditpass" id="creditpass" style="width:20%; height:40px;" required></p>
                            <br>
                        </div>
                        <br>
                        <div class="service-text">                 
                        
                        <center>
                        <input type="button" onclick="document.location='mainpage.php'" id="back" value="ย้อนกลับ" name="tickback" style="width: 100px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">&emsp;&emsp;
                
                        <input type="submit" id="acceptproduct" value="ยอมรับ" name="actp1"                  
                        style="width: 100px; background-color: #4c8a77; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;" >
                        <?php //data-toggle="modal" data-target="#exampleModal" ?>
                        </center>
                        </form>
                        
                            
                            
                        </div>
                    </div>                   
                </div> <!-- /.col -->           

                

            </div> <!-- /.row -->

        </div> <!-- /.container -->
    </section>
    <!-- End Service -->

                        
        <!-- Modal -->
        <form action="payment_db.php" method="post">
        <div class="modal" id="exampleModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Prompt', sans-serif;">ยืนยัน</h5>
                    </div>
                    <div class="modal-body">
                            กดเพื่อยืนยันการตรวจสอบและยอมรับเพื่อโอน
                    </div>
                    <div class="modal-footer">
                        <button type="button" style="width: 50px; background-color: #666666; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                        data-dismiss="modal">ปิด</button>
                        
                        <button type="submit"  style="width: 90px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                        data-dismiss="modal" data-toggle="modal" data-target="#exampleModal2" data-scroll href="#" name="tickpay">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Prompt', sans-serif;">ยืนยัน</h5>
                    </div>
                    <div class="modal-body">
                            ระบบทำการตัดเงินจากบัตรเสร็จสิ้น
                    </div>
                    <div class="modal-footer">
                        <button type="submit" style="width: 50px; background-color: #666666; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                        data-dismiss="modal" onclick="document.location='mainpage.php'" >ปิด</button>                        
                    </div>
                </div>
            </div>
        </div>
       

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
    <script src="js/custom.js">
        
    </script>
    
  </body>
</html>
<script>
    //$('#exampleModal').modal('show');  
    $('#payment_form').on('submit', function(e) 
    {
    
        e.preventDefault(); //stop submit
        //show modal
        $('#exampleModal').modal('show');
    });


                       
                        
</script>