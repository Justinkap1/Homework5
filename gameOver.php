<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">  
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Justin Kaplan and Mihir Sangameswar" content="Homework5">
        <link rel="stylesheet" href="styles.css"> 

        <title>Trivia Game</title> 
    </head>
    <body class='game-over-body'> 
        <section class="game-over">
            <?=$gameOverMessage?>
            <?=$myGuesses?>
           <?php
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
            // print_r($_SERVER['REQUEST_METHOD']);
            // print_r($_SESSION['triviaArray']);
           ?>
            <div class="options">
                <form class="play-again-form" action="?command=game" method="post">
                    <input type="hidden" value="<?php echo $_SESSION['name'] ?>" name="name" />
                    <input type="hidden" value="<?php echo $_SESSION['email'] ?>" name="email" />
                    <input type="submit" value="Play Again">
                </form>
                <a href="?command=welcome">
                    To Welcome Page
                </a>
            </div>
        </section>
    </body>