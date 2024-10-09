<?php

// use App\Models\Livre as ModelsLivre;
// use App\Repository\livresRepository;


// $repositoryLivres = new livresRepository;
// $repositoryLivres->chargementLivresBdd();
// $l1 = new ModelsLivre(1, "Toubou Le Bonobo", 667, "toubo.jpg", "Toubo la météo");
// $repositoryLivres->ajouterLivre($l1);
// // $l2 = new ModelsLivre(2, "Le dev fou", 5676, "le_dev_fou.png", "Image de couverture du livre le dev fou");
// $repositoryLivres->ajouterLivre($l2);
// // $l3 = new ModelsLivre(3, "Mon futur site web", 57, "mon-futur-site-web.png", "Image de couverture du livre mon futur site web");
// $repositoryLivres->ajouterLivre($l3);

// $livres = [$l1, $l2, $l3];

?>

<?php ob_start() ?>

<table class="table test-center">
    <tr class="table-dark">
        <th>Image</th>
        <th>Titre</th>
        <th>Nombre de pages</th>
        <th colspan="2">Actions</th>
    </tr>
    <?php foreach($livresTab as $livre) : ?>
    <tr>
        <td class="align-middle"><img src="images/<?= $livre->getUrlImage(); ?>" style="height: 60px;" ; alt="texte-alternatif"></td>
        <td class="align-middle">
            <a href="<?= SITE_URL ?>livres/l/<?=$livre->getId() ?>"> <?= $livre->getTitre()?></a>
        </td>
        <td class="align-middle"><?= $livre->getNbreDePages(); ?></td>
        <td class="align-middle"><a href="#" class="btn btn-warning">Modifier</a> </td>
        <td class="align-middle"><a href="#" class="btn btn-danger">Supprimer</a> </td>
    </tr>
    <?php endforeach ?>
</table>
<a href="#" class="btn btn-success d-block w-100">Ajouter</a>

<?php

$titre = "Livres";
$content = ob_get_clean();
require_once 'template.php';
