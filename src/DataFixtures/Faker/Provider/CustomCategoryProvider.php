<?php

namespace App\DataFixtures\Faker\Provider;

use \Faker\Provider\Base as BaseProvider;

class CustomCategoryProvider extends BaseProvider
{
    public function customCategory()
    {

        $categoryArray = ['Roman', 'Policier', 'Science-fiction', 'Nouvelles', 'Contes', 'Poésie', 'Philosophie',
            'Psychologie', 'Biologie', 'Manga', 'Bande Déssinée', 'Comics'];

        return $categoryArray[array_rand($categoryArray, 1)];
    }

}