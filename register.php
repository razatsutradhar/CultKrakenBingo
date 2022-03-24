<?php
    // include(dirname(__DIR__).'/myBoard.php');
    // include(dirname(__DIR__).'/userlogin.php');
    if(isset($_POST['login'])){
        $username = $_POST['uname'];
        $password1 = $_POST['pword1'];
        $password2 = $_POST['pword2'];

        
        
        $txtFile = fopen('./users.txt','r');
        $allUsers = array();
        while ($line = fgets($txtFile)) {
            $entry = explode(",", $line);
            $allUsers[$entry[0]] = $entry[1];
        }
        fclose($txtFile);
        if($password1 != $password2){
            header("location: ./userlogin.php?err=3");
        }else if(array_key_exists($username, $allUsers)){
            header("location: ./userlogin.php?err=2");
        }else{
            $fp = fopen('./users.txt', 'a');
            fwrite($fp, "\n".$username.','.$password1);  
            fclose($fp);  

            $fp = fopen('./wins.txt', 'a');
            fwrite($fp, "\n".$username.','. strval(0));  
            fclose($fp);  

            session_start();
            $_SESSION["user"] = $username;
            header("location: ./myBoard.php");
        }   
    }else{
        header("location: ./userlogin.php");
    }
?>