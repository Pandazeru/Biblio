<?php

namespace App\Controller;

use Exception;
use App\Repository\livresRepository;
use App\Service\Utils;
use App\Service\ValidationDonnees;

class LivreController {
    private livresRepository $repositoryLivres;
    private ValidationDonnees $validationDonnees;

    public function __construct()
    {
        $this->repositoryLivres = new livresRepository();
        $this->repositoryLivres->chargementLivresBdd();
        $this->validationDonnees = new ValidationDonnees();
    }

    public function afficherLivres() {
        $livresTab = $this->repositoryLivres->getLivres();
        $pasDeLivre = (count($livresTab) > 0) ? false : true;
        require "../app/Views/livres.php";
    }

    public function afficherUnlivre($idLivre) {
        $livre = $this->repositoryLivres->getLivreById($idLivre);
        ($livre!==null) ? require "../app/Views/afficherlivre.php" : require "../app/Views/error404.php";
    }

    public function ajouterLivre() {
        require '../app/Views/ajouterLivre.php';
    }

    public function validationAjoutLivre() 
    {
        $erreurs = $this->validationDonnees->valider([
            'titre'=> ['required', 'match:/^[A-Z][a-z\- ]+$/']
        ], $_POST);

        if (is_array($erreurs) && count($erreurs) > 0) {
            $_SESSION['erreurs'][] = $erreurs;
            header('location: ' . SITE_URL . 'livres/a');
            exit;
        }
        // $image = $_FILES['image'];
        // $repertoire = "images/";   //là ou l'on souhaite stocker nos images dans le serveur
        // $nomImage = Utils::ajouterImage($image, $repertoire);
        // $this->repositoryLivres->ajouterLivreBdd($_POST['titre'], $_POST['nbre-de-pages'], $_POST['text_alternatif'], $nomImage);
        header('location: ' . SITE_URL . 'livres');
    }

    public function modifierLivre($idLivre) {
        $livre = $this->repositoryLivres->getLivreById($idLivre);
        require '../app/Views/modifierLivre.php';
    }

    public function validationModifierLivre() {
        $idLivre = (int)$_POST['id_livre'];
        $imageActuelle = $this->repositoryLivres->getLivreById($idLivre)->getUrlImage(); // on récupere l'image du livre
        $imageUpload = $_FILES['image'];
        if ($imageUpload['size'] > 0) {
            unlink("images/$imageActuelle");
            $imageActuelle = Utils::ajouterImage($imageUpload, "images/");
        }
        $this->repositoryLivres->modificationLivreBdd($_POST['titre'], $_POST['nbre-de-pages'], $_POST['text_alternatif'], $imageActuelle, $idLivre);
        header('location: ' . SITE_URL . 'livres');
    }

    public function supprimerLivre($idLivre) {
        $nomImage = $this->repositoryLivres->getLivreById($idLivre)->getUrlImage();
        $filename = "images/$nomImage";
        if (file_exists($filename)) unlink("images/$nomImage");
        $this->repositoryLivres->supprimerLivreBdd($idLivre);
        header('location: ' . SITE_URL . 'livres');

    }
}
