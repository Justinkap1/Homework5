<?php
    session_start();
    include("index.php");

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
        $guessCounter = checkGuess($_SESSION['guess'], $_SESSION['triviaArray']);
        //echo $guessCounter;
        if ($guessCounter === 1){
            $_SESSION['offset'] += 1;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trivia'])){
        $_SESSION['triviaArray'] = unserialize($_POST['trivia']);
    }
    else{
        $_SESSION['name'] = htmlspecialchars($_POST['name']);
        $_SESSION['email'] = htmlspecialchars($_POST['email']);
        $_SESSION['triviaArray'] = getTrivia();
        $_SESSION['randomizedOrder'] = displayTrivia($_SESSION['triviaArray']);

    }
    if (($_SESSION['name'] === null || $_SESSION['name'] === "") || ($_SESSION['email'] === null || $_SESSION['email'] === "")){
        header("Location: welcome.php");
        exit();
    }
    if (count($_SESSION['randomizedOrder']) === 0 || count($_SESSION['pastAnswers']) - $_SESSION['offset'] === 5){
        header("Location: gameOver.php");
        exit();
    }
    //var_dump($_SESSION['tableMap']);
    //echo "<br>";
    //var_dump($_SESSION['curTableMap']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Justin Kaplan and Mihir Sangameswar" content="Homework5">
        <link rel="stylesheet" href="styles/styles.css"> 

        <title>Trivia Game</title>  
    </head>
    <body>
        <section class='game-over-button'>
            <a href="gameOver.php">
                <button>Leave game</button>
            </a>
        </section>
        <header class="game-header">
            <?php
            echo "<h1> User: " . $_SESSION['name'] . "</h1>";
            echo "<h1> Email: " . $_SESSION['email'] . "</h1>";
            //print_r($_SESSION['randomizedOrder']);
            //echo "<br>";
            //print_r($_SESSION['curTableMap']);
            //print_r($_SESSION['triviaArray']);
            ?>    
        </header>
        <section class="words">                                                      
            <?php
            echo '<table>';
            for ($i = 0; $i < (sizeof($_SESSION['randomizedOrder'])/4); $i++) {
                echo '<tr>';
                for ($j = 0; $j < 4; $j++) {
                    $index = $i * 4 + $j;
                    $_SESSION['curTableMap'][$index] = $_SESSION['randomizedOrder'][$index];
                    echo '<td>' . $index+1 . ". " . $_SESSION['randomizedOrder'][$index] . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
            //print_r($tableMap);
            
            ?>
        </section>
        <p>Please enter the numbers for your guess below, space separated.</p>
        <div class="flex-container">
            <section class="past-guesses">
            <?php
            echo "You have used " . (count($_SESSION['pastAnswers']) - $_SESSION['offset']) . " guesses and have " . (6 - count($_SESSION['pastAnswers']) + $_SESSION['offset']) . " guesses remaining";
            echo "<br>";
            echo "<br>";
            echo "Last Guesses: ";
            echo "<section class='guesses-list'>";
            for ($i = 0; $i < count($_SESSION['pastAnswers']); $i++){
                echo "<br>";
                for ($j = 0; $j < count($_SESSION['pastAnswers'][$i]); $j++){
                    if ($j !== 3){
                        echo $_SESSION['pastAnswers'][$i][$j] . ", ";
                    }
                    else{
                        echo $_SESSION['pastAnswers'][$i][$j];
                    }
                }
            }
            echo "</section>";
            ?>
            </section>
            <section class="result-message">
            <?php
            if (isset($guessCounter)){
                if ($guessCounter === 1){
                    echo "You got a category!";
                }
                else if ($guessCounter === 0){
                    echo "Try again!";
                }
                else if($guessCounter === 2){
                    echo "You were close, but missing 2 words!";
                }
                else if($guessCounter === 3){
                    echo "You were close, but missing 1 word!";
                }
                else if($guessCounter === 4){
                    echo "Please enter 4 unique numbers from the table.";
                }
            }
            ?>
            </section>
            <section class='guess-section'>
            <form class="guess-form" action="/game.php" method="post">
                <label for="guess">Your guess:</label>
                <input type="text" name="guess">
                <br>
                <input type="hidden" value="<?php echo $_POST['name'] ?>" name="name" />
                <input type="hidden" value="<?php echo $_POST['email'] ?>" name="email" />
                <input type="hidden" value="<?php echo htmlentities(serialize($_SESSION['triviaArray'])); ?>" name="trivia" />
                <input type="submit" value="Submit">
            </form>
            </section>
        </div>
    </body>
</html>