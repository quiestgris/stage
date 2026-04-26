<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Tests\ORMPurger;

class DeleteImgTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $container = static::getContainer();
        $manager = $container->get(EntityManagerInterface::class);

        $purger = new ORMPurger($manager);
        $purger->purge();

        $fixture = new AppFixtures();
        $fixture->load($manager);

        $newAdmin = new Admin();
        $newAdmin->setEmail('testmail@gmail.com');
        $newAdmin->setPassword($hasher->hashPassword($newAdmin, "123"));
        
        $manager->persist($newAdmin);
        $manager->flush();

        $adminRepository = static::getContainer()->get(\App\Repository\AdminRepository::class);
        $admin = $adminRepository->findOneBy( ["email" => 'testmail@gmail.com']);

        $client->loginUser($admin);

        $sourcePath = $container->getParameter('kernel.project_dir') . '/public/tests/imgs/test.jpeg';

        $uploadedFile = new UploadedFile(
            $sourcePath,
            'test.jpeg',
            'image/png',
            null,
            true 
        );

        $crawler = $client->request('GET', '/admin-panel');

        $deleteBtns = $crawler->filter('.delete-btn');

        $deleteBtn = $deleteBtns->eq($deleteBtns[rand(0, $deleteBtns->count() - 1)]);

        $client->click($deleteBtn);

        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();

        $img = $manager->getRepository(Image::class)->findBySrcContains('/imgs/portfolio/test');
        $this->assertNotNull($img);
        $this->assertNotNull($img->getSrc(), "L'image doit être enregistrée en base.");

        $uploadDir = $container->getParameter('kernel.project_dir') . '/public';
        $this->assertFileExists($uploadDir . '/' . $img->getSrc());
    }
}
