<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Device>
 *
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /**
     * @param $value
     * @return Device|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUid($value): ?Device
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.uid = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $value
     * @return Device|null
     */
    public function findOneByClientToken($value): ?Device
    {
        return $this->findOneBy(['client_token' => $value]);
    }
}
