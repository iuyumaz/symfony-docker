<?php

namespace App\Repository;

use App\Entity\Device;
use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 *
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * @param $clientToken
     * @return float|int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findSubscriptionByClientToken($clientToken)
    {
        $qb = $this->createQueryBuilder('subscription');
        return $qb->leftJoin(Device::class, 'device', Join::WITH, 'subscription.device = device.id')
            ->andWhere($qb->expr()->eq('device.client_token', ':clientToken'))
            ->setParameter(':clientToken', $clientToken)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
