<?php
declare(strict_types=1);

namespace App\DataFixtures;
use App\Entity\Language;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    /**@var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager) {
        if($_SERVER['APP_ENV'] !== 'dev') {
            return;
        }

        /** @var Language $english */
        $english = $manager->getRepository(Language::class)
            ->findOneBy(['code' => 'en']);

        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'test123'));
        $user->setEmail('user@example.com');
        $user->setName('John Doe');
        $user->setUsername('John Doe');
        $user->setLanguage($english);

        $partner = new User();
        $partner->setPassword($this->passwordEncoder->encodePassword($user, 'test123'));
        $partner->setEmail('partner@example.com');
        $partner->setName('Jane Doe');
        $partner->setUsername('Jane Doe');
        $partner->setIsPartner(true);
        $partner->setLanguage($english);

        $manager->persist($user);
        $manager->persist($partner);
        $manager->flush();
    }
}