<?php require_once 'view_begin.php' ?>

<table>
    <tr><td>Name</td><td>Category</td><td>Year</td><td class='sansBordure'>Remove</td><td class='sansBordure'>Update</td></tr>
    <?php

    foreach($list as $cle => $valeur) {
        echo "  <tr><td><a href='?controller=list&action=informations&id=".$list[$cle]['id']."'>".$list[$cle]['name']."</a></td>
                <td>".$list[$cle]['category']. "</td><td>".$list[$cle]['year']."</td>
                <td class='sansBordure'><a href='?controller=set&action=remove&id=".$list[$cle]['id']."'><img class='icon' src='Content/img/remove-icon.png'></img></a></td>
                <td class='sansBordure'><a href='?controller=set&action=form_update&id=".$list[$cle]['id']."'><img class='icon' src='Content/img/edit-icon.png'></img></a></td>
                </tr>";
    }
    ?>
</table>
