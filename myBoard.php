<?php
    // include(dirname(__DIR__).'/boardGenerator.php');
    // include(dirname(__DIR__).'/myBoard.php');
    // include(dirname(__DIR__).'/userlogin.php');

    session_start();
    //get all data from files first
    if(!isset($_SESSION['user'])){
        header("location: ./userlogin.php");
    }
    $user = $_SESSION['user'];
    $allUsers = array();
    foreach (file('./boardData.txt') as $line) {
        $entry = explode(" ", $line);
        $allUsers[$entry[0]] = explode(",",$entry[1]);
    }
    // print_r($allUsers);
    $txtFile = fopen('./bank.txt','r');
    $allPrompts = array();
    while ($line = fgets($txtFile)) {
        $allPrompts[] = $line;
    }
    fclose($txtFile);


    //replacing a line
    function replace_a_line($data, $replacementLine) {
        if (stristr($data, $_SESSION['user'])) {
            return $replacementLine;
        }
        return $data;
    }



    if(array_key_exists($user, $allUsers)){
        //x is index 0-24
        //i is bank key 1-25
        
        $arr = $allUsers[$user];

        //check if url has any flips
        $flip = isset($_GET['flip']) ? (int)$_GET['flip'] : -1;
        if ($flip != -1) {
            $arr[$flip] = -1 * $arr[$flip];
            $newStr = $user. " ";
            $newBoardData = "";
            foreach($arr as $num){
                $newBoardData = $newBoardData . ',' .strval($num);
            }
            $newBoardData = substr($newBoardData, 1);
            if($flip == 24){
                $newStr = $newStr . $newBoardData."\n";
            }else{
                $newStr = $newStr . $newBoardData;
            }
            
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
            header("location: ./myBoard.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Board</title>
    <link href="./style.css", rel="stylesheet">
</head>
<body>
    <h1 class="center2">Cult Kraken Bingo</h1>
    <div class="scoreboard">
        <form>
            <?php
                $scoreFiles = fopen('./wins.txt','r');
                $score = array();
                while ($line = fgets($scoreFiles)) {
                    $entry = explode(",", $line);
                    $score[$entry[0]] = $entry[1];
                }
                fclose($scoreFiles);
                foreach ($allUsers as $key => $value) {
                    if($key != $user){
                        
                        echo "
                        <div class = 'user'>
                            <div class = 'name'>".$key."</div>
                            <a class='invis' href='./myBoard.php?name=".$key."'><div class = 'board'>Show Board</div></a>
                            <div class='wins'>Wins: ";
                            echo $score[$key];
                            echo " </div>
                        </div>";
                        
                    }
                }
            ?>
        </form>
    </div>
    <?php
        if(isset($_GET['name'])){
            if(array_key_exists($_GET['name'], $allUsers)){
                echo "<div class='bingo-grid'>";
                echo "
                <div class=\"title\">
                    ".$_GET['name']."'s Board
                    <p>Wins: " . $score[$_GET['name']] . "</p>
                </div>
                ";
                for ($x = 0; $x < 25; $x++){
                    $i = $allUsers[$_GET['name']][$x];
                    if($i > 0){
                        echo "<button>".$allPrompts[trim($i)]."</button>";
                    }else{
                        echo "<button class='selected'>".$allPrompts[abs(trim($i))]."</button>";
                    }
                    
                }
                echo "</div>";
            }else{
                echo "<form class= \"center\" action=\"./boardGenerator.php\" method=\"post\"><button>Generate Board</button></form>";
            }
        }else{
            if(array_key_exists($user, $allUsers)){
                echo "<div class='bingo-grid'>";
                echo "
                <div class=\"title\">
                    My Board
                    <p>Wins: " . $score[$user] . "</p>
                </div>
                ";
                for ($x = 0; $x < 25; $x++){
                    $i = $arr[$x];
                    if($i > 0){
                        echo "<button onclick=\"location.href='./myBoard.php?flip=".trim($x)."'\">".$allPrompts[trim($i, "\n")]."</button>";
                    }else{
                        echo "<button class='selected' onclick=\"location.href='./myBoard.php?flip=".$x."'\">".$allPrompts[abs(trim($i, "\n"))]."</button>";
                    }
                    
                }
                echo "</div>";
            }else{
                echo "<form class= \"center\" action=\"./boardGenerator.php\" method=\"post\"><button>Generate Board</button></form>";
            }
        }
        
    ?>

    <div class="scoreboard">
        <div class="control-grid">
            <form class="controlForms" action="./checkBingo.php" method="POST">
                <input class="bingo" type="submit" value="BINGO">
            </form>

            <button class="small" onclick="window.location.href='./myBoard.php'">My Board</button>

            

            <button class="small" onclick="window.location.href='./logout.php'">Log Out</button>


            <form class="controlForms" action="./boardGenerator.php" method="POST">
                <input class="bingo" type="submit" value="RESET">
            </form>
            
            
        </div>
        
    </div>
</body>
</html>