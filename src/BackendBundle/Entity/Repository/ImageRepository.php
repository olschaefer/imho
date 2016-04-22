<?php

namespace BackendBundle\Entity\Repository;

use BackendBundle\Entity\Image;
use Doctrine\ORM\EntityRepository;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends EntityRepository
{
    public function countTotals()
    {
        $result = $this->getEntityManager()->createQuery("SELECT COUNT(i.id) as totalNumber, SUM(i.size) AS totalSize FROM ".Image::class." i")->getSingleResult();
        foreach ($result as $k => $v) {
            $result[$k] = (int)$v;
        }

        return $result;
    }

    public function getLatest($limit = null)
    {
        $em = $this->getEntityManager();
        return $em->createQueryBuilder()
                    ->select('i')
                    ->from(Image::class, 'i')
                    ->where('i.id = i.originalId')
                    ->orderBy('i.createdAt', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }
}