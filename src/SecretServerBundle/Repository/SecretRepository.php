<?php

namespace SecretServerBundle\Repository;

use SecretServerBundle\Entity\Secret;

/**
 * SecretRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SecretRepository extends \Doctrine\ORM\EntityRepository
{
    public function addNew(array $post)
    {
        $queryBuilder  = $this->createQueryBuilder('*');
        $entityManager = $queryBuilder->getEntityManager();
        $secretEntity  = new Secret();
        $createAtDate  = new \DateTime();

        $secretEntity->setSecret(empty($post['secret']) ? NULL : $post['secret']);
        $secretEntity->setHash(uniqid('se-' . md5(rand(1, 99999999)) . '-'));
        $secretEntity->setRemainingViews($post['remainingViews']);
        $secretEntity->setCreatedAt($createAtDate);
        $secretEntity->setExpiresAt($post['expiresAt']);

        $entityManager->persist($secretEntity);
        $entityManager->flush();

        return $secretEntity;
    }

    public function getSecretByHash($hash) {
        return $this->findOneBy(["hash" => $hash]);
    }

    public function reduceRemainingViewsCount(Secret $secretEntity)
    {
        $actualRemainingViewsCount = $secretEntity->getRemainingViews();

        if ($actualRemainingViewsCount == 1) {
            $secretEntity->setRemainingViews(-1);
        } elseif ($actualRemainingViewsCount > 1) {
            $secretEntity->setRemainingViews(--$actualRemainingViewsCount);
        }

        $queryBuilder  = $this->createQueryBuilder('*');
        $entityManager = $queryBuilder->getEntityManager();

        $entityManager->persist($secretEntity);
        $entityManager->flush();

        return $secretEntity;
    }

    public function getAllSecretItem()
    {
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->getQuery();

        return $query->getResult();
    }
}
