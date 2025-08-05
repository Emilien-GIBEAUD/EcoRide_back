<?php

namespace App\DataFixtures;

use App\Entity\{Brand, Color, Energy, Model};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FeedingFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['feed'];
    }

    public function load(ObjectManager $manager): void
    {
        // $colors = [
        //     "Blanc",
        //     "Bleu",
        //     "Gris",
        //     "Jaune",
        //     "Marron",
        //     "Noir",
        //     "Orange",
        //     "Rose",
        //     "Rouge",
        //     "Vert",
        //     "Violet",
        //     "Autre",
        // ];
        // foreach ($colors as $colorName) {
        //     $color = new Color();
        //     $color->setColor($colorName);
        //     $manager->persist($color);
        // }

        // $energies = [
        //     "Diesel",
        //     "Electrique",
        //     "Essence",
        //     "GPL",
        //     "Hybride",
        //     "Hydrogène",
        //     "A pédale !",
        //     "Autre",
        // ];
        // foreach ($energies as $energieName) {
        //     $energy = new Energy();
        //     $energy->setEnergy($energieName);
        //     $manager->persist($energy);
        // }

        $manager->flush();
    }
}
