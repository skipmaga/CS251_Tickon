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

    date_default_timezone_set('Asia/Bangkok');
    $uname = $_SESSION['username'];
    if(isset($_SESSION['username']) && !isset($_GET['logout'])){
       $dt = new DateTime();
       $timestamp = $dt->format('Y-m-d H:i:s');
       $query1 = mysqli_query($conn,"SELECT ID_Member FROM member WHERE member.userID = '$uname'");
       while($row=mysqli_fetch_array($query1))
        {
            $mid = mysqli_real_escape_string($conn, $row[0]);
        }
       $query2 = mysqli_query($conn,"INSERT INTO `log`(`ID_Member`, `userID`, `Type_in_out`, `time_stamp`) VALUES ('$mid','$uname','I','$timestamp')");
    }
    if (isset($_GET['logout'])) {
        $dt = new DateTime();
        $timestamp = $dt->format('Y-m-d H:i:s');
        $query1 = mysqli_query($conn,"SELECT ID_Member FROM member WHERE member.userID = '$uname'");
        while($row=mysqli_fetch_array($query1))
        {
            $mid = mysqli_real_escape_string($conn, $row[0]);
        }
        $query2 = mysqli_query($conn,"INSERT INTO `log`(`ID_Member`, `userID`, `Type_in_out`, `time_stamp`) VALUES ('$mid','$uname','O','$timestamp')");
        session_destroy();
        unset($_SESSION['username']);
        header('location: mainpage.php');
    }

    $errors = array();
    $_SESSION['event'] = "";
    $query = "SELECT event_s.ID_Event, event_s.event_name, event_s.event_type,event_s.event_date,event_s.show_time_start,event_s.image_addr,location.loc_name FROM event_s INNER JOIN location ON event_s.ID_Location=location.ID_Location WHERE event_s.event_status = 'O'";
    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_row($result)) {
            if($row[2] == "ET00001")        // Event type = Concert
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['event'] .= '<div class="pf-item concert"><a onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="width:auto; cursor: pointer;" class="pf-style" ><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content"><div class="pf-info white-color" style="font-family:prompt;">';
                    $_SESSION['event'] .= '<h4 class="pf-title">'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p></div></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['event'] .= '<div class="pf-item concert"><a href="event.php?id='.$row[0].'" class="pf-style"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content"><div class="pf-info white-color" style="font-family:prompt;">';
                    $_SESSION['event'] .= '<h4 class="pf-title">'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p></div></div></div></div></div></a></div>';
                }
                
                //<a onclick="document.getElementById('id01').style.display='block'" style="width:auto; cursor: pointer;"><i class="fi fi-rr-portrait">&nbsp</i>Login</a>
            }
            elseif ($row[2] == "ET00002")   //Event type = Product Show/Art
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['event'] .= '<div class="pf-item art"><a onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="width:auto; cursor: pointer;" class="pf-style" ><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content"><div class="pf-info white-color" style="font-family:prompt;">';
                    $_SESSION['event'] .= '<h4 class="pf-title">'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p></div></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['event'] .= '<div class="pf-item art"><a href="event.php?id='.$row[0].'" class="pf-style"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content"><div class="pf-info white-color" style="font-family:prompt;">';
                    $_SESSION['event'] .= '<h4 class="pf-title">'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p></div></div></div></div></div></a></div>';
                }
            }
            else                             //Event type = Event
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['event'] .= '<div class="pf-item event"><a onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="width:auto; cursor: pointer;" class="pf-style" ><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content"><div class="pf-info white-color" style="font-family:prompt;">';
                    $_SESSION['event'] .= '<h4 class="pf-title">'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p></div></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['event'] .= '<div class="pf-item event"><a href="event.php?id='.$row[0].'" class="pf-style"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content"><div class="pf-info white-color" style="font-family:prompt;">';
                    $_SESSION['event'] .= '<h4 class="pf-title">'.$row[1].'</h4><p>วันที่ : '.DateThai($row[3]).'</p><p>ประตูเปิด : '.TimeThai($row[4]).'</p><p>สถานที่ : '.$row[6].'</p><p>Tag : คอนเสิร์ต</p></div></div></div></div></div></a></div>';
                }
            }
        }
        
    } 
    
    else{
        array_push($errors, "Can't find event");
        $_SESSION['error'] += "Can't find event";
    }

    $_SESSION['merchandise'] = "";
    $query2 = "SELECT * FROM (merchandise INNER JOIN merchandise_type ON merchandise.mer_type=merchandise_type.ID_mer_type) INNER JOIN merchandise_stock ON merchandise.ID_Mer=merchandise_stock.ID_Mer WHERE merchandise.ID_Mer = 'MER00003' OR merchandise.ID_Mer = 'MER00004' OR merchandise.ID_Mer = 'MER00005'";
    $result2 = mysqli_query($conn,$query2);
    if (mysqli_num_rows($result2)>0){
        while($row = mysqli_fetch_row($result2)) {
            $url = "'merchandise.php?id=".$row[0]."'";
            if($row[3] == "MT00001")        // Merchandise type = Accessory
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item accessory branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item accessory branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
            }
            elseif ($row[3] == "MT00002")   // Merchandise type = clothes
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item clothes branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item clothes branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
            }
            elseif ($row[3] == "MT00003")   // Merchandise type = Album
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item album branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item album branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                //$_SESSION['merchandise'] .= '<div class="pf-item album branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                //$_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
            }
            elseif ($row[3] == "MT00004")   // Merchandise type = Light stick
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item lightsticks branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item lightsticks branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                //$_SESSION['merchandise'] .= '<div class="pf-item lightsticks branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                //$_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
            }
            else                             // Event type = Poster
            {
                if (!isset($_SESSION['username']))
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item poster branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.getElementById('.'\'id01\''.').style.display='.'\'block\''.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                else
                {
                    $_SESSION['merchandise'] .= '<div class="pf-item poster branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                    $_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
                }
                //$_SESSION['merchandise'] .= '<div class="pf-item poster branding"><a href="'.$row[5].'" class="pf-style lightbox-image mfp-image"><div class="pf-image"><img src="'.$row[5].'" alt=""><div class="overlay"><div class="overlay-caption"><div class="overlay-content" style="font-family:prompt;"><div class="pf-info white-color">';
                //$_SESSION['merchandise'] .= '<h4 class="pf-title" style="font-family:prompt;">'.$row[2].'</h4><p>ประเภท : '.$row[7].'</p><p>ราคา : '.$row[4].' บาท</p><p>สินค้าคงเหลือ : '.$row[9].' ชิ้น</p></div><button class="merchandise-button" onclick="document.location='.$url.'" style="font-family:prompt; border: none; border-radius: 5px; width: 40%;">คลิกเพื่อดูรายละเอียด</button></div></div></div></div></a></div>';
            }
        }
        
    } 


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>TICKON</title>

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
      ::placeholder{
        color: white;
      }
    </style>
    
    
    <!-- Preloader -->
    <div id="preloader">
        <div id="spinner"></div>
    </div>
    <!-- End Preloader-->

    
    <!-- Start Navigation -->
    <header class="nav-solid" id="home" >
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
                                    <li><a data-scroll href="#home"><i class='bx bx-home-alt' style="font-size:14px;">&nbsp</i>HOME</a></li>
                                    <li><a data-scroll href="#shows"><i class="fi fi-rs-ticket">&nbsp</i>SHOWS</a></li>
                                    <li><a data-scroll href="#official"><i class='bx bx-cart-alt' style="font-size:15px;">&nbsp</i>OFFICIAL PRODUCT</a></li>
                                    
                                    <!--<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    CANCEl <i class='bx bx-caret-down' ></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                    <li><a class="dropdown-item" href="cancel_concert.php">CONCERT TICKET</a></li>
                                    <li><a class="dropdown-item" href="cancel_official.php">OFFICIAL PRODUCT</a></li></ul>-->
                                    <li><a data-scroll href="#contact"><i class='bx bx-phone-call' style="font-size:15px;">&nbsp</i>CONTACT</a></li>
                                        <?php if(!isset($_SESSION['username'])) : ?>  
                                         <li><a onclick="document.getElementById('id01').style.display='block'" style="width:auto; cursor: pointer;"><i class='bx bx-user-circle' style="font-size:14px;">&nbsp</i>Login</a></li>
                                         <?php endif ?>
                                         <?php if(isset($_SESSION['username'])) : ?>  
                                        <!-- edit here change link when login success -->
                                        <li><a href="history.php"><i class='bx bx-receipt' style="font-size:14px;">&nbsp</i>HISTORY</a></li>
                                         <li><a href="cart.php" style="width:auto; cursor: pointer;"><i class='bx bx-basket' style="font-size:14px;">&nbsp</i>Cart</a></li>
                                         <li><a href="sumticket.php" style="width:auto; cursor: pointer;"><i class='bx bx-purchase-tag-alt' style="font-size:14px;">&nbsp</i>TicketPay</a></li>
                                         <li><a href="mainpage.php?logout='1'" style="width:auto; cursor: pointer;"><i class='bx bxs-door-open' style="font-size:14px;">&nbsp</i>logout</a></li>
                                         <?php endif ?>
                                </ul>
                            </div>
                        </div> <!-- /.col -->

                    </div> <!-- /.row -->
                </div> <!--/.container -->
            </div> <!-- /.navigation-overlay -->
        </nav> <!-- /.navbar -->

    </header>
    <!-- End Navigation -->

    
<!-- Start Intro -->
<section id="slider">
        <div class="rev_slider_wrapper fullscreen-container" id="rev_slider_280_1_wrapper" style="background-color:#fff;padding:0px;height:1080px; margin-top:0px;">
          <!-- START REVOLUTION SLIDER 5.1.4 fullscreen mode -->
          <div class="rev_slider fullscreenbanner" id="rev_slider_nagency" style="display:none;">
              <ul style="display:none;">

                <!-- slider Item 1 -->
                <li data-index="rs-1" data-transition="fadetotopfadefrombottom" data-slotamount="default" data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500" data-rotate="0"  data-saveperformance="off">
                  <img src="img/slider/izone.jpg" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg" data-no-retina>

                  <!-- LAYER NR. 1 -->
                  <div class="tp-caption NotGeneric-Title tp-resizeme rs-parallaxlevel-3" 
                    id="slide-1-layer-1" 
                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['-160','-170','-170','-120']" 
                    data-fontsize="['70','60','60','36']"
                    data-lineheight="['70','60','60','50']"
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"              
                    data-transform_in="y:[100%];z:0;rZ:-35deg;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" 
                    data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" 
                    data-start="1000" 
                    data-splitin="chars" 
                    data-splitout="none" 
                    data-responsive_offset="on" 
                    data-elementdelay="0.05"                    
                    style="z-index: 5; white-space: nowrap; color: white; font-family: prompt;">Welcome to TICKON
                  </div>

                  <!-- LAYER NR. 2 -->
                  <div class="tp-caption NotGeneric-SubTitle tp-resizeme rs-parallaxlevel-4" 
                    id="slide-1-layer-2" 
                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['-80','-90','-90','-60']"
                    data-fontsize="['28','24','24','20']"
                    data-lineheight="['28','24','36','30']"
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"               
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" 
                    data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" 
                    data-start="1500" 
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"                     
                    style="z-index: 6; white-space: nowrap; color: white; font-family: prompt;">ยินดีต้อนรับเข้าสู่ TICKON
                  </div>

                  <!-- LAYER NR. 3 -->
                  <div class="tp-caption rs-parallaxlevel-5" 
                    id="slide-1-layer-3" 
                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['15','5','5','10']" 
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"
                    data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;"
                    data-style_hover="c:rgba(255, 255, 255, 1.00);bc:rgba(255, 255, 255, 1.00);cursor:pointer;"
                    data-transform_in="y:50px;opacity:0;s:1500;e:Power4.easeInOut;" 
                    data-transform_out="y:[175%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_out="x:inherit;y:inherit;" 
                    data-start="2000" 
                    data-splitin="none" 
                    data-splitout="none" 
                    data-actions='[{"event":"click","action":"jumptoslide","slide":"next","delay":""}]'
                    data-responsive_offset="on" 
                    data-responsive="off">
                  </div>           
                
                </li>

                <!-- slider Item 2 -->
                <li data-index="rs-3" data-transition="slideremoveright" data-slotamount="default" data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500" data-rotate="0"  data-saveperformance="off">
                  <img src="img/slider/justinbieber.jpg"  alt=""  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg" data-no-retina>

                  <!-- LAYER NR. 1 -->
                  <div class="tp-caption NotGeneric-Title tp-resizeme white-color rs-parallaxlevel-3" 
                    id="slide-2-layer-1" 
                    data-x="['left','left','left','left']" data-hoffset="['0','50','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['-100','-100','-100','-85']" 
                    data-fontsize="['70','60','60','36']"
                    data-lineheight="['70','60','60','50']"
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"              
                    data-transform_in="y:[100%];z:0;rx:0deg;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" 
                    data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" 
                    data-start="1000" 
                    data-splitin="chars" 
                    data-splitout="none" 
                    data-responsive_offset="on" 
                    data-elementdelay="0.05"                    
                    style="z-index: 5; white-space: nowrap; color: white; font-family: prompt;">GET YOUR TICKET
                  </div>

                  <!-- LAYER NR. 2 -->
                  <div class="tp-caption tp-resizeme NotGeneric-Text white-color rs-parallaxlevel-4" 
                    id="slide-2-layer-2" 
                    data-x="['left','left','left','left']" data-hoffset="['0','50','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','-10']"
                    data-fontsize="['18','18','20','14']"
                    data-lineheight="['28','28','32','24']"
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"               
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" 
                    data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" 
                    data-start="1500" 
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"                   
                    style="z-index: 6; white-space: nowrap; color: white; font-family: prompt;">let's have fun with many shows.
                  </div>

                  <!-- LAYER NR. 3 -->
                  <div class="tp-caption rs-parallaxlevel-5" 
                    id="slide-2-layer-3" 
                    data-x="['left','left','left','left']" data-hoffset="['0','50','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['105','105','115','85']" 
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"
                    data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;"
                    data-style_hover="c:rgba(255, 255, 255, 1.00);bc:rgba(255, 255, 255, 1.00);cursor:pointer;"
                    data-transform_in="y:50px;opacity:0;s:1500;e:Power4.easeInOut;" 
                    data-transform_out="y:[175%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_out="x:inherit;y:inherit;" 
                    data-start="2000" 
                    data-splitin="none" 
                    data-splitout="none" 
                    data-actions='[{"event":"click","action":"jumptoslide","slide":"next","delay":""}]'
                    data-responsive_offset="on" 
                    data-responsive="off"                   
                    style=""><a data-scroll href='#shows' class='btn btn-main btn-black'>Select show</a>
                  </div>          
                
                </li>

                <!-- slider Item 3 -->
                <li data-index="rs-2" data-slotamount="default" data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500" data-rotate="0"  data-saveperformance="off">
                  <img alt="" class="rev-slidebg" data-bgparallax="3" data-bgposition="center center" data-duration="5000" data-ease="Linear.easeNone" data-kenburns="on" data-no-retina="" data-offsetend="0 0" data-offsetstart="0 0" data-rotateend="0" data-rotatestart="0" data-scaleend="100" data-scalestart="110" src="img/slider/blackpink.jpg">

                  <!-- LAYER NR. 1 -->
                  <div class="tp-caption NotGeneric-Title tp-resizeme white-color rs-parallaxlevel-3" 
                    id="slide-3-layer-1" 
                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['-75','-70','-70','-45']" 
                    data-fontsize="['70','60','60','36']"
                    data-lineheight="['70','60','60','50']"
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"              
                    data-transform_in="x:[105%];z:0;rX:45deg;rY:0deg;rZ:90deg;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_in="x:0px;y:0px;s:inherit;e:inherit;" 
                    data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" 
                    data-start="1000" 
                    data-splitin="chars" 
                    data-splitout="none" 
                    data-responsive_offset="on" 
                    data-elementdelay="0.05"                    
                    style="z-index: 5; white-space: nowrap;">100% Beautiful Product.
                  </div>

                  <!-- LAYER NR. 2 -->
                  <div class="tp-caption NotGeneric-SubTitle white-color tp-resizeme rs-parallaxlevel-2" 
                    id="slide-3-layer-2" 
                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']"
                    data-fontsize="['28','24','24','20']"
                    data-lineheight="['28','24','36','30']"
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"
                    data-transform_in="y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;s:2000;e:Power4.easeInOut;" 
                    data-transform_out="y:[100%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_in="x:0px;y:[100%];s:inherit;e:inherit;" 
                    data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" 
                    data-start="1500" 
                    data-splitin="none" 
                    data-splitout="none" 
                    data-responsive_offset="on"                    
                    style="z-index: 6; white-space: nowrap;">you can buy the merchandise here.
                  </div>

                  <!-- LAYER NR. 3 -->
                  <div class="tp-caption rs-parallaxlevel-5" 
                    id="slide-3-layer-3" 
                    data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" 
                    data-y="['middle','middle','middle','middle']" data-voffset="['90','90','100','75']" 
                    data-width="none"
                    data-height="none"
                    data-whitespace="nowrap"
                    data-transform_idle="o:1;"
                    data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:300;e:Power1.easeInOut;"
                    data-style_hover="c:rgba(255, 255, 255, 1.00);bc:rgba(255, 255, 255, 1.00);cursor:pointer;"
                    data-transform_in="y:50px;opacity:0;s:1500;e:Power4.easeInOut;" 
                    data-transform_out="y:[175%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" 
                    data-mask_out="x:inherit;y:inherit;" 
                    data-start="2000" 
                    data-splitin="none" 
                    data-splitout="none" 
                    data-actions='[{"event":"click","action":"jumptoslide","slide":"next","delay":""}]'
                    data-responsive_offset="on" 
                    data-responsive="off"                   
                    style=""><a data-scroll href='#official' class='btn btn-main btn-transparent-light'>Purchase Now</a>
                  </div>

                </li>

              </ul>
              <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
          </div>
      </div>
    </section>
    <!-- End Intro -->

    <!-- Login and register for member -->
    <div id="id01" class="modal">
        <span class="close">&times;</span>
          <form class="modal-content animate" action="login_db.php" method="post">
              <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
              <br>
              <br>
              <p style="font-family:prompt; font-size: 30px; text-align: center;">เข้าสู่ระบบ</p>

            <div class="login-container" style="text-align: center;">
              <input  id = "username" type="text" name="username"  placeholder="ชื่อผู้ใช้" style=" margin-bottom: 15px; background-color: #FDCA40; border: none; border-radius: 5px; font-family: prompt; padding: 5px;" required>
              <br>
              <input  id = "password" type="password" name="password" placeholder="รหัสผ่าน" style=" margin-bottom: 15px; background-color: #FDCA40; border: none; border-radius: 5px; font-family: prompt; padding: 5px;"  required>
              <br>     
              <br>                             
              <button type="submit" name="login_user" class="btn">เข้าสู่ระบบ</button>
              <br>
              <br>
              <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
              </label>
            </div>
            <hr>
            <div class="login-container">                                
              <p style="font-family:prompt; text-align: center;">หากท่านยังไม่ได้เป็นสมาชิก<br><a href="register_member.php" style="text-decoration: underline;">กรุณาสมัครสมาชิก</a></p>
            </div>
          </form>
        </div>

        <script>
        
        var modal = document.getElementById('id01');

        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        }
        let closeBtns = [...document.querySelectorAll(".close")];
          closeBtns.forEach(function (btn) {
            btn.onclick = function () {
              let modal = btn.closest(".modal");
              modal.style.display = "none";
          };
        });
        </script>        
    <!-- End login and register for member -->

    <!-- Section Title shows -->
    <div class="section-title-bg text-center">
        <h2 class="wow fadeInDown no-margin" data-wow-duration="1s" data-wow-delay="0.6s"><strong>SHOWS</strong></h2>
        <div class="divider-center-small wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s"></div>
        <i class="section-subtitle wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s" style="font-family:prompt;">เลือกการแสดงที่คุณต้องการได้ที่นี่</i>
    </div>


    <!-- Start shows -->
    <section id="shows" class="p-top-80 p-bottom-80">
        <div class="container">

            <!-- shows-filter -->
            <ul class="pf-filter pf-filter-gray text-center list-inline">
                <li><a href="#" data-filter="*" class="iso-active iso-button" style="font-family:prompt;">All</a></li>
                <li><a href="#" data-filter=".event" class="iso-button" style="font-family:prompt;">Event</a></li>
                <li><a href="#" data-filter=".concert" class="iso-button" style="font-family:prompt;">Concert</a></li>
                <li><a href="#" data-filter=".art" class="iso-button" style="font-family:prompt;">Art Expedition</a></li>
            </ul>          
            
            <!-- shows -->
            <div class="portfolio portfolio-isotope col-3 gutter">
                <?php if (isset($_SESSION['event'])) : ?>
                    <p><?php echo $_SESSION['event']; unset($_SESSION['event']); ?> </p>
                <?php endif ?>
                
            </div>
        </div> <!-- /.container -->
    </section>
    <!-- End shows -->

    <!-- Start official product -->
    <section id="official" class="light-bg p-top-80 p-bottom-80">
        <div class="container">
            <!-- Section Title -->
            <div class="section-title text-center m-bottom-30">
                <h2><strong>OFFICIAL PRODUCT</strong></h2>
                <div class="divider-center-small"></div>
                <i class="section-subtitle wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s" style="font-family:prompt;">สินค้ามากมายจากศิลปินที่คุณชื่นชอบ</i>
            </div>

       <!-- official product -->
            <div class="portfolio portfolio-isotope col-3 gutter">

                <?php if (isset($_SESSION['merchandise'])) : ?>
                    <p><?php echo $_SESSION['merchandise']; unset($_SESSION['merchandise']); ?> </p>
                <?php endif ?>

            </div>
            <br>
                <?php if(!isset($_SESSION['username'])) : ?>  
                    <button onclick="document.getElementById('id01').style.display='block'" style="width: 20%; background-color: #FDCA40; border-radius: 40px; border: 0px; color: white; font-family: prompt; margin: 13px;">MORE OFFICIAL PRODUCTS</button>
                <?php endif ?>
                <?php if(isset($_SESSION['username'])) : ?>  
                    <!-- edit here change link when login success -->
                    <button onclick="document.location='merchandise_page.php'" style="width: 20%; background-color: #FDCA40; border-radius: 40px; border: 0px; color: white; font-family: prompt; margin: 13px;">MORE OFFICIAL PRODUCTS</button>
                <?php endif ?>
                
        </div> <!-- /.container -->
    </section>
    <!-- End official product -->

    <!-- Start Contact -->
    <section id="contact" class="p-top-80 p-bottom-50">
        <div class="container">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <!-- Section Title -->
                    <div class="section-title text-center m-bottom-40">
                        <h2 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.6s">Contact</h2>
                        <div class="divider-center-small wow zoomIn" data-wow-duration="1s" data-wow-delay="0.6s"></div>
                        <p class="section-subtitle wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s" style="font-family:prompt;"><em>ช่องทางการติดต่อและสอบถาม TICKON</em></p>
                    </div>
                </div> <!-- /.col -->
            </div>  <!-- /.row -->

            <div class="row">

                <!-- === Contact Form === -->
                <div class="col-md-7 col-sm-7 p-bottom-30">
                    <div class="contact-form row">

                        <form name="ajax-form" id="ajax-form" action="contact.php" method="post">
                            <div class="col-sm-6 contact-form-item wow zoomIn">
                                <input name="name" id="name" type="text"   placeholder="ชื่อ-นามสกุล: *" style="font-family: prompt;">
                                <span class="error" id="err-name">please enter name</span>
                            </div>
                            <div class="col-sm-6 contact-form-item wow zoomIn">
                                <input name="email" id="email" type="text"  placeholder="E-Mail: *" style="font-family: prompt;"/>
                                <span class="error" id="err-email">please enter e-mail</span>
                                <span class="error" id="err-emailvld">e-mail is not a valid format</span>
                            </div>
                            <div class="col-sm-12 contact-form-item wow zoomIn">
                                <textarea name="message" id="message" placeholder="ใส่ข้อความ" style="font-family: prompt;"></textarea>
                            </div>
                            <div class="col-sm-12 contact-form-item" style="text-align: center; padding: 5px;">
                                <button class="send_message btn btn-main btn-theme wow fadeInUp" id="send" data-lang="en" style=" margin-bottom: 15px; border: none; font-family: prompt; padding: 5px;">submit</button>                
                            </div>
                            <div class="clear"></div>   
                            <div class="error text-align-center" id="err-form">There was a problem validating the form please check</div>
                            <div class="error text-align-center" id="err-timedout">The connection to the server timed out</div>
                            <div class="error" id="err-state"></div>
                        </form> 
                                    
                        <div class="clearfix"></div>
                        <div id="ajaxsuccess">Successfully sent</div>
                        <div class="clear"></div>

                    </div> <!-- /.contacts-form & inner row -->
                </div> <!-- /.col -->

                <!-- === Contact Information === -->
                <div class="col-md-5 col-sm-5 p-bottom-30">
                    <address class="contact-info">

                        <!-- === Location === -->
                        <div class="m-top-20 wow slideInRight">
                            <div class="contact-info-icon">
                                <i class="fa fa-location-arrow"></i>
                            </div>
                            <div class="contact-info-title" style="font-family: prompt;">
                                Address:
                            </div>
                            <div class="contact-info-text" style="font-family: prompt;">
                                99 Moo 18 Paholyothin Road, Klong Luang, Rangsit, Prathumthani 12121
                            </div>
                        </div>

                        <!-- === Phone === -->
                        <div class="m-top-20 wow slideInRight" style="font-family: prompt;">
                            <div class="contact-info-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="contact-info-title">
                                Phone number:
                            </div>
                            <div class="contact-info-text">
                                +66 (0) 5423 7999
                            </div>
                        </div>

                        <!-- === Mail === -->
                        <div class="m-top-20 wow slideInRight" style="font-family: prompt;">
                            <div class="contact-info-icon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="contact-info-title">
                                Email:
                            </div>
                            <div class="contact-info-text">
                                scitu_cs@sci.tu.ac.th
                            </div>
                        </div>

                    </address>
                </div> <!-- /.col -->

            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>
    <!-- End Contact -->

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
    <script src="inc/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script src="inc/revolution/js/jquery.themepunch.revolution.min.js"></script>

    <!-- Contact Form -->
    <script src="js/contact.js"></script>
    
    <!-- Custom Plugin -->
    <script src="js/custom.js"></script>

    <!-- RS Plugin Extensions -->
    <script src="inc/revolution/js/extensions/revolution.extension.video.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script src="inc/revolution/js/extensions/revolution.extension.parallax.min.js"></script>


    <script>
      var tpj = jQuery;

      var revapi280;
      tpj(document).ready(function() {
          if (tpj("#rev_slider_nagency").revolution == undefined) {
              revslider_showDoubleJqueryError("#rev_slider_nagency");
          } else {
              revapi280 = tpj("#rev_slider_nagency").show().revolution({
                  sliderType: "standard",
                  sliderLayout: "fullscreen",
                  dottedOverlay: "none",
                  delay: 90000,
                  navigation: {
                    keyboardNavigation:"off",
                    keyboard_direction: "horizontal",
                    mouseScrollNavigation:"off",
                    onHoverStop:"off",
                    touch:{
                      touchenabled:"on",
                      swipe_threshold: 75,
                      swipe_min_touches: 1,
                      swipe_direction: "horizontal",
                      drag_block_vertical: false
                    }
                    ,
                    arrows: {
                          style: "uranus",
                          enable: true,
                          hide_onmobile: true,
                          hide_under: 496,
                          hide_onleave: true,
                          hide_delay: 200,
                          hide_delay_mobile: 1200,
                          tmp: '',
                          left: {
                              h_align: "left",
                              v_align: "center",
                              h_offset: 20,
                              v_offset: 0
                          },
                          right: {
                              h_align: "right",
                              v_align: "center",
                              h_offset: 20,
                              v_offset: 0
                          }
                      }
                  },
                  responsiveLevels: [1200, 991, 767, 480],
                  visibilityLevels: [1200, 991, 767, 480],
                  gridwidth: [1200, 991, 767, 480],
                  gridheight: [868, 768, 960, 720],
                  lazyType: "none",
                  parallax: {
                    type:"mouse+scroll",
                    origo:"slidercenter",
                    speed:2000,
                    levels:[2,3,4,5,6,7,12,16,10,50],
                    disable_onmobile:"on"
                  },
                  shadow: 0,
                  spinner: "spinner2",
                  autoHeight: "off",
                  fullScreenAutoWidth: "off",
                  fullScreenAlignForce: "off",
                  fullScreenOffsetContainer: "",
                  fullScreenOffset: "0",
                  disableProgressBar: "on",
                  hideThumbsOnMobile: "off",
                  hideSliderAtLimit: 0,
                  hideCaptionAtLimit: 0,
                  hideAllCaptionAtLilmit: 0,
                  debugMode: false,
                  fallbacks: {
                      simplifyAll: "off",
                      disableFocusListener: false,
                  }
              });
          }
      }); /*ready*/
  </script>
    
  </body>
</html>