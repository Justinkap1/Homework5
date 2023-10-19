<?php
    session_start();
    include("index.php");

    if (!isset($_SESSION['pastAnswers'])) {
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
        //print_r($_SESSION['curTableMap']);
        $guessBool = false;
        if (checkGuess($_SESSION['guess'], $_SESSION['triviaArray']) === true){
            $guessBool = true;
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
    if (count($_SESSION['randomizedOrder']) === 0 || count($_SESSION['pastAnswers']) === 6){
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
        <header class="game-header">
            <?php
            echo "<h1> User: " . $_SESSION["name"] . "</h1>";
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
        <section class="past-guesses">
        <?php
        //$_SESSION['pastAnswers'] = array_diff($_SESSION['pastAnswers'], $_SESSION['pastAnswers']); 
        echo "You have used " . count($_SESSION['pastAnswers']) . " guesses and have " . (6 - count($_SESSION['pastAnswers'])) . " guesses remaining";
        echo "<br>";
        echo "<br>";
        echo "Last Guesses: ";
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
        //print_r($_SESSION['triviaArray']);
        ?>
        <p>
        <?php
        if (isset($guessBool))
            if ($guessBool === true){
                echo "You got a category!";
            }
            else{
                echo "Try again!";
            }
        ?>
        </p>
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
        <section class='game-over-button'>
            <a href="gameOver.php">
                <button>Leave game</button>
            </a>
        </section>
    </body>
</html>