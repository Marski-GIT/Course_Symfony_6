<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    protected PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    public function findAllPosts(int $page): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $dbQuery = $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->getQuery()->getResult();

        return $this->paginator->paginate($dbQuery, $page, 3);
    }

    public function findAllUserPosts(int $page, int $userId): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $dbQuery = $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->where('p.user = :id')
            ->setParameter('id', $userId)
            ->getQuery()
            ->getResult();

        return $this->paginator->paginate($dbQuery, $page, 3);
    }

    public function isLiked($authUser, $postId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id')
            ->andWhere('p.id = :postId')
            ->andWhere('usersThatLike.id = :authUser')
            ->innerJoin('p.usersThatLike', 'usersThatLike')
            ->setParameter('authUser', $authUser)
            ->setParameter('postId', $postId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function isDisliked($authUser, $postId): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id')
            ->andWhere('p.id = :postId')
            ->andWhere('usersThatDontLike.id = :authUser')
            ->innerJoin('p.usersThatDontLike', 'usersThatDontLike')
            ->setParameter('authUser', $authUser)
            ->setParameter('postId', $postId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

}
