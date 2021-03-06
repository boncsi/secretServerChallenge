<?php

namespace SecretServerBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\Repository\SecretRepositoryInterface;
use SecretServerBundle\SecretInDDD\Domain\Util\Model\SecretModelInterface;
use SecretServerBundle\Entity\Secret;
use SecretServerBundle\SecretInDDD\Domain\VO\SecretVO;
use DateTimeImmutable;

/**
 * SecretRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SecretRepository extends \Doctrine\ORM\EntityRepository implements SecretRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, $entityManager->getClassMetadata(Secret::class));
    }

    public function addNew(SecretVO $secretVO) : SecretModelInterface
    {
        $queryBuilder  = $this->createQueryBuilder('*');
        $entityManager = $queryBuilder->getEntityManager();
        $secretEntity  = new Secret();

        $secretEntity->setSecret($secretVO->getSecret()->getValue());
        $secretEntity->setHash($secretVO->getHash()->getValue());
        $secretEntity->setRemainingViews($secretVO->getRemainingViews()->getValue());
        $secretEntity->setCreatedAt(new DateTimeImmutable($secretVO->getSecretCreatedAt()->getValue()));
        $secretEntity->setExpiresAt($secretVO->getSecretExpiresAt()->getValue());

        $entityManager->persist($secretEntity);
        $entityManager->flush();

        return $secretEntity;
    }

    public function save(SecretVO $secretVO) : SecretModelInterface
    {
        /** @var $secretEntity SecretModelInterface */
        $queryBuilder  = $this->createQueryBuilder('*');
        $entityManager = $queryBuilder->getEntityManager();
        $secretEntity  = $this->find($secretVO->getId()->getValue());

        $secretEntity->setSecret($secretVO->getSecret()->getValue());
        $secretEntity->setHash($secretVO->getHash()->getValue());
        $secretEntity->setRemainingViews($secretVO->getRemainingViews()->getValue());
        $secretEntity->setCreatedAt(new DateTimeImmutable($secretVO->getSecretCreatedAt()->getValue()));
        $secretEntity->setExpiresAt($secretVO->getSecretExpiresAt()->getValue());

        $entityManager->persist($secretEntity);
        $entityManager->flush();

        return $secretEntity;
    }

    public function getSecretByHash($hash) : Secret
    {
        /** @var $this Secret */
        return $this->findOneBy(["hash" => $hash]);
    }

    public function getAllSecretItem() : array
    {
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->getQuery();

        return $query->getArrayResult();
    }
}
