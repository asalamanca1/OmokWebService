<?php
include "Board.php";
include_once "RandomStrategy.php"; // Include this at the top of your

class Game{
    public Board $board;//respresents game board
    public $strategy;//represents games strategy
    private $gameStateFile;//game state file
    private $gameData;//stores game state data array
    public $computerPlayerStones;

    //PSEUDOCODE
    //instantiate board with player and computer intersections
    //check for draw
    //check for win
    //update file
    //update json response


    //constructor that takes pid & both player and computer rows
    public function __construct($gameStateFile, $x, $y, $player) {

        //represents this games gameStateFile
        $this->gameStateFile=$gameStateFile;

        //read the contents of game state file
        $fileContent = file_get_contents($gameStateFile);

        //decode the json content into an array
        $this->gameData = json_decode($fileContent, true);


        //store human players placed stones
        $humanPlayerStones=$this->gameData['humanPlayerStones'];
        //store computer players placed stones
        $computerPlayerStones=$this->gameData['computerPlayerStones'];

        //append new coordinates to players stones
        $appendedCoordinates=[$x,$y];
      
       
        if($player=="HUMAN"){
          
            //$humanPlayerStones=$humanPlayerStones+$appendedCoordinates;
            $humanPlayerStones=array_merge($humanPlayerStones, $appendedCoordinates);
           
        
            $this->gameData['humanPlayerStones']=$humanPlayerStones;
        }
        else{
            $computerPlayerStones+=$appendedCoordinates;
            $this->gameData['computerPlayerStones']=$computerPlayerStones;
        }
        
        //instantiate the Board class
        $this->board = new Board($humanPlayerStones, $computerPlayerStones);

        //if player is human, place stone on board and set it to humanPlayers stone
        if($player=="HUMAN"){
            $this->board->placeStone($x,$y,"HUMAN");
        }
        //if player is computer, place stone on board and set it to computerPlayers stone
        else{
            $this->board->placeStone($x,$y,"COMPUTER");
        }

        
       


        //checking for a draw
        if ($this->board->isDraw()) {
            //if theres a draw, update game data
            $this->gameData['isDraw']=true;
        }
        //check if human player won game
        else if ($this->board->checkForWin($x, $y, "HUMAN")) {
            //update game state to showcase win, winning row
            $this->gameData['humanWon']=true;
        }
        //check if computer player won game
        else if ($this->board->checkForWin($x, $y, "COMPUTER")) {
            //update game state to showcase win, winning row
            $this->gameData['computerWon']=true;
        }
        //convert the array to JSON
        $newFileContent = json_encode($this->gameData);
        //update game state file
        file_put_contents($this->gameStateFile, $newFileContent);


    }

    function CPUMove(){
        $random = new RandomStrategy($this->board);
        $computerMove = $random->pickPlace($this->board); // Get the computer's move
    
        // Check if the move is valid and update the board and game state
        if (isset($computerMove['x']) && isset($computerMove['y'])) {
            $this->board->placeStone($computerMove['x'], $computerMove['y'], "COMPUTER");
            $this->gameData['computerPlayerStones'][] = $computerMove['x'];
            $this->gameData['computerPlayerStones'][] = $computerMove['y'];
    
            // Save the updated game state
            $newFileContent = json_encode($this->gameData);
            file_put_contents($this->gameStateFile, $newFileContent);
        }
    
        return $computerMove;
    }
    


    /*
    static function fromJson($json) {
        $obj = json_decode($json); // of stdClass
        $strategy = $obj->{'strategy'};
        $board = $obj->{'board'};
        $game = new Game();
        $game->board = Board::fromJson($board);
        $name = $strategy->{'name'};
        $game->strategy = $name::fromJson($strategy);
        $game->strategy->board = $game->board;
        return $game;
     }
     */
  
}
?>