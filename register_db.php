<?php 
    session_start();
    include('server.php');

    $errors = array();

    if(isset($_POST['reg_user'])) {
        //member
        $c_ID = mysqli_real_escape_string($conn, $_POST['ID_Member']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $Bdate = mysqli_real_escape_string($conn, $_POST['Bdate']);
        $userID = mysqli_real_escape_string($conn, $_POST['username']);
        $userPass1 = mysqli_real_escape_string($conn, $_POST['userPass1']);
        $userPass2 = mysqli_real_escape_string($conn, $_POST['userPass2']);
        //member_address
        $adrName = mysqli_real_escape_string($conn, $_POST['Address_name']);
        $adrNum = mysqli_real_escape_string($conn, $_POST['Address_number']);
        $postal = mysqli_real_escape_string($conn, $_POST['address_postal_code']);
        $road = mysqli_real_escape_string($conn, $_POST['Road']);
        $adrProvince = mysqli_real_escape_string($conn, $_POST['Address_province']);
        $adrDis = mysqli_real_escape_string($conn, $_POST['Address_district']);
        //member_phone
        $phoneNum = mysqli_real_escape_string($conn, $_POST['phone']);


        if ($userPass1 != $userPass2){
            array_push($errors, "The two passwords do not match");
        }
        $user_check_query = "SELECT * FROM member WHERE userID = '$userID'";
        $query = mysqli_query($conn, $user_check_query);
        $result = mysqli_fetch_assoc($query);

        if($result){ // if user exists
            if ($result['userID'] === $userID){
                    array_push($errors, "Username already exists");
            }
            

        }

        if (count($errors)==0){

            $password = $userPass1;
            $query2 = mysqli_query($conn, "SELECT * FROM member ORDER BY ID_Member DESC LIMIT 1");
            $ID_pre = '';
            $ID_new = '';
            while($row=mysqli_fetch_array($query2))
            {
                $ID_pre = $row[0];
                $ID_new = 'M'. str_pad(intval(substr($ID_pre,1)) + 1, 5, '0', STR_PAD_LEFT);
            }
            //insert member
            $sqldb = mysqli_query($conn,"INSERT INTO member (ID_Member,CitizenID, prefix, fname, lname, mem_birthdate, userID, User_pass) VALUES ('$ID_new','$c_ID', '$gender', '$fname', '$lname', '$Bdate', '$userID', '$password')");
          
            //insert member_address
            $sqldb2 = mysqli_query($conn,"INSERT INTO `member_address`(`ID_Member`, `Address_name`, `Address_house_num`, `Address_road`, `Address_province`, `Address_district`, `Address_postel_code`) VALUES ('$ID_new','$adrName','$adrNum','$road','$adrProvince','$adrDis','$postal')");
            
            //insert member_phone
            $sqldb3 = mysqli_query($conn,"INSERT INTO `member_phone`(`ID_Member`, `PhoneNumber`) VALUES ('$ID_new','$phoneNum')");
            //mysqli_query($conn,$sqldb3);


            $_SESSION['username'] = $userID;
            $_SESSION['success'] = "You are now logged in";
            header('location: mainpage.php');
        } else {
            array_push($errors, "Username or Email already exists");
            $_SESSION['error'] = "Username or Email already exists";
            header("location: register_member.php");
        }
    }
?>
    
        
    
