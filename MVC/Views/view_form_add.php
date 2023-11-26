<?php
require_once 'view_begin.php';

$formulaire = '
    <form action="?controller=set&action=add" method="post">
        <p>
            Name:<br><input type="text" name="name"><br>
            <br>Year:<br><input type="text" name="year"><br>
            <br>Birth Date:<br><input type="text" name="birthdate"><br>
            <br>Birth Place:<br><input type="text" name="birthplace"><br>
            <br>County:<br><input type="text" name="county"><br>
            <br>
';
     
foreach($category as $cle => $val) {
    $formulaire .= '
        <input type="radio" name="category" value="'.$val.'">'.$val.'<br>
    ';
}

$formulaire .= '
            <br><textarea name="motivation" cols="70" rows="10"></textarea>
        </p>
        <button type="submit">Add in database</button>
    </form>
';

echo $formulaire;

require_once 'view_end.php';
?>