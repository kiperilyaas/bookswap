<?php 
defined("APP") or die("ACESSO NEGATO");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="index.php?action=check&table=Login">
        <input type="email" name="email">
        <input type="password" name="password">
        <button type="submit">Login</button>
    </form>
</body>
</html>