<?php

namespace App\Controller;

use Exception;
use App\Repository\livresRepository;

class LivreController {
    private $repositoryLivres;

    public function __construct()
    {
        $this->repositoryLivres = new livresRepository;
        $this->repositoryLivres->chargementLivresBdd();
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

    public function validationAjoutLivre() {
        $image = $_FILES['image'];
        $repertoire = "images/";   //là ou l'on souhaite stocker nos images dans le serveur
        $nomImage = $this->ajouterImage($image, $repertoire);
        $this->repositoryLivres->ajouterLivreBdd($_POST['titre'], $_POST['nbre-de-pages'], $_POST['text_alternatif'], $nomImage);
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
            $imageActuelle = $this->ajouterImage($imageUpload, "images/");
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

    public function ajouterImage($image, $repertoire) {
        if ($image['size'] === 0) {
            throw new Exception('Vous devez uploader une image');
        }
        if (!file_exists($repertoire)) mkdir($repertoire, 0777);

        $filename = uniqid() . "-" . $image['name'];
        $target = $repertoire . $filename;

        if (!getimagesize($image['tmp_name'])) // si on obtient pas la taille du fichier, ce n'est pas une image
            throw new Exception('Vous devez uploader une image');

        $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION)); // récupere le format de l'image (tah le gros format)
        $extensionsTab = ['png', 'wedp', 'jpeg'];

        if (!in_array($extension, $extensionsTab))
            throw new Exception("Extension non autorisée => ['png', 'wedp', 'jpeg']"); // les formats autorisés

        if ($image['size'] > 4000000) // 4Mo
            throw new Exception("Fichier trop volumineux : max 4Mo"); // limite de taille pour l'image

        if (!move_uploaded_file($image['tmp_name'], $target))
            throw new Exception("Le transfert de l'image a échoué");
        else
            return $filename;
    }
}
