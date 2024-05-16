<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create-admin-user';
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates an admin user.')
            ->setHelp('This command allows you to create an admin user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Create the admin user
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setUsername('admin'); // Définit le champ `username`
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Admin user created with email: admin@example.com, username: admin, nom: Administrator, and password: adminpassword');

        return Command::SUCCESS;
    }
}
