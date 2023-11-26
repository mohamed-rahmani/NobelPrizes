<?php

class Controller_set extends Controller {

    public function action_default() {
        $this->action_form_add();
    }
    
    public function action_form_add() {
        $m = Model::getModel();
    
        $categories = $m->getCategories();

        $data = [
            "category" => $categories
        ];

        $this->render('form_add', $data);
    }

    public function action_add() {
        $m = Model::getModel();

        if(!isset($_POST['name']) &&
        !isset($_POST['category']) &&
        !isset($_POST['year']) ) {
            exit;
        }

        $data=check_data();
        if($data===false) {    
            $this->action_error('Vérifier si le nom, la catégorie ou l\'année sont bien écrit.');
        }
        $nb_personne = $m->addNobelPrize($data);
        $this->action_error("Le prix nobel a été ajouté !");
    }

    public function action_remove() {
        $m = Model::getModel();

        if(!isset($_GET['id'])) {
            $this->action_error("Il n'y a pas d'identifiant !");
            die;
        }
        if($m->isInDataBase($_GET['id']) == false) {
            $this->action_error("L'identifiant n'existe pas !");
        }
        else {
            $m->removeNobelPrize($_GET['id']);
            $this->action_error("L'identifiant a été supprimer !");
        }
    }

    public function action_update() {
        $m = Model::getModel();

        if(isset($_POST['id'])) {
            if(!preg_match('/^[0-9]+$/',$_POST['id'])) {
                $this->action_error("Id non valide");
            }
        }

        if(isset($_POST['name'])) {
            if(!preg_match('/.+/',trim($_POST['name']))) {
                $this->action_error("Name non valide");
            }
        }

        if(isset($_POST['category'])) {
            if(!preg_match('/.+/', trim($_POST['category']))) {
                $this->action_error('Category non valide');
            }
        }

        if(isset($_POST['year'])) {
            if(!preg_match('/.+/',trim($_POST['year']))) {
                $this->action_error("Year non valide");
            }
        }

        if(!isset($_POST['birthdate'])) {
            $_POST['birthdate'] = null;
        }

        if(!isset($_POST['birthplace'])) {
            $_POST['birthplace'] = null;
        }

        if(!isset($_POST['county'])) {
            $_POST['county'] = null;
        } 

        if(!isset($_POST['motivation'])) {
            $_POST['motivation'] = null;
        }

        $m->updateNobelPrize($_POST);
        $this->action_error("Base de donné mise à jour !");
    }

    public function action_form_update() {
        $m = Model::getModel();

        if(!isset($_GET['id'])) {
            $this->action_error("L'identifiant n'existe pas !");
            die;
        }
        if($m->isInDataBase($_GET['id']) == false) {
            $this->action_error("L'identifiant n'existe pas !");
            die;
        }

        $data = [
            'tab' => $m->getNobelPrizeInformations($_GET['id']),
            'category' => $m->getCategories()
        ];
        $this->render('form_update',$data);

    }
    
    protected function render($vue, $data = []) {
        extract($data);
    
        $file_name = "Views/view_" . $vue . ".php";
        if(file_exists($file_name)) {
            require $file_name;
        }
        else {
            $this->action_error("La vue n'existe pas !");
        }
        die();
    }
    
    protected function action_error($message = '')
    {
        $data = [
            'title' => "Error",
            'message' => $message,
        ];
        $this->render("message", $data);
    }

}

?>