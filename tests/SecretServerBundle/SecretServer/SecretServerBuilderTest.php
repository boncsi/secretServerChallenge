<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use SecretServerBundle\Entity\Secret;
use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\Service\SecretService;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class SecretServerBuilderTest extends TestCase
{
    /**
     * @var SecretRepository|MockObject
     */
    private $_secretServerRepository;

    /**
     * @var Secret|MockObject
     */
    private $_secretServerEntity;

    /**
     * @var ObjectManager|MockObject
     */
    private $_entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->_secretServerEntity = $this->getMockBuilder(Secret::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_secretServerRepository = $this->getMockBuilder(SecretRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

   public function testGetFilledData()
    {
        $secretServerEntity = $this->_secretServerEntity;

        $secretServerEntity->expects($this->once())
            ->method('getSecret')
            ->will($this->returnValue('Secret string'));

        $now = new \DateTime();

        $secretServerEntity->expects($this->once())
            ->method('getCreatedAt')
            ->will($this->returnValue($now));

        $secretServerEntity->expects($this->once())
            ->method('getExpiresAt')
            ->will($this->returnValue(200));

        $secretServerEntity->expects($this->once())
            ->method('getHash')
            ->will($this->returnValue('asddewerfsdfewr2342bfdfgb46'));

        $secretServerEntity->expects($this->once())
            ->method('getRemainingViews')
            ->will($this->returnValue(10));

        $secretServerRepository = $this->_secretServerRepository;

        $secretServerRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->_secretServerEntity));

        $secretServerRepository->expects($this->once())
            ->method('getSecretByHash')
            ->will($this->returnValue($this->_secretServerEntity));

        $entityManager = $this->_entityManager;
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($secretServerRepository));

        $secretService = new SecretService($entityManager);
        $secretItem    = $secretService->getSecretByHash('asddewerfsdfewr2342bfdfgb46');

        $this->assertIsArray($secretItem);

        $this->assertTrue(empty($secretItem) === FALSE);

        $this->assertNotEmpty($secretItem['hash'] ?? NULL);
        $this->assertNotEmpty($secretItem['secretText'] ?? NULL);
        $this->assertNotEmpty($secretItem['createdAt'] ?? NULL);
        $this->assertNotEmpty($secretItem['expiresAt'] ?? NULL);
        $this->assertNotEmpty($secretItem['remainingViews'] ?? NULL);
    }
}
