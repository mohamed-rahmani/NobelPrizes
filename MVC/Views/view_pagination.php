<?php require 'view_list_nobel.php'?>

<div class='listePages'>
    <p>Pages :<p>
    <tr>
        <p>
            <a href=<?="'?controller=list&action=pagination&start=".($_GET['start']-1)."'" ?> class='lienStart'><td><img class='icone' src="Content/img/previous-icon.png"></td></a>

            <?php
            $list_pages = "";
            for($i = 1;$i<sizeof($list);$i++) {
                if($_GET['start'] == $i) {
                    $list_pages .= "<a href='?controller=list&action=pagination&start=".$i."' class='active'><td> ".$i." </td></a>";
                }
                else{$list_pages .= "<a href='?controller=list&action=pagination&start=".$i."'><td> ".$i." </td></a>";}
            }

            echo $list_pages;
            ?>
            <a href=<?= "'?controller=list&action=pagination&start=".($_GET['start']+1)."'" ?> class='lienStart'><td><img class='icone' src="Content/img/next-icon.png"></td></a>
        </p>
    </tr>
</div>

<?php require_once 'view_end.php' ?>