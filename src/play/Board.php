<?php
include_once "Game.php"; 
class Board{

    private $intersections=array();//represents 2d array of 15x15 intersections on board
    private $size =15;//represents board size of 15x15
    private $winningRow;//represents winning row
    private $computerPlayerStones=array();//represents computer players placed stones of format [x1,y1,x2,y2,...,xn,yn]
    private $humanPlayerStones=array();//represents human players placed stones of format [x1,y1,x2,y2,...,xn,yn]
    private $gameData;


    //Default constructor
    public function __construct($humanPlayerStones,$computerPlayerStones, $gameData) {
        //initializes 2d array of 15x15 intersections on board

        $this->gameData = $gameData;

        for ($i = 0; $i < $this->size; $i++) {
            for ($j = 0; $j < $this->size; $j++) {
                $this->intersections[$i][$j] = "EMPTY";
            }
        }

        //set both players stones to class properties
        $this->humanPlayerStones=$humanPlayerStones;
        $this->computerPlayerStones=$computerPlayerStones;


        //place human players stones on board
        $count = count($this->humanPlayerStones);
        for ($i = 0; $i < $count; $i += 2) {
            $x = $this->humanPlayerStones[$i];
            $y = $this->humanPlayerStones[$i + 1];
            $this->placeStone($x, $y, "HUMAN");
        }



        //place computer players stones on board
        $count = count($this->computerPlayerStones);
        for ($i = 0; $i < $count; $i += 2) {
            $x = $this->computerPlayerStones[$i];
            $y = $this->computerPlayerStones[$i + 1];
            $this->placeStone($x, $y, "COMPUTER");
        }
    }

    //NOTE: MIGHT DELETE LATER, SERVES NO USE
    //returns 2d array of intersections on board
    public function intersections(){
        return $this->intersections;
    }
 


    //assigns player to intersection
    public function placeStone($x,$y,$player){
        $this->intersections[$x][$y]=$player;
    }


    //checks for draw by checking if all intersections on board are full
    public function isDraw(){
        for ($i = 0; $i < 15; $i++) {
            for ($j = 0; $j < 15; $j++) {
                if($this->intersections[$i][$j]==="EMPTY"){
                    return false;
                }
            }
        }
        return true;
    }


    //check for a winning row of 5
    public function checkForWin($x, $y, $player, $n) {
        $this->winningRow = [];
        if($this->intersections[$x][$y] == $player) {
            // Vertical check
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countUp($x, $y - 1, $player, $tempWinningRow) + $this->countDown($x, $y + 1, $player, $tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                return true;
            }
    
            // Horizontal check
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countLeft($x - 1, $y, $player, $tempWinningRow) + $this->countRight($x + 1, $y, $player, $tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                return true;
            }
    
            // Diagonal check - top-left to bottom-right
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countDiagonalTL($x - 1, $y - 1, $player, $tempWinningRow) + $this->countDiagonalBR($x + 1, $y + 1, $player, $tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                return true;
            }
    
            // Diagonal check - top-right to bottom-left
            $tempWinningRow = [$x, $y];
            $count = 1 + $this->countDiagonalTR($x + 1, $y - 1, $player, $tempWinningRow) + $this->countDiagonalBL($x - 1, $y + 1, $player, $tempWinningRow);
            if ($count >= $n) {
                $this->winningRow = $tempWinningRow;
                $this->gameData['winningRow'] = $this->winningRow;
                return true;
            }
        }
    
        // No win condition met
        return false;
    }
    
    // Upwards count
    public function countUp($x, $y, $player, &$tempWinningRow) {
        if ($y >= 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countUp($x, $y - 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Downwards count
    public function countDown($x, $y, $player, &$tempWinningRow) {
        if ($y < $this->size && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDown($x, $y + 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Leftwards count
    public function countLeft($x, $y, $player, &$tempWinningRow) {
        if ($x >= 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countLeft($x - 1, $y, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Rightwards count
    public function countRight($x, $y, $player, &$tempWinningRow) {
        if ($x < $this->size && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countRight($x + 1, $y, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Diagonal TL count
    public function countDiagonalTL($x, $y, $player, &$tempWinningRow) {
        if ($x >= 0 && $y >= 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonalTL($x - 1, $y - 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Diagonal BR count
    public function countDiagonalBR($x, $y, $player, &$tempWinningRow) {
        if ($x < $this->size && $y < $this->size && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonalBR($x + 1, $y + 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Diagonal TR count
    public function countDiagonalTR($x, $y, $player, &$tempWinningRow) {
        if ($x < $this->size && $y >= 0 && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonalTR($x + 1, $y - 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    // Diagonal BL count
    public function countDiagonalBL($x, $y, $player, &$tempWinningRow) {
        if ($x >= 0 && $y < $this->size && $this->intersections[$x][$y] == $player) {
            $tempWinningRow[] = $x;
            $tempWinningRow[] = $y;
            return 1 + $this->countDiagonalBL($x - 1, $y + 1, $player, $tempWinningRow);
        }
        return 0;
    }
    
    public function getGameData() {
        return $this->gameData;
    }
    

    public function isEmpty($x, $y) {
        // Assuming $this->intersections is the 2D array representing the board
        // Check if the position is within the board bounds and is empty
        return isset($this->intersections[$x][$y]) && $this->intersections[$x][$y] == "EMPTY";
    }

    //NOTE: add function that counts diagonally from bottom right to top left

}

?>