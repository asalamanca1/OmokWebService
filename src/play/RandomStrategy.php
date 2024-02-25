<?php
include "MoveStrategy.php";

class RandomStrategy extends MoveStrategy{
    protected $board;
    

    function pickPlace($board){
        while (true) {
            $x = mt_rand(0, 9);
            $y = mt_rand(0, 9);

            if ($board->board[$x][$y] == 0) {
                $board->board[$x][$y]=1;
                return array('x' => $x, 'y' => $y);
            }
        }
        return;
    }
    
}

$random = new RandomStrategy();
$result = $random->pickPlace($board);
if ($result['value'] == 0) {
    echo $result['value'] . " Empty Place at coordinates (" . $result['x'] . ", " . $result['y'] . ")";
}