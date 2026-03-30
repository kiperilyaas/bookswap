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
    <form action="index.php?table=login&action=insert" method="post">

        <label for="">name</label>
        <input type="text" name="name" id="">

        <label for="">surname</label>
        <input type="text" name="surname" id="">

        <label for="">class sezione</label>
        <input type="text" name="class" id="">

        <label for="">email</label>
        <input type="text" name="email" id="">

        <label for="">password</label>
        <input type="text" name="password" id="">

        <button type="submit">Registrati</button>
    </form>
</body>
</html>