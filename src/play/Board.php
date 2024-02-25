<?php

class Board{
    
    private $intersections=array();//represents 2d array of 15x15 intersections on board
    private $size =15;//represents board size of 15x15
    private $winningRow=array();//represents winning row
    private $computerPlayerStones=array();//represents computer players placed stones of format [x1,y1,x2,y2,...,xn,yn]
    private $humanPlayerStones=array();//represents human players placed stones of format [x1,y1,x2,y2,...,xn,yn]


    //Default constructor
    public function __construct($humanPlayerStones,$computerPlayerStones) {
        //initializes 2d array of 15x15 intersections on board
        
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
    public function checkForWin($x,$y,$player) {
        
        
        //for each intersection that belongs to player, check for a row of 5
        if($this->intersections[$x][$y]==$player){
            //Check vertically (up and down)
            $verticalCount = 1 + $this->countDown($x, $y - 1, $player) + $this->countUp($x, $y + 1, $player);
            if ($verticalCount >= 5) {
                //Win condition met vertically
                return true; 
            }
            //Check horizontally (left and right)
            $horizontalCount = 1 + $this->countLeft($x - 1, $y, $player) + $this->countRight($x + 1, $y, $player);
            if ($horizontalCount >= 5) {
                //Win condition met horizontally
                return true; 
            }
            //FIX THE LOGIC FOR CHECKING DIAGONAL ROWS, ITS CURRENTLY INCORRECT
            //Check diagonally (top-left to bottom-right)
            $diagonalTRCount = 1 + $this->countLeft($x - 1, $y - 1, $player) + $this->countRight($x + 1, $y + 1, $player);
            if ($diagonalTRCount >= 5) {
                //Win condition met diagonally (top-left to bottom-right)
                return true; 
            }
            //FIX THE LOGIC FOR CHECKING DIAGONAL ROWS, ITS CURRENTLY INCORRECT
            //Check diagonally (top-right to bottom-left)
            $diagonalTLCount = 1 + $this->countRight($x + 1, $y - 1, $player) + $this->countLeft($x - 1, $y + 1, $player);
            if ($diagonalTLCount >= 5) {
                //Win condition met diagonally (top-right to bottom-left)
                return true; 
            }
        }
          

        
        //No win condition met
        return false; 
    }
    

    //count downwards
    public function countDown($x,$y,$player){
        //if we are not at bottom of board and intersection corressponds to player
        if($y!=0&&$this->intersections[$x][$y]==$player){
            //make a recursive call downwards and add 1 to count
            return 1 + $this->countDown($x,$y-1,$player);
        }
        //add 0 to count if intersection doesnt belong to player
        return 0;
    }
    //count upwards
    public function countUp($x,$y,$player){
        //if we are not at top of board and intersection corressponds to player
        if($y!=14&&$this->intersections[$x][$y]==$player){
            //make a recursive call upwards and add 1 to count
            return 1 + $this->countDown($x,$y+1,$player);
        }
        //add 0 to count if intersection doesnt belong to player
        return 0;
    }
    //count left
    public function countLeft($x,$y,$player){
        //if we are not at leftmost of board and intersection corressponds to player
        if($x!=0&&$this->intersections[$x][$y]==$player){
            //make a recursive call leftwards and add 1 to count
            return 1 + $this->countDown($x-1,$y,$player);
        }
        //add 0 to count if intersection doesnt belong to player
        return 0;
    }
    //count right
    public function countRight($x,$y,$player){
        //if we are not at rightmost of board and intersection corressponds to player
        if($x!=14&&$this->intersections[$x][$y]==$player){
            //make a recursive call rightwards and add 1 to count
            return 1 + $this->countDown($x+1,$y,$player);
        }
        //add 0 to count if intersection doesnt belong to player
        return 0;
    }
    //Count diagonally (top-right to bottom-left)
    public function countDiagonal_TR_BL($x, $y, $player) {
        if ($x != 0 && $y != 0 && $this->intersections[$x][$y] == $player) {
            return 1 + $this->countDiagonal_TL_BR($x - 1, $y - 1, $player);
        }
        return 0;
    }

    //NOTE: add function that counts diagonally from bottom left to top right

    //Count diagonally (top-left to bottom-right)
    public function countDiagonal_TL_BR($x, $y, $player) {
        if ($x != 14 && $y != 0 && $this->intersections[$x][$y] == $player) {
            return 1 + $this->countDiagonal_TL_BR($x + 1, $y - 1, $player);
        }
        return 0;
    }

    //NOTE: add function that counts diagonally from bottom right to top left

}

?>