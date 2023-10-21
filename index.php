<?php
    session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    function getTrivia(){

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

    function displayTrivia($triviaArray){
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
    
    function checkGuess($guess, $triviaArray){
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
                        /*
                        echo "randomizedOrder: ";
                        print_r($_SESSION['randomizedOrder']);
                        echo "<br>";
                        echo "curTableMap: ";
                        print_r($_SESSION['curTableMap']);
                        */
                        
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
