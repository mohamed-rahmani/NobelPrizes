<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {
        include "Utils/credentials.php";
        $this->bd = new PDO($dsn, $login);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET nameS 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne les 25 derniers prix nobels
     * @return [array] Contient les informations des prix nobel
     */
    public function getLast()
    {
        $req = $this->bd->prepare('SELECT * FROM nobels ORDER BY year DESC LIMIT 25');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Retourne le nombre de prix nobels dans la base de données
     * @return [int]
     */
    public function getNbNobelPrizes()
    {
        $req = $this->bd->prepare('SELECT COUNT(*) FROM nobels');
        $req->execute();
        $tab = $req->fetch(PDO::FETCH_NUM);
        return $tab[0];
    }

    /**
     * Retourne les prix nobels dans la base de données du ($offset+1)ème au ($offset + $limit) ème
     * @param [int] $offset Position de départ
     * @param [int] $limit Nombre de résultats retournés
     * @return [array] Contient les informations des prix nobel
     */
    public function getNobelPrizesWithLimit($offset = 0, $limit = 25)
    {
        $requete = $this->bd->prepare('Select * from nobels ORDER BY year DESC LIMIT :limit OFFSET :offset');
        $requete->bindValue(':limit', $limit, PDO::PARAM_INT);
        $requete->bindValue(':offset', $offset, PDO::PARAM_INT);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne un tableau contenant les informations du prix nobel (ou false s'il n'existe pas)
     * @param [int] $id identifian du prix Nobel
     * @return [array ou false] Tableau contenant les infos(id, year, name, category, birthdate, birthplace, county, motivation) ou false
     */
    public function getNobelPrizeInformations($id)
    {
        $requete = $this->bd->prepare('Select * from nobels WHERE id = :id');
        $requete->bindValue(':id', $id);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne true si la table nobels contient un prix nobel d'identifiant $id, false sinon
     * @param int $id identifiant du prix nobel
     * @return boolean
     */
    public function isInDataBase($id)
    {
        return $this->getNobelPrizeInformations($id) !== false;
    }

    /**
     * Retourne les catégories des prix nobels
     * @return [array] Tableau contenant les catégories (les valeurs sont les catégories, les clés les indices)
     */
    public function getCategories()
    {
        $requete = $this->bd->prepare('SELECT * FROM categories');
        $requete->execute();
        $reponse = [];
        while ($ligne = $requete->fetch(PDO::FETCH_ASSOC)) {
            $reponse[] = $ligne['category'];
        }
        return $reponse;
    }

    /**
     * Ajoute le prix Nobel passé en paramètre dans la base de données.
     * @return [boolean] retourne true si la personne a été ajoutée dans la base de données, et false sinon
     */
    public function addNobelPrize($infos)
    {
        //Préparation de la requête
        $requete = $this->bd->prepare('INSERT INTO nobels (year, category, name, birthdate, birthplace, county, motivation) VALUES (:year, :category, :name, :birthdate, :birthplace, :county, :motivation)');

        //Remplacement des marqueurs de place par les valeurs
        $marqueurs = ['year', 'category', 'name', 'birthdate', 'birthplace', 'county', 'motivation'];
        foreach ($marqueurs as $value) {
            $requete->bindValue(':' . $value, $infos[$value]);
        }

        //Exécution de la requête
        $requete->execute();

        return (bool) $requete->rowCount();
    }

    /**
     * Met à jour le prix Nobel passé en paramètre dans la base de données.
     * @return [boolean] retourne true si la personne a été ajoutée dans la base de données, et false sinon
     */
    public function updateNobelPrize($infos)
    {
        $requete = $this->bd->prepare('UPDATE nobels SET year = :year, category = :category, name = :name, birthdate = :birthdate, birthplace = :birthplace, county = :county, motivation = :motivation WHERE id = :id');

        //Remplacement des marqueurs de place par les valeurs
        $marqueurs = ['id', 'year', 'category', 'name', 'birthdate', 'birthplace', 'county', 'motivation'];

        foreach ($marqueurs as $value) {
            $requete->bindValue(':' . $value, $infos[$value]);
        }

        //Exécution de la requête
        $requete->execute();

        return (bool) $requete->rowCount();
    }

    /**
     * Retourne les prix nobels correspondant aux critères de recherche donnés par $filters
     * @param [array] $filters contient :
     *         - une clé name si le nom doit contenir $filters['name']
     *         - une clé year et une clé Sign si l'on doit retourner les prix nobels
     *           dont l'année est $filters['sign'] (<=, >=, =) à $filters['year']
     *         - une clé categories si la catégorie doit être dans l'ensemble de catégories données par $filters['categories']
     * @param integer $offset Position de départ
     * @param integer $limit Nombre de résultats retournés
     * @return [array] Tableaux de résultats
     */
    public function findNobelPrizes($filters, $offset = 0, $limit = 25)
    {
        $sql = 'SELECT * FROM nobels WHERE 1=1';
        $bv = [];

        if (isset($filters['name'])) {
            $sql .= ' AND name LIKE :name';
            $bv[] = [
                'marqueur' => ':name',
                'valeur' => '%' . $filters['name'] . '%',
                'type' => PDO::PARAM_STR,
            ];
        }

        if (isset($filters['year']) and isset($filters["sign"]) 
        and in_array($filters["sign"], ["<=", ">=", "="])) {
            $sql .= ' AND year ' . $filters['sign'] . ' :year';
            $bv[] = [
                'marqueur' => ':year',
                'valeur' => intval($filters['year']),
                'type' => PDO::PARAM_INT,
            ];
        }

        if (isset($filters['categories'])) {
            $nbc = count($filters['categories']);
            $sql .= ' AND category IN (:c0';
            for ($i = 1; $i < $nbc; $i++) {
                $sql .= ',:c' . $i;
            }
            $sql .= ')';
            foreach ($filters['categories'] as $key => $value) {
                $bv[] = [
                    'marqueur' => ':c' . $key,
                    'valeur' => $value,
                    'type' => PDO::PARAM_STR,
                ];
            }
        }

        $sql .= ' ORDER BY year DESC LIMIT :limit OFFSET :offset';
        $bv[] = [
            'marqueur' => ':limit',
            'valeur' => intval($limit),
            'type' => PDO::PARAM_INT,
        ];

        $bv[] = [
            'marqueur' => ':offset',
            'valeur' => intval($offset),
            'type' => PDO::PARAM_INT,
        ];

        //Exécution et renvoi des résultats
        $requete = $this->bd->prepare($sql);
        foreach ($bv as $value) {
            $requete->bindValue($value['marqueur'], $value['valeur'], $value['type']);
        }
        echo "</table>";
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne le nombre de prix nobels correspondant aux critères de recherche donnés par $filters
     * @param [array] $filters contient :
     *                           - une clé name si le nom doit contenir $filters['name']
     *                           - une clé year et une clé Sign si l'on doit retourner les prix nobels
     *                             dont l'année est $filters['sign'] (<=, >=, =) à $filters['year']
     *                           - une clé categories si la catégorie doit être dans l'ensemble de catégories données par $filters['categories']
     * @return [int] Nombre de prix nobels satisfaisant les critères de recherche
     */
    public function nbFindNobelPrizes($filters)
    {
        $sql = 'SELECT COUNT(*) FROM nobels WHERE 1=1';
        $bv = [];

        if (isset($filters['name'])) {
            $sql .= ' AND name LIKE :name';
            $bv[] = [
                'marqueur' => ':name',
                'valeur' => '%' . $filters['name'] . '%',
                'type' => PDO::PARAM_STR,
            ];
        }

        if (isset($filters['year']) and isset($filters['sign']) 
        and in_array($filters["sign"], ['<=', '>=', '=='])) {
            $sql .= ' AND year ' . $filters['sign'] . ' :year';
            $bv[] = [
                'marqueur' => ':year',
                'valeur' => intval($filters['year']),
                'type' => PDO::PARAM_INT,
            ];
        }

        if (isset($filters['categories'])) {
            $nbc = count($filters['categories']);
            $sql .= ' AND category IN (:c0';
            for ($i = 1; $i < $nbc; $i++) {
                $sql .= ',:c' . $i;
            }
            $sql .= ')';
            foreach ($filters['categories'] as $key => $value) {
                $bv[] = [
                    'marqueur' => ':c' . $key,
                    'valeur' => $value,
                    'type' => PDO::PARAM_STR,
                ];
            }
        }
        //Exécution et renvoi des résultats
        $requete = $this->bd->prepare($sql);
        foreach ($bv as $value) {
            $requete->bindValue($value['marqueur'], $value['valeur'], $value['type']);
        }
        $requete->execute();
        return $requete->fetch(PDO::FETCH_NUM)[0];
    }

    /**
     * Supprime le prix nobel dont l'identifiant est $id_np
     * @param [int] $id_np identifiant du prix nobel à supprimer
     * @return [boolean] retourne true si la personne a été supprimée de la base de données, et false sinon
     */
    public function removeNobelPrize($id_np)
    {
        $requete = $this->bd->prepare("DELETE FROM nobels WHERE id = :id");
        $requete->bindValue(':id', (int) $id_np, PDO::PARAM_INT);
        $requete->execute();
        return (bool) $requete->rowCount();
    }
}
