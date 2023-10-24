<?php
    session_start();
    include("index.php");


    $game = new CategoryGameController();
    $_SESSION['game'] = $game;
    // $serializedGame = serialize($game);
    // $_SESSION['game_instance'] = $serializedGame;

    // $game->startGame();
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
            $game->words();
            ?>
        </section>
        <section class="result-message">
            <?php
            $game->resultMessage();
            ?>
            </section>
        <p>Please enter the numbers for your guess below, space separated.</p>
        <div class="flex-container">
            <section class="past-guesses">
            <?php
            if (count($_SESSION['randomizedOrder']) === 0 || $_SESSION['totalGuesses']  - $_SESSION['offset'] === 5) {
                header("Location: gameOver.php");
            }
            else {
                $game->pastGuesses();
                
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
                <div class="submit-container">
                    <input type="submit" value="Submit">
                </div>
            </form>
            </section>
        </div>
    </body>

    <?php    
        $serializedGame = serialize($game);
        $_SESSION['game_instance'] = $serializedGame;
    ?>
</html>