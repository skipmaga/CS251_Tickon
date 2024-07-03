<?php 
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
    
    session_start();
    include('server.php');

    $errors = array();
    $_SESSION['eventEach'] = "";
    $_SESSION['eventTitle'] = "";
    $_SESSION['eventPic'] = "";
    $query = "SELECT event_s.ID_Event, event_s.event_name, event_s.event_type,event_s.event_date,event_s.show_time_start,event_s.image_addr,location.loc_name FROM event_s INNER JOIN location ON event_s.ID_Location=location.ID_Location WHERE event_s.event_status = 'O' AND event_s.ID_Event='".$_GET['id']."'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_row($result)) {
            if($row[2] == "ET00001")        // Event type = Concert
            {
                $_SESSION['eventEach'] .= '<ul><h4>'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p>';
            }
            elseif ($row[2] == "ET00002")   //Event type = Product Show/Art
            {
                $_SESSION['eventEach'] .= '<ul><h4>'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : งานแสดงศิลปะ, คอนเสิร์ต</p>';
            }
            else                             //Event type = Event
            {
                $_SESSION['eventEach'] .= '<ul><h4>'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : อีเวนท์</p>';
            }
            $_SESSION['eventTitle'] = '<div class="postTitle"><h1>'.$row[1].'</h1><div class="divider-small"></div></div>';
            $_SESSION['eventPic'] = '<div class="postMedia"><img alt="" src="'.$row[5].'"></div>';
            
        }
        
    } 
    
    else{
        array_push($errors, "Can't find event");
        $_SESSION['error'] .= "Can't find event";
    }
    //what event
    $_SESSION['eventid'] = $_GET['id'];

    //which location?
    $query = "SELECT ID_Location FROM event_s WHERE event_s.ID_Event = '".$_GET['id']."'";
    $result = mysqli_query($conn,$query);
    $loc = "";
    while($row=mysqli_fetch_array($result))
    {
        $loc = mysqli_real_escape_string($conn, $row[0]);
        //echo $loc;
    }
    //echo '<br>';

    $query = "SELECT DISTINCT Seat_zone FROM `seat_reserve` WHERE ID_Location = '$loc'AND Seat_zone IS NOT NULL;";
    $result = mysqli_query($conn,$query);
    $temp = "";
    while($row=mysqli_fetch_array($result))
    {
        $temp = $row[0];
        //echo '<br>';
        //print_r($row);
    }
    //echo $temp;

    //location  with zone
    if(!empty($temp))
    {
            //what zone is there  
        $query = "SELECT DISTINCT Seat_zone ,seat_price,COUNT(seat_zone) FROM seat_reserve WHERE seat_reserve.ID_Location = '$loc' GROUP BY Seat_zone;";
        $result = mysqli_query($conn,$query);        
       
        $zone_price = array();
        $zone_qty = array();
        
        while($row=mysqli_fetch_array($result))
        {        
            $zone_qty[$row[0]] = $row[2];
            $zone_price[$row[0]] = $row[1];
            
            //print_r($row);
        }
        //print_r($zone_price);
        //print_r($zone_qty);

        //seat's status A 
        $query = "SELECT Seat_number,Seat_zone FROM seat_reserve WHERE seat_reserve.Seat_status = 'A' AND seat_reserve.ID_Location = '$loc';";
        $result = mysqli_query($conn,$query);
        $a_name = array();

        while($row=mysqli_fetch_array($result))
        {        
            $a_name[] = $row[1].$row[0];        
            //print_r($row);
        }
        //print_r($r_name);
    }
    else
    {
        //location without zone
        //max -> lastest seat
        $query = "SELECT COUNT(ID_Seat) FROM `seat_reserve` WHERE ID_Location = '$loc';";
        $result = mysqli_query($conn,$query);
        $maxseat = "";
        $lseat = 0;
        while($row=mysqli_fetch_array($result))
        {
            $maxseat = mysqli_real_escape_string($conn, $row[0]);
            //echo $maxseat;
        }
        //echo '<br>';
        $maxseat = (int)$maxseat;

        //left
        $query = "SELECT COUNT(ID_Seat) FROM `seat_reserve` WHERE ID_Location = '$loc' AND Seat_status = 'A'";
        $result = mysqli_query($conn,$query);
        $avileft = "";
        while($row=mysqli_fetch_array($result))
        {
            $avileft = mysqli_real_escape_string($conn, $row[0]);
            echo $avileft;
        }
        //echo '<br>';
        $avileft = (int)$avileft;

        //lastest ticket was booked
        $lseat = $maxseat - $avileft;
        //echo $lseat;

        //price
        $query = "SELECT Seat_price FROM `seat_reserve` WHERE ID_Location = '$loc' AND Seat_status = 'A'";
        $result = mysqli_query($conn,$query);
        $aviprice = "";
        while($row=mysqli_fetch_array($result))
        {
            $aviprice = mysqli_real_escape_string($conn, $row[0]);
        }
    }

    $query = "SELECT event_date FROM event_s WHERE event_s.ID_Event = '".$_GET['id']."'";
    $result = mysqli_query($conn,$query);
    $dday = "";
    while($row=mysqli_fetch_array($result))
    {
        $dday = DateThai(mysqli_real_escape_string($conn, $row[0]));
    }

    $query = "SELECT MAX(Seat_price) FROM seat_reserve WHERE ID_Location = '$loc'";
    $result = mysqli_query($conn,$query);
    $maxprice = mysqli_fetch_array($result)[0];                            
                            
    $query = "SELECT MIN(Seat_price) FROM seat_reserve WHERE ID_Location = '$loc'";
    $result = mysqli_query($conn,$query);
    $minprice = mysqli_fetch_array($result)[0];

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
    <link href="css/style.css" rel="stylesheet">
    
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

    
    <!--BLog single section-->
    <section id="blog-single" class="p-top-80 p-bottom-80">

        <!--Container-->
        <div class="container clearfix">
        <div class="row">

            <div class="col-sm-4 sidebar">
                
                <!-- Widget 2 -->
                <div class="widget">
                    <?php if (isset($_SESSION['eventEach'])) : ?>
                        <p><?php echo $_SESSION['eventEach']; unset($_SESSION['eventEach']); ?> </p>
                    <?php endif ?>
                    <!--<ul>
                        <h4>ROAD TO PARADISEFEST</h4>
                        <p>ราคา : 399 บาท</p>
                        <p>วันที่ : 1 พฤษภาคม 2022</p>
                        <p>ประตูเปิด : 12.00</p>
                        <p>สถานที่ : LIDO Connect Hall 3</p>
                        <p>Tag : event</p>
                    </ul> --> 
                </div> <!--End widget--> 

                <!--<div class="widget">
                    <ul>
                        <h4>Description</h4>
                        <p>🔥เผาหัว กันก่อนปะล่ะ !!!🔥</p>
                        <p>- KLUAYTHAI</p>
                        <p>- SUDDEN FACE DOWN</p>
                        <p>- LASTFIGHT FOR FINISH</p>
                        <p>- RITALINN</p>
                        <p>- KNO</p>
                        <p>- BIKINI</p>
                        <p>- LAME POET</p>
                        <p>- DEADKAT</p>
                        <p>- EMPTY GLASS MEANS NOTHING</p>
                        <p>- NAVER GETS OLD</p>
                        <p>- TRAGEDY OF MURDER </p>
                        <p>- UGOSLABIER</p>
                    </ul> 
                </div> End widget-->           
            
            </div> <!-- /.col -->
            

            <div class="col-sm-8">
                
                <!--Post Single-->
                <div class="postSingle">               
                    
                    <!--<div class="postMedia">                    
                        <img alt="" src="img/portfolio/LidoHall3.jpg">
                    </div>-->
                    <?php if (isset($_SESSION['eventPic'])) : ?>
                        <p><?php echo $_SESSION['eventPic']; unset($_SESSION['eventPic']); ?> </p>
                    <?php endif ?>
                    
                    <?php if (isset($_SESSION['eventTitle'])) : ?>
                        <p><?php echo $_SESSION['eventTitle']; unset($_SESSION['eventTitle']); ?> </p>
                    <?php endif ?>
                     <!--Post title-->  
                    <form action="seat_ticket_db.php" method="post">
                    <!--<p>จำนวนบัตรที่ต้องการซื้อ : <input name="ticket_qty" type="number" min="1" max="5" required></p></br>
                        <p>รอบการแสดง : <select name="date" required>
                                        <option>1 พฤษภาคม 2022</option>
                                    </select></p></br>
                        <p>ที่นั่ง : <select name="seat" required>
                                        <option>Hall 3</option>
                                    </select></p></br>
                        <p>ราคา : 399 บาท</p>
                        <button onclick="document.location='mainpage.html'" style="width: 100px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">ย้อนกลับ</button>
                        <button type="submit" name="firmseat" onclick="document.location='pay.html'" style="width: 100px; background-color: #4c8a77; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">ดำเนินการต่อ</button>
                    -->

                        <!--has zone seat -->
                        <?php if(!empty($temp)) : ?>
                            <p>ที่นั่ง : <select name="seat" required>                                        
                                        <?php foreach($a_name as $zonen)
                                            {   
                                                $_SESSION['checkb'] .= '<option >'.$zonen.'</option>';                                                                                               
                                            }
                                        ?>
                                        <?php echo  $_SESSION['checkb'];unset($_SESSION['checkb']);?>                                        
                                    </select></p></br>
                            <p>รอบการแสดง : <?php echo $dday;?></p></br>
                            <p>ราคาใบละ : <?php echo $minprice.' - '.$maxprice;?> &nbsp;บาท</p>
                            
                        <?php endif ?>

                        <!--without zone-->
                        <?php if(empty($temp)):?>
                            <p>จำนวนบัตรที่ต้องการซื้อ : <input name="ticket_qty" type="number" min="1" max=<?php if($avileft<5){echo $avileft;}else{echo "5";}?> required></p></br>
                            <p>รอบการแสดง : <?php echo $dday;?></p></br>
                            <p>ราคา : <?php if($aviprice == ""){echo 'ฟรี';}else{echo $aviprice.'&nbsp;บาท';}?> </p>
                            
                        
                        <?php endif?>
                           
                        <button type="button" onclick="document.location='mainpage.php'" style="width: 100px; background-color: #FB3640; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">ย้อนกลับ</button>
                        <button type="submit" name="firmseat" onclick="document.location='sumticket.php'" style="width: 100px; background-color: #4c8a77; border-radius: 40px; border: 0px; color: white; font-family: prompt; position: center;">ดำเนินการต่อ</button>

                        <?php //onclick="document.location='pay.php'" action="pay.php" onclick="document.location="seat_ticket_db.php?id='.$_GET['id'].'"?>
                 </form>
   

                    </div><!--End reply form-->       
                </div><!--End respond-->       

            </div> <!-- /.col -->
        
        </div> <!-- /.row -->
        </div> <!-- /.container -->

    </section><!--End blog single section-->

    
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
    
    <!-- Custom Plugin -->
    <script src="js/custom.js"></script>
    
  </body>
</html>