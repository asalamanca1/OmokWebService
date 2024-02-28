<?php
include "Game.php";
include_once "RandomStrategy.php";
include_once "SmartStrategy.php"; 
// include other strategy files if necessary

//http://localhost:3000/src/play/index.php?pid=65daa65356166&x=1&y=4
//missing pid
if (!array_key_exists('pid', $_GET)) { 
    $response = array("response" => false, "reason" => "Pid not specified");
    echo json_encode($response);
    exit; 
}
//missing x coordinate
else if (!array_key_exists('x', $_GET)) { 
    $response = array("response" => false, "reason" => "X coordinate not specified");
    echo json_encode($response);
    exit; 
}
//missing y coordinate
else if (!array_key_exists('y', $_GET)) { 
    $response = array("response" => false, "reason" => "Y coordinate not specified");
    echo json_encode($response);
    exit; 
}
//invalid x coordinate
else if($_GET['x']<0||$_GET['x']>14){
    $response = array("response" => false, "reason" => "Invalid x coordinate");
    echo json_encode($response);
    exit; 
}
//invalid y coordinate
else if($_GET['y']<0||$_GET['y']>14){
    $response = array("response" => false, "reason" => "Invalid y coordinate");
    echo json_encode($response);
    exit; 
}
//check if game exists by search for data/pid file
else{
    //create gamestate file format using pid
    //NOTE FOR FER: comment out my path in initial declaration of $gameStateFile and replace it with your path
    // $gameStateFile='/Users/andre/Programming Languages/OmokWebService/src/data/';
    $gameStateFile='/Users/fernandomunoz/Documents/Omok_Web/OmokWebService/src/data/';
    $gameStateFile.=$_GET['pid'].'.txt';
    
    
    //search if the file already exists, if so we can proceed with game
    if ($_GET['pid']) {
        $x=$_GET['x'];
        $y=$_GET['y'];
        $fileContent = file_get_contents($gameStateFile);
        $gameData = json_decode($fileContent, true);
//

        //instantiate new Game and pass in x/y coordinate of placed stone
        $newGame = new Game($gameStateFile, $gameData, intval($x), intval($y),"HUMAN");
        
        // Add move strat here
        if($cpuMove = $newGame->CPUMove()){
            $fileContent = file_get_contents($gameStateFile);
            $gameData = json_decode($fileContent, true);
            $response=array(
                "response"=>true,
                "ack_move"=>array(
                    "x"=>$x,
                    "y"=>$y,
                    "isWin"=>$gameData['humanWon'],
                    "isDraw"=>$gameData['isDraw'],
                    "row"=>[1,1,2,2,3,3,4,4,5,5] //row will be winning row
                ),
                "move"=>array(
                    "x"=>$cpuMove['x'],
                    "y"=>$cpuMove['y'],
                    "isWin"=>$gameData['computerWon'],
                    "isDraw"=>$gameData['isDraw'],
                    "row"=>[1,1,2,2,3,3,4,4,5,5]
                )
            );
        }
        echo json_encode($response);
        exit;

    } 
    //gamestate file with given pid does not exist, this means a game has not been started with this pid
    else {
        $response = array("response" => false, "reason" => "Invalid Pid");
        echo json_encode($response);
        exit; 
    }

}

//nano /opt/homebrew/etc/php/7.4/php.ini
///opt/homebrew/lib/php/pecl/20190902

?>