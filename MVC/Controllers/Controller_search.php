<?php

class Controller_search extends Controller {

    /**
     * Action par défaut du contrôleur (à définir dans les classes filles)
     */
    public function action_default() {
        $this->action_form();
    }

    public function action_form() {
        $form = [ 
            'form_find' => "<form action='?controller=search&action=pagination' method='post'>
                                Name contains :<br>
                                <input type='text' name='name'><br><br>
                                Year :<br>
                                <select name='sign'>
                                    <option value='<='><=
                                    <option value='>='>>=
                                    <option value='='>==
                                </select>
                                <input type='text' name='year'><br><br>
                                <input type='checkbox' name='categories[]' value='chemistry'>chemistry<br>
                                <input type='checkbox' name='categories[]' value='economics'>economics<br>
                                <input type='checkbox' name='categories[]' value='literature'>literature<br>
                                <input type='checkbox' name='categories[]' value='medicine'>medicine<br>
                                <input type='checkbox' name='categories[]' value='peace'>peace<br>
                                <input type='checkbox' name='categories[]' value='physics'>physics<br>
                                <br>
                                <button type='submit'>Search</button>
                            </form>"
        ];
        $this->render('form',$form);
    }

    public function action_pagination() {
        $m = Model::getModel();

        $resultat = [
            'list' => $m->findNobelPrizes($_POST, 0, $m->nbFindNobelPrizes($_POST))
        ];

        $this->render('list_nobel',$resultat);
    }

    /**
     * Affiche la vue
     * @param string $vue nom de la vue
     * @param array $data tableau contenant les données à passer à la vue
     * @return aucun
     */
    protected function render($vue, $data = [])
    {

        //On extrait les données à afficher
        extract($data);

        //On teste si la vue existe
        $file_name = "Views/view_" . $vue . '.php';
        if (file_exists($file_name)) {
            //Si oui, on l'affiche
            include $file_name;
        } else {
            //Sinon, on affiche la page d'->action_error
            $this->action_error("La vue n'existe pas !");
        }
        // Pour terminer le script
        die();
    }

    /**
     * Méthode affichant une page d'erreur
     * @param string $message Message d'erreur à afficher
     * @return aucun
     */
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