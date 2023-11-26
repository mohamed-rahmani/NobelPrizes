<?php
require_once 'view_begin.php';


if($tab != false) {
    $s = '
        <form action="?controller=set&action=update" method="post">
        <p>
            ID<br><input type="hidden" name="id" value="'.$tab['id'].'"><br>
            Year<br><input name="year" value="'.$tab['year'].'"><br>
            Category<br>
                
    ';

    foreach($category as $cle => $val) {
        if($val == $tab['category']) {
            $s .= '<input type="radio" name="category" value="'.$tab['category'].'" checked>'.$val.'<br>';
        }
        $s .= '<input type="radio" name="category" value="'.$val.'" >'.$val.'<br>';
    }

    $s .= '
            Name<br><input name="name" value="'.$tab['name'].'"><br>
            Birthdate<br><input name="birthdate" value="'.$tab['birthdate'].'"><br>
            Birthplace<br><input name="birthplace" value="'.$tab['birthplace'].'"><br>
            County<br><input name="county" value="'.$tab['county'].'"><br>
            Motivation<br><textarea name="motivation" cols="70" rows="10">'.$tab['motivation'].'</textarea><br>
            <button type="submit">Update data base</button>
        </p>
        </form>
    ';
    echo $s;
}
else {exit;}
require_once 'view_end.php';

?>