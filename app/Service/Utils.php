<?php

namespace App\Service;

use Exception;

class Utils
{
    public static function ajouterImage($image, $repertoire) {
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
