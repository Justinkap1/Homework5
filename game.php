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
            <form class="play-again-form" action="?command=gameOver" method="post">
                    <button>Leave game</button>
                    <input type="hidden" value="<?php echo htmlentities(serialize($_SESSION['triviaArray'])); ?>" name="trivia" />
            </form>
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
            ?>
        </section>
        <section class="result-message">
            <?php 
            if($myMessage != 0) {
                echo $myMessage;
            }
            ?>
        </section>
        <p>Please enter the numbers for your guess below, space separated.</p>
        <div class="flex-container">
            <section class="past-guesses">
                <p> <?=$currentGuessNum?> </p>
                <p> <?=$mistakesRemaining?> </p>
                <?php
                    echo "<section class='guesses-list'>";
                            for ($i = 0; $i < count($_SESSION['pastAnswers']); $i++){
                                echo "<br>";
                                for ($j = 0; $j < count($_SESSION['pastAnswers'][$i]); $j++){
                                    if ($j !== 3){
                                        echo $_SESSION['pastAnswers'][$i][$j] . " | ";
                                    }
                                    else{
                                        echo $_SESSION['pastAnswers'][$i][$j];
                                    }
                                }
                            }
                    echo "</section>";
                ?>
            </section>
            <section class='guess-section'>
            <form class="guess-form" action="?command=game" method="post">
                <label for="guess">Your guess:</label>
                <input type="text" name="guess">
                <br>
                <input type="hidden" value="<?php echo $_SESSION['name'] ?>" name="name" />
                <input type="hidden" value="<?php echo $_SESSION['email'] ?>" name="email" />
                <input type="hidden" value="<?php echo htmlentities(serialize($_SESSION['triviaArray'])); ?>" name="trivia" />
                <div class="submit-container">
                    <input type="submit" value="Submit">
                </div>
            </form>
            </section>
        </div>
    </body>

    <?php    
        // $serializedGame = serialize($game);
        // $_SESSION['game_instance'] = $serializedGame;
    ?>
</html>