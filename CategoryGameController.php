
<?php
    class CategoryGameController {
        private $input = [];
        public function __construct() {
            session_start();
            
            if (!isset($_SESSION['pastAnswers'])) {
                $_SESSION['offset'] = 0;
                $_SESSION['pastAnswers'] = [];
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guess'])) {
                // Update session only if form is submitted
                $_SESSION['guess'] = htmlspecialchars($_POST['guess']);
                //array_push($_SESSION['pastAnswers'], $_SESSION['guess']);
                $_SESSION['curTableMap'] = [];
                for ($i = 0; $i < (sizeof($_SESSION['randomizedOrder'])/4); $i++) {
                    for ($j = 0; $j < 4; $j++) {
                        $index = $i * 4 + $j;
                        $_SESSION['curTableMap'][$index] = $_SESSION['randomizedOrder'][$index];
                    }
                }
                //CALL TO CHECKGUESS
                $guessCounter = $this->checkGuess($_SESSION['guess'], $_SESSION['triviaArray']);
                //echo $guessCounter;
                if ($guessCounter === 1){
                    $_SESSION['offset'] += 1;
                }
                $_SESSION['totalGuesses'] = count($_SESSION['pastAnswers']);
            }
            if(isset($_POST['name'])) {
                $_SESSION['name'] = htmlspecialchars($_POST['name']);
                $_SESSION['email'] = htmlspecialchars($_POST['email']);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trivia'])){
                $_SESSION['triviaArray'] = unserialize($_POST['trivia']);
            }
            else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['trivia'])) {
                $_SESSION['triviaArray'] = unserialize($_POST['trivia']);
            }
            else{
                $_SESSION['triviaArray'] = $this->getTrivia();
                $_SESSION['randomizedOrder'] = $this->displayTrivia($_SESSION['triviaArray']);
                $_SESSION['totalGuesses'] = 0;
        
            }
            if (($_SESSION['name'] === null || $_SESSION['name'] === "") || ($_SESSION['email'] === null || $_SESSION['email'] === "")){
                // header("Location: hw5/welcome.php");
                $this->welcome();
                exit();
                
            }
            if (count($_SESSION['randomizedOrder']) === 0 || $_SESSION['totalGuesses']  - $_SESSION['offset'] === 5){
                $this->gameOverContr();
                exit();
            }

            $_SESSION['guessCounter'] = $guessCounter;
            // $_SESSION['firstTime'] = True;
        }

        public function getTrivia(){

            $contents = file_get_contents("https://www.cs.virginia.edu/~jh2jf/data/categories.json"); // Returns a string containing the file's contents
            $trivia_array = json_decode($contents, true); // Converts JSON string to associative array and returns the array
            $x = 4;
            $random_keys = array_rand($trivia_array, $x);
            $return_array = [];
            for ($i = 0; $i < 4; $i++){
                $curr = $random_keys[$i];
                //print_r($trivia_array[$curr]);
                //echo "<br>";
                array_push($return_array, $trivia_array[$curr]);
            }
            return $return_array;
        }

        public function displayTrivia($triviaArray){
            //print_r($triviaArray);
            $result = [];
            $count = 1;
            for ($i = 0; $i < sizeof($triviaArray); $i++){
                for ($j = 0; $j < 4; $j++){
                    array_push($result, $triviaArray[$i]["words"][$j]);
                }
            }
            shuffle($result);
            return $result;
        }

        public function checkGuess($guess, $triviaArray){
            $checkerFor2 = false;
            $checkerFor3 = false;
            $checker = false;
            $allWords = [];
            $indices = [];
            $numbers2 = explode(' ', $guess);
            $numbers = array_unique($numbers2);
            if (count($numbers) !== 4){
                return 4;
            }
            for ($j = 0; $j < count($triviaArray); $j++){
                $count = 0;
                for ($i = 0; $i < count($numbers); $i++){
                    settype($numbers[$i], "integer");
                    if (array_key_exists($numbers[$i]-1, $_SESSION['curTableMap'])){
                        $curWord = $_SESSION['curTableMap'][$numbers[$i] - 1];
                    }
                    else{
                        return 5;
                    }
                    if ($j === 0){
                        array_push($allWords, $curWord);
                    }
                    if (in_array($curWord, $triviaArray[$j]['words'])){
                        array_push($indices, $numbers[$i]);
                        $count++;
                        if ($count === 4){ 
                            //print_r($indices);
                            for ($k = 0; $k < count($indices); $k++){
                                unset($_SESSION['randomizedOrder'][$indices[$k]-1]);
                                unset($_SESSION['curTableMap'][$indices[$k]-1]);
                                //echo "<br>";
                            }
                            $_SESSION['randomizedOrder'] = array_values($_SESSION['randomizedOrder']);
                            $_SESSION['curTableMap'] = array_values($_SESSION['curTableMap']);
                            
                            // echo "randomizedOrder: ";
                            // print_r($_SESSION['randomizedOrder']);
                            // echo "<br>";
                            // echo "curTableMap: ";
                            // print_r($_SESSION['curTableMap']);
                            
                            
                            $checker = true;
                            break 2;
                        }
                    }
                    if(($i === 3) && $count === 2){
                        $checkerFor2 = true;
                    }
                    if(($i === count($numbers) - 1) && $count === 3){
                        $checkerFor3 = true;
                    }
                }
            }
            array_push($_SESSION['pastAnswers'], $allWords);
            if ($checker === true){
                return 1;
            }
            if($checkerFor2 === true){
                return 2;
            }
            if($checkerFor3 === true){
                return 3;
            }
            return 0;
        }

        public function words() {
            echo '<table>';
            // print_r($_SESSION['pastAnswers']);
            for ($i = 0; $i < (sizeof($_SESSION['randomizedOrder'])/4); $i++) {
                echo '<tr>';
                for ($j = 0; $j < 4; $j++) {
                    $index = $i * 4 + $j;
                    $_SESSION['curTableMap'][$index] = $_SESSION['randomizedOrder'][$index];
                    $size = count($_SESSION['pastAnswers']) - 1;
                    if(isset($_SESSION['pastAnswers']) && count($_SESSION['pastAnswers']) >= 1){
                        if (in_array($_SESSION['randomizedOrder'][$index], $_SESSION['pastAnswers'][$size])){
                            echo "<td class='highlighted'>" . $index+1 . ". " . $_SESSION['randomizedOrder'][$index] . "</td>";
                        }
                        else{
                            echo '<td>' . $index+1 . ". " . $_SESSION['randomizedOrder'][$index] . '</td>';
                        }
                    }
                    else{
                        echo '<td>' . $index+1 . ". " . $_SESSION['randomizedOrder'][$index] . '</td>';
                    }
                }
                echo '</tr>';
            }
            echo '</table>';
        }

        public function resultMessage() {
            $guessCounter = $_SESSION['guessCounter'];
            // echo $_SESSION['totalGuesses'];
            // echo $_SESSION['totalGuesses']  - $_SESSION['offset'];
            $myMessage = "";
                if (isset($guessCounter)){
                    if ($guessCounter === 1){
                        $myMessage =  "You got a category!";
                    }
                    else if ($guessCounter === 0){
                        $myMessage = "Try again!";
                    }
                    else if($guessCounter === 2){
                        $myMessage = "You were close, but missing 2 words!";
                    }
                    else if($guessCounter === 3){
                        $myMessage = "You were close, but missing 1 word!";
                    }
                    else if($guessCounter === 4){
                        $myMessage = "Please enter 4 unique numbers from the table.";
                    }
                    else if($guessCounter === 5){
                        $myMessage = "Please enter valid numbers for the current table.";
                    }
                }
            $_SESSION['myMessage'] = $myMessage;
        }

        public function pastGuesses() {
            $_SESSION['currentGuesses'] = "Current Guesses: " . (count($_SESSION['pastAnswers']));
            $_SESSION['mistakesRemaining'] = "Mistakes Remaining: " . 5 - count($_SESSION['pastAnswers']) + $_SESSION['offset'];
        }

        public function gameOver() {
            $_SESSION['gameOverMessage'] = "NULL";
            unset($_SESSION['pastAnswers']);
            if (count($_SESSION['randomizedOrder']) === 0){
                $_SESSION['gameOverMessage'] =  "Congratulations ". $_SESSION['name']  .", you won!";
            }
            else{
                $_SESSION['gameOverMessage'] =  "Sorry " . $_SESSION['name'] .", you couldn't guess them all!";
            }
            $_SESSION['myGuesses'] = "NULL";

            if ($_SESSION['totalGuesses'] >= 1){
                $_SESSION['myGuesses'] = "<div> You used " . $_SESSION['totalGuesses']." total guesses </div>";
            }
            else{
                $_SESSION['myGuesses'] =  "<div> You quit before guessing! </div>";
            }
        }

        public function run() {
            // Get the command
            $command = "welcome";
            if (isset($_GET["command"]))
                $command = $_GET["command"];
            switch($command) {
                case "game":
                    $this->game();
                    break;
                case "gameOver":
                    $this->gameOverContr();
                    break;
                case "welcome":
                    $this->welcome();
                    break;
                default:
                    $this->welcome();
                    break;
            }
        }
        
        public function game() {
            $this->pastGuesses();
            $this->resultMessage();
            $myMessage = $_SESSION['myMessage'];
            $currentGuessNum =  $_SESSION['currentGuesses'];
            $mistakesRemaining = $_SESSION['mistakesRemaining'];
            include "/opt/src/hw5/game.php";

        }
        

        public function gameOverContr() {
            $this->gameOver();
            $gameOverMessage = $_SESSION['gameOverMessage'];
            $myGuesses = $_SESSION['myGuesses'];
            // unset($_SESSION['pastAnswers']);
            include "/opt/src/hw5/gameOver.php";
        }

        public function welcome() {
            session_destroy();
            session_start();
            include "/opt/src/hw5/welcome.php";
        }

        public function checkOver() {
            if (count($_SESSION['randomizedOrder']) === 0 || $_SESSION['totalGuesses']  - $_SESSION['offset'] === 5) {
                return True;
            }
        }
    }



    // session_start();
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);
    

