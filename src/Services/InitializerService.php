<?php
namespace App\Services;

use App\Entity\User;
use App\Entity\SittingGenerale;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InitializerService
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function initialize()
    {
        $userRepository = $this->entityManager->getRepository(Users::class);
        $adminUser = $userRepository->findOneBy(['roles' => ['ROLE_ADMIN']]);

        if (!$adminUser) {
            $adminUser = new Users();
            $adminUser->setName('admin');
            $adminUser->setFirstName('Admin');
            $adminUser->setEmail('admin@example.com');
            $adminUser->setRoles(['ROLE_ADMIN']);
            $hashedPassword = $this->passwordEncoder->hashPassword($adminUser, 'admin123');
                $adminUser->setPassword($hashedPassword);
           
            $this->entityManager->persist($adminUser);
            $this->entityManager->flush();
        }

        $sittingGeneraleRepository = $this->entityManager->getRepository(SittingGenerale::class);
        $sittingGenerale = $sittingGeneraleRepository->find(1);

        if (!$sittingGenerale) {
            
            $sittingGenerale = new SittingGenerale();
            
            $this->entityManager->persist($sittingGenerale);
            $this->entityManager->flush();
        }
    }
}
