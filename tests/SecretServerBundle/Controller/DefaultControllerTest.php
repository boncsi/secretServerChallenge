<?php

namespace SecretServerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SecretServerBundle\Entity\Secret;
use SecretServerBundle\Service\SecretService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        static::bootKernel();

        $entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $secretItem    = new Secret();

        $secretItem->setRemainingViews(10);
        $secretItem->setHash('asddewerfsdfewr2342bfdfgb46');
        $secretItem->setExpiresAt(200);
        $secretItem->setCreatedAt(new \DateTime());
        $secretItem->setSecret('Secret string');

        $secretRepository = $this->createMock(ObjectRepository::class);
        $secretRepository->expects($this->any())
            ->method('find')
            ->willReturn($secretItem);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($secretRepository);

        $secretService = new SecretService($entityManager);
        $result        = $secretService->getFilledData($secretItem);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result['hash']);
        $this->assertNotEmpty($result['secretText']);
        $this->assertNotEmpty($result['createdAt']);
        $this->assertNotEmpty($result['expiresAt']);
        $this->assertNotEmpty($result['remainingViews']);
    }
}
