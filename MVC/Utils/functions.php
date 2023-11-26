<?php


/**
 * Fonction échappant les caractères html dans $message
 * @param string $message chaîne à échapper
 * @return string chaîne échappée
 */
function e($message) {
    return htmlspecialchars($message, ENT_QUOTES);
}

function check_data() {

    $infos = [];
    if(preg_match('/.+/',trim($_POST['name']))) {
        if(preg_match('/.+/',trim($_POST['category']))) {
            if(preg_match('/^[0-9]{4}$/',$_POST['year'])) {
                $infos['name'] = $_POST['name'];
                $infos['category'] = $_POST['category'];
                $infos['year'] = $_POST['year'];
                if(preg_match('/^[0-9]{8}$/',$_POST['birthdate'])) {
                    $infos['birthdate'] = $_POST['birthdate'];
                }
                else{$infos['birthdate'] = null;}

                if(preg_match('/[a-zA-Z]+/',$_POST['birthplace'])) {
                    $infos['birthplace'] = $_POST['birthplace'];
                }
                else{$infos['birthplace'] = null;}

                if(preg_match('/[a-zA-Z]+/',$_POST['county'])) {
                    $infos['county'] = $_POST['county'];
                }
                else{$infos['county'] = null;}

                if(preg_match('/./',$_POST['motivation'])) {
                    $infos['motivation'] = $_POST['motivation'];
                }
                else{$infos['motivation'] = null;}
                return $infos;
            }
            else {return false;}
        }
        else {return false;}
    }
    else {return false;}
}

function liste_page() {
    die();
};

?>