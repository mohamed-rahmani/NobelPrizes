<?php require_once 'view_begin.php' ?>

<table>
    <tr>
        <td>Year</td><td>Category</td><td>Name</td><td>Birthdate</td><td>Birthplace</td><td>County</td><td>Motivation</td>
    </tr>
    <?php 
    if($year === null) {
        $year = '???';
    }
    if($category === null) {
        $category = '???';
    }
    if($name == null) {
        $name = '???';
    }
    if($birthdate === null) {
        $birthdate = '???';
    }
    if($birthplace === null) {
        $birthplace = '???';
    }
    if($county === null) {
        $county = '???';
    }
    if($motivation === null) {
        $motivation = '???';
    }
    
    echo "
    <tr>
        <td>$year</td><td>$category</td><td>$name</td><td>$birthdate</td><td>$birthplace</td><td>$county</td><td>$motivation</td>
    </tr>
    "; ?>
</table>

<?php require_once 'view_end.php' ?>