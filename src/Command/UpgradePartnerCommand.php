<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

class UpgradePartnerCommand extends Command
{
    protected static $defaultName = 'l2g:upgrade-partner';
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Upgrade user to partner')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        if ($email) {
            $io->note(sprintf('You passed an argument: %s', $email));
        }

        $user = $this->em->getRepository(User::class)->findOneBy([
            'email' => $email
        ]);

        if($user === null) {
            $io->error('User not found');
            return;
        }

        $user->setIsPartner(true);
        $this->em->flush();

        $io->success(sprintf('Upgrade user %s to partner', $user->getEmail()));
    }
}
