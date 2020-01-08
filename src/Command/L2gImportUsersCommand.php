<?php

namespace App\Command;

use App\Entity\Language;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class L2gImportUsersCommand extends Command
{
    protected static $defaultName = 'l2g:import-users';

    private $em;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure() {
        $this->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $english = $this->em->getRepository(Language::class)
            ->findOneBy(['code' => 'en']);

        $io = new SymfonyStyle($input, $output);

        $rawUsers = explode("\n", file_get_contents('users.txt'));

        foreach($rawUsers AS $email) {
            $username = substr($email, 0, strpos($email, '@'));
            $password = substr(md5(time() . rand(1, 100000)), 0, 8);

            $user = new User();
            $user->setLanguage($english);
            $user->setName($username);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setIsPartner(true);
            $user->setCreated(new \DateTimeImmutable());

            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

            $this->em->persist($user);

            $io->comment(sprintf('%s : %s', $email, $password));
        }

        $this->em->flush();

        $io->success(sprintf('You created %s new users', count($rawUsers)));
    }
}
