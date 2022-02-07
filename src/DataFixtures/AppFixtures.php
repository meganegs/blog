<?php

namespace App\DataFixtures;


use App\Entity\Articles;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{    
    private $UserPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->UserPasswordHasherInterface = $userPasswordHasherInterface;
    }
    
    protected $faker;

    public function load(ObjectManager $manager):void
    {
        $this->manager = $manager;
        $generator = Faker\Factory::create("fr_FR");
        for($i = 0 ; $i < 20 ; $i++)
        {
            $user = new User();
            $user
                ->setEmail($generator->email())
                ->setFirstName($generator->firstName())
                ->setLastName($generator->lastName())
                /*->setEmail($generator->email())*/
                //->setPassword("test");
                ->setPassWord(
                    $this->UserPasswordHasherInterface->hashPassword(
                        $user, "test"
                    )
                );
            $manager->persist($user);

            for($j = 0 ; $j < rand (10, 50) ; $j++)
            {
                $articles = new Articles();
                $articles
                    ->setImage($generator->imageUrl($width = 400, $height = 250))
                    ->setTitle($generator->sentence())
                    ->setContent($generator->text())
                    ->setCover($generator->imageUrl($width = 1280, $height = 420))
                    ->setStatus(
                       $generator->randomElement(['DRAFT', 'PUBLISHED', 'DELETED'])
                    )
                    //->setCreatedAt($generator->dateTimeBetween($startDate ='-1 year', $endDate = 'now'))
                    ->setUser($user);
                    $manager->persist($articles);
                
            }

            
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    
}
