<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use App\Entity\KeyWord;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // private UserPasswordHasherInterface $hasher;

    // public function __construct(UserPasswordHasherInterface $hasher)
    // {
    //     $this->hasher = $hasher;
    // }



    public function load(ObjectManager $manager): void
    {

        $image = new Image();
        $image->setSrc('/imgs/hero-section.jpeg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/previous-work-section-img1.jpg');

        $manager->persist($image);
        $image = new Image();
        $image->setSrc('/imgs/previous-work-section-img2.jpeg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/previous-work-section-img3.jpeg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/previous-work-section-img4.jpeg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/contact-section-img1.jpeg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/contact-section-img2.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/0-3.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/1-7.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/1.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/03.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/04.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/12.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/034.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/035.jpg');

        $manager->persist($image);

        $image = new Image();
        $image->setSrc('/imgs/portfolio/119.jpg');

        $manager->persist($image);

        $manager->flush();
    }
}
