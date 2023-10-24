<?php
    session_start();
    include("index.php");
    session_destroy();
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
    <body class="body-background">
        <header class="welcome-header">
            <h1 class="welcome-h1">Welcome to the game</h1>
            <h3 class="info">Enter your information below</h3>
            <form class="main-form" action="/game.php" method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" minlength="3" required>
                <br>
                <label for="email">Email:</label>
                <input type="text" name="email" $pattern = '/^[a-zA-Z0-9_+.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/' title="Enter a valid email address" required>
                <br>
                <input type="submit" value="Play Game!">
            </form>
        </header>
    </body>
</html>