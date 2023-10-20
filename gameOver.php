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
            unset($_SESSION['pastAnswers']);
            if (count($_SESSION['randomizedOrder']) === 0){
                echo "Congratulations ". $_SESSION['name']  .", you won!";
            }
            else{
                echo "Sorry " . $_SESSION['name'] .", You ran out of guesses!";
            }
            ?>
            </p>
            <span>Choose to either:</span>
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