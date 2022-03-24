<?php
    // include(dirname(__DIR__).'/boardData.txt');
    // include(dirname(__DIR__).'/wins.txt');
    // include(dirname(__DIR__).'/bank.txt');
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
        $allUsers[$entry[0]] = explode(",",$entry[1]);
    }

    $numbers = range(1,25);
    shuffle($numbers);
    if(array_key_exists($user, $allUsers)){
        $newStr = $user. " ";
        $newBoardData = "";
        foreach($numbers as $num){
            $newBoardData = $newBoardData . ',' .strval($num);
        }
        $newBoardData = substr($newBoardData, 1);
        $newStr = $newStr . $newBoardData;
        $data = file('./boardData.txt'); 
        $data = array_map(
            function($item) use ($newStr) { return replace_a_line($item, $newStr); },
            $data);
        $newData = "";
        foreach($data as $value) {
            $newData = $newData . $value;
        }
        
        $file = fopen("./boardData.txt", "w");
        fwrite($file, $newData);
        fclose($file);
    }else{
        $newStr = $user. " ";
        $newBoardData = "";
        foreach($numbers as $num){
            $newBoardData = $newBoardData . ',' .strval($num);
        }
        $newBoardData = substr($newBoardData, 1);
        $newStr = "\n" . $newStr . $newBoardData;
        $file = fopen("./boardData.txt", "a");
        fwrite($file, $newStr);
        fclose($file);
    }
                header("location: ./myBoard.php");

?>