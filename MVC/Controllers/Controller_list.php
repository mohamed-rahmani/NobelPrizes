<?php

class Controller_list extends Controller {

    public function action_default() {
        $this->action_last();
    }

    public function action_last() {
        $m = Model::getModel();

        $last25 = [
            "list" => $m->getNobelPrizesWithLimit(0,25)
        ];
        
        $this->render('last',$last25);
    }

    public function action_informations() {
        $m = Model::getModel();

        if(isset($_GET['id']) && preg_match('/^[0-9]+$/',$_GET['id'])) {
            if($m->isInDataBase($_GET['id'])) {
                $infos = $m->getNobelPrizeInformations($_GET['id']);
                $this->render('informations', $infos);
            }
            else {
                $this->action_error("L'identifiant n'est pas dans la base de donnée !");
            }
        }
        else {
            $this->action_error("L'identifiant renseigné n'est pas bon !");
        }
        die;
    }

    public function action_pagination() {
        $m = Model::getModel();

        if(!isset($_GET['start']) || !preg_match('/^[0-9]+$/',$_GET['start'])) {
            $_GET['start'] = 1;
        }
        $nb_nobels = $m->getNbNobelPrizes();

        $nb_page = intdiv($nb_nobels, 25);
        $i = 0;
        $compt_nb_page = 1;
        
        while ($compt_nb_page < $nb_page) {
            $pages[$compt_nb_page] = $m->getNobelPrizesWithLimit($i,25);
            $compt_nb_page++;
            $i = $i + 25;
        }
        if($_GET['start'] > $nb_page) {
            $this->action_error("La page n'existe pas !");
        }

        $page = [
            'list' => $pages[$_GET['start']]
        ];

        $this->render('pagination', $page);
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