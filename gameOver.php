<?php
    session_start();   
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
    <body class='game-over-body'> 
        <section class="game-over">
            <p>
            <?php
            if (($_SESSION['name'] === null || $_SESSION['name'] === "") || ($_SESSION['email'] === null || $_SESSION['email'] === "")){
                header("Location: welcome.php");
                exit();
            }
            unset($_SESSION['pastAnswers']);
            if (count($_SESSION['randomizedOrder']) === 0){
                echo "Congratulations ". $_SESSION['name']  .", you won!";
            }
            else{
                echo "Sorry " . $_SESSION['name'] .", you couldn't guess them all!";
            }
            if ($_SESSION['totalGuesses'] >= 1){
                echo "<div> You used " . $_SESSION['totalGuesses']." total guesses </div>";
            }
            else{
                echo "<div> You quit before guessing! </div>";
            }
            echo "<br>";
            for ($i = 0; $i < count($_SESSION['triviaArray']); $i++){
                echo "<div class='answers'>";
                echo "<h3>" . $_SESSION['triviaArray'][$i]["category"] . " </h3>";
                for ($j = 0; $j < count($_SESSION['triviaArray'][$i]['words']); $j++){
                    if(in_array($_SESSION['triviaArray'][$i]['words'][$j], $_SESSION['curTableMap'])){
                        echo "<div class='eachAnswer'>";
                        echo $_SESSION['triviaArray'][$i]['words'][$j] . " ";
                        echo "</div>";
                    }
                    else{
                        echo "<div class='eachAnswer2'>";
                        echo $_SESSION['triviaArray'][$i]['words'][$j] . " ";
                        echo "</div>";
                    }
                }
                echo "</div>";
            }
            ?>
            </p>
            <div class="options">
                <form class="play-again-form" action="/game.php" method="post">
                    <input type="hidden" value="<?php echo $_SESSION['name'] ?>" name="name" />
                    <input type="hidden" value="<?php echo $_SESSION['email'] ?>" name="email" />
                    <input type="submit" value="Play Again">
                </form>
                <a href="welcome.php">
                    To Welcome Page
                </a>
            </div>
        </section>
    </body>