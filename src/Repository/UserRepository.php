<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($registry, User::class);
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function create(string $email, string $name, string$userName, string $password, array $roles): array
    {
        try {

            $user = new User();
            $encodePassword = $this->userPasswordHasher->hashPassword($user, $password);
            $user->setUserName($userName);
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword($encodePassword);
            $user->setRoles($roles);

            $this->_em->persist($user);
            $this->_em->flush();


            return [
                'success' => true,
                'user' => $user,
                'msg' => "Administrador creado exitosamente"
            ];


        } catch (\Exception $exception) {

            return [
                'success' => false,
                'msg' => $exception->getMessage()
            ];
        }

    }

    public function userList(int $page = 1, int $limit = 10):array{

        $query = $this->createQueryBuilder('user')
//            ->where('')
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit)
            ->orderBy('user.id', 'DESC');

        $paginator = new Paginator($query->getQuery());

        $totalUsers = count($paginator);
        $totalPages = ceil($totalUsers / $limit);
        $resultUsers = $paginator->getQuery()->getResult();

        return array(
            "items" => $resultUsers,
            "countItems" => count($resultUsers),
            "totalItems" => $totalUsers,
            "currentPage" => $page,
            "totalPages" => intval($totalPages)
        );

    }

    public function createAdmin(): array
    {
        try {
            $admin = $this->findOneBy(['email' => 'admin@app.master.com']);

            if (!empty($admin)) {
                $this->upgradePassword($admin, $this->userPasswordHasher->hashPassword($admin, 'test'));

                return [
                    'success' => true,
                    'msg' => "Password del usuario 'admin' reseteada exitosamente"
                ];
            }

            $this->create('admin@app.master.com', 'admin', 'admin' , 'test', ['COMPANY']);

            return [
                'success' => true,
                'msg' => "Usuario 'admin' creado exitosamente"
            ];

        } catch (\Exception $exception) {

            return [
                'success' => false,
                'msg' => $exception->getMessage()
            ];
        }


    }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
