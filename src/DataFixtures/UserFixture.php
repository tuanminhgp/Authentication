<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder= $passwordEncoder;
        
    }
    public function loadData(ObjectManager $manager)
    {
       /*     $this->createMany(User::class, 10, function(User $user,$count) use ($manager) {

                $user->setEmail(sprintf('minh%d@gmail.com', $count));
                $user->setFirstName($this->faker->firstName);

                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    '123456'

                ));
            });*/

        $this->createMany(User::class, 3, function(User $user,$count) use ($manager) {

            $user->setEmail(sprintf('admin%d@gmail.com', $count));
            $user->setFirstName($this->faker->firstName);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '123456'

            ));
        });

        $manager->flush();
    }
}
