<?php

namespace App\Service;

class ValidationDonnees
{

    private array $erreurs = [];

    public function valider(array $regles, array $datas)
    {
        foreach ($regles as $key => $regleTab) {
            if (array_key_exists($key, $datas)) {
                foreach ($regleTab as $regle) {
                    switch ($regle) {
                        case 'required':
                            $this->required($key, $datas[$key]);
                            break;
                        case substr($regle, 0, 5) === 'match':
                            $this->match($key, $datas[$key], $regle);
                            break;
                    }
                }
            }
        }
        return $this->getErreurs();
    }
    public function required(string $name, string|int|bool $data)
    {
        $value = trim($data);
        if (!isset($value) || empty($value) || is_null($value)) {
            $this->erreurs[$name][] = "Le champ {$name} est requis!";
        }
    }

    public function match(string $name, string|int|bool $data, string $regle) {
        $pattern = substr($regle, 6);
        if (!preg_match($pattern, $data)){
        switch ($name) {
            case 'titre':
                $this->erreurs[$name][] = "Le champs {$name} doit commencer par une lettre majuscule, contenir minimum 3 lettres et maximum 20 lettres, espaces et '-' autorités";
            break;
        }
    }
}
    

    /**
     * Get the value of erreurs
     */
    public function getErreurs()
    {
        return $this->erreurs;
    }
}
