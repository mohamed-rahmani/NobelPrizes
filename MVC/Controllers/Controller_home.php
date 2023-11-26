<?php

class Controller_home extends Controller {

    public function action_default() {
        $this->action_home();
    }

    public function action_home() {
        $m = Model::getModel();

        $data = [
            "nb" => $m->getNbNobelPrizes()
        ];
        
        $this->render('home',$data);
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