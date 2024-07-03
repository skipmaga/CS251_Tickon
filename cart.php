<?php 
    session_start();
    include('server.php');

    $user = $_SESSION['username'];

    $findID = mysqli_query($conn,"SELECT * FROM member WHERE userID = '$user'");
    $idmember = '';
    if(mysqli_num_rows($findID)>0)
    {
        while($row = mysqli_fetch_row($findID)) {
            $idmember = $row[0];
        }
    }

    $totalPrice = 0;
    $_SESSION['cart'] = '';
    $result = mysqli_query($conn,"SELECT * FROM order_s INNER JOIN order_mer_detail WHERE order_s.ID_Order=order_mer_detail.ID_Order AND order_s.ID_Member = '$idmember'");
    if (mysqli_num_rows($result)>0){
        
        while($row = mysqli_fetch_row($result)) {
            if($row[7] == 'U')
            {
                $result2 = mysqli_query($conn,"SELECT * FROM order_mer_detail INNER JOIN merchandise WHERE order_mer_detail.ID_Mer=merchandise.ID_Mer AND order_mer_detail.ID_Mer = '$row[9]' AND order_mer_detail.ID_Order = '$row[0]'");
                if(mysqli_num_rows($result2)>0)
                {
                    while($row2 = mysqli_fetch_row($result2))
                    {
                        $_SESSION['cart'] .= '<p>&emsp;&emsp;ชื่อสินค้า : '. $row2[7] . '</p>';
                        $_SESSION['cart'] .= '<p>ราคาต่อชิ้น : '. strval($row2[2]) . ' บาท</p>';
                        $_SESSION['cart'] .= '<p>จำนวนที่ต้องการซื้อ : '. strval($row2[3]) . ' ชิ้น</p>';
                        $_SESSION['cart'] .= '<p>ราคารวมของสินค้าชิ้นนี้ : '. strval($row2[4]) . ' บาท</p>
                        <br>';
                        $totalPrice += $row2[4];
                    }
                }
            }
        }
        $_SESSION['cart'] .= '<p>&emsp;&emsp;ราคารวมทั้งหมด : ' . strval($totalPrice) . ' บาท</p>';
    } 
    else
    {
        $_SESSION['cart'] .= '<p>ไม่พบรายการสั่งซื้อสินค้า</p>';
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
        <h2 class="wow fadeInDown no-margin" data-wow-duration="1s" data-wow-delay="0.6s"><strong>CART</strong></h2>
        <div class="divider-center-small wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s"></div>
        <i class="section-subtitle wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s">ตะกร้า</i>
    </div>

    


    <!-- Start Tracking -->
    <section id="tracking" class="p-top-80 p-bottom-80">
        <div class="container">


            <!-- Tracking-filter -->
            <ul class="tracking">
                <?php if (isset($_SESSION['cart'])) : ?>
                    <p><?php echo $_SESSION['cart']; unset($_SESSION['cart']); ?> </p>
                <?php endif ?>
            </ul><br><br>
            <!--<form action="mer_pay.php". method="post">-->
            <form method="post" action="" data-toggle="modal" id="myForm">
            <div class="tracking-bg">
                <h4 style="font-family:prompt; text-align: center;"><strong>จ่ายเงินด้วยบัตร</strong></h4>
                <br>
                <hr>
                <br>
                <div class="service-text">
                    <p>หมายเลขบัตร : <input type="text" style="width:25%; height:30px;" required id= "cardNum">
                    &nbspวันหมดอายุ : <input type="month" style="width:15%; height:30px;" required id= "expiredDate">
                    &nbspรหัสบัตร : <input type="text" style="width:15%; height:30px;" required id= "cardPass"></p>
                    <br>
                </div>
                <hr>
            
            </div><br><br>
            
            <div class="tracking-button">
            <center>
                <input type="button" id="back" value="ย้อนกลับ" onclick="document.location='mainpage.php'" style="width: 100px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">&emsp;&emsp;
                <!-- Button trigger modal   -->
                <form method="post" action="" data-toggle="modal" data-target="#exampleModal" id="myForm">
                    <input type="submit" id="acceptproduct" data-toggle="modal" value="จ่ายเงิน" style="width: 100px; background-color: #4c8a77; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;  ">
                </form>
            </center>
            </form>
        </div>
    </section>           

    <!-- Modal -->
    <form action="mer_pay.php". method="post">
    <div class="modal" id="exampleModal" tabindex="1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color:white;"> 
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Prompt', sans-serif;">ยืนยัน</h5>
                </div>
                <div class="modal-body">
                    กดเพื่อยืนยันการจ่ายเงิน
                </div>
                <div class="modal-footer">
                    <button type="button" style="width: 50px; background-color: #666666; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                    data-dismiss="modal" >ปิด</button>
                    <button type="button"  style="width: 90px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;"
                    data-dismiss="modal" data-toggle="modal" data-target="#exampleModal2" data-scroll href="#">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

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
                    <button type="submit" name="Pay"  style="width: 50px; background-color: #666666; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">
                        ปิด</button>
                </div>
            </div>
        </div>
    </div>
</form>
                         
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

    <script>
        $('#myForm').on('submit', function(e) {
  
        e.preventDefault(); //stop submit
        
        if ($("#cardNum").val() != null && $("#expiredDate").val() != null && $("#cardPass").val() != null ) {
        //Check if checkbox is checked then show modal
            //alert ("Open");
            /*var mdlPopup = document.getElementById("exampleModal"); ;
            if(mdlPopup)
            {
                mdlPopup.show();
            }*/
            $('#exampleModal').modal("show");
        }
        });
    </script>
    
  </body>
</html>