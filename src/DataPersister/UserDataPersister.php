<?php
namespace App\DataPersister;


use App\Entity\User;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;





class UserDataPersister implements DataPersisterInterface
{
private UserPasswordHasherInterface $passwordHasher;

private EntityManagerInterface $entityManager;

private ?TokenInterface $token;
    
public function __construct(UserPasswordHasherInterface $passwordHasher,
EntityManagerInterface $entityManager,
TokenStorageInterface $tokenStorage,Mailer $mail)
{
$this->passwordHasher= $passwordHasher;
$this->entityManager = $entityManager;
$this->mail =$mail;
// $this->token = $tokenStorage->getToken();
}
public function supports($data): bool
{
return $data instanceof User;
}
/**
* @param User $data
*/
public function persist($data)
{
$hashedPassword = $this->passwordHasher->hashPassword(
$data,
'passer'
);
$data->setPassword($hashedPassword);
$this->entityManager->persist($data);
$this->entityManager->flush();
$this->mail->sendEmail($data);
}
public function remove($data)
{
$this->entityManager->remove($data);
$this->entityManager->flush();
}
}