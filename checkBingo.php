<?php
    // include(dirname(__DIR__).'/boardData.txt');
    // include(dirname(__DIR__).'/wins.txt');
    // include(dirname(__DIR__).'/boardGenerator.php');
    // include(dirname(__DIR__).'/myBoard.php');
    // include(dirname(__DIR__).'/userlogin.php');


    session_start();
    $user = $_SESSION['user'];
    
    //replacing a line
    function replace_a_line($data, $replacementLine) {
        if (stristr($data, $_SESSION['user'])) {
            return $replacementLine . "\n";
        }
        return $data;
    }

    $allUsers = array();
    foreach (file('./boardData.txt') as $line) {
        $entry = explode(" ", $line);
        $allUsers[$entry[0]] = explode(",",trim($entry[1]));
    }
    
    $allScores = array();
    foreach (file('./wins.txt') as $line) {
        $entry = explode(",", $line);
        $allScores[$entry[0]] = intval(trim($entry[1]));
    }
    if(array_key_exists($user, $allUsers)){
        $myBoard = $allUsers[$user];
        $pass = true;

        //handle all cols first
        for($x = 0; $x < 5; $x++){
            $pass = true;
            for($y = 0; $y < 5; $y++){
                if (intval($myBoard[$y*5 + $x]) > 0){
                    $pass = false;
                    break;
                }
            }
            if($pass){
                break;
            }
            
        }

        //all rows
        if(!$pass){
            for($y = 0; $y < 5; $y++){
                $pass = true;
                for($x = 0; $x < 5; $x++){
                    if (intval($myBoard[$y*5 + $x]) > 0){
                        $pass = false;
                        break;
                    }
                }
                if($pass){
                    break;
                }
                
            }
        }
        
        //diagonal 1
        if(!$pass){
            $pass = true;
            for($x = 0; $x < 5; $x++){
                if (intval($myBoard[$x*5 + $x]) > 0){
                    $pass = false;
                    break;
                }
            }
        }

        //diagonal 2
        if(!$pass){
            $pass = true;
            for($x = 0; $x < 5; $x++){
                if (intval($myBoard[$x*5 + (4-$x)]) > 0){
                    $pass = false;
                    break;
                }
            }
        }

        //award point
        if($pass){
            // echo "Pass";
            $newStr = $user . "," . strval(($allScores[$user]+1));
            $data = file('./wins.txt'); 
            $data = array_map(
                function($item) use ($newStr) { return replace_a_line($item, $newStr); },
                $data);
            $newData = "";
            foreach($data as $value) {
                $newData = $newData . $value;
            }
            
            $file = fopen("./wins.txt", "w");
            fwrite($file, $newData);
            fclose($file);
            header("location: ./boardGenerator.php");
        }else{
            header("location: ./myBoard.php");
        }  
    }else{
        header("location: ./myBoard.php");
    }

?>