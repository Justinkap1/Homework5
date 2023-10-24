<?php
    session_start();
    include("index.php");  
?>
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
            <p>
            <?php
            $serializedGame = $_SESSION['game_instance'];
            if($serializedGame==NULL) {
                header("Location: welcome.php");
                exit();
            }
            else {
                $game = unserialize($serializedGame);
                $game->gameOver();
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