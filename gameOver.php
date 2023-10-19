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

        <section class="game-over">
            <?php
            unset($_SESSION['pastAnswers']);
            if (count($_SESSION['randomizedOrder']) === 0){
                echo "Congratulations, you won!";
            }
            else{
                echo "You ran out of guesses!";
            }
            ?>
            <button>
                <a href="welcome.php">Exit</a>
            </button>
            <form class="play-again-form" action="/game.php" method="post">
                <label for="play-again">Play Again</label>
                <br>
                <input type="hidden" value="<?php echo $_SESSION['name'] ?>" name="name" />
                <input type="hidden" value="<?php echo $_SESSION['email'] ?>" name="email" />
                <input type="submit" value="Play Again">
            </form>
        </section>
    </head>