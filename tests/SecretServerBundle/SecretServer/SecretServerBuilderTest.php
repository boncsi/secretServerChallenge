<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use SecretServerBundle\Entity\Secret;
use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\Service\SecretService;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

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

        $this->_entityManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

   public function testGetFilledData()
    {
        $secretServerEntity = $this->_secretServerEntity;

        $secretServerEntity->expects($this->once())
            ->method('getSecret')
            ->will($this->returnValue('Secret string'));

        $secretServerEntity->expects($this->once())
            ->method('getCreatedAt')
            ->will($this->returnValue(new \DateTime()));

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
            ->method('getSecretByHash')
            ->will($this->returnValue($this->_secretServerEntity));

        $secretServerRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($this->_secretServerEntity));

        $entityManager = $this->_entityManager;
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($secretServerRepository));

        $secretService = new SecretService($entityManager);
        $secretItem    = $secretService->getSecretByHash('asddewerfsdfewr2342bfdfgb46');
        $result        = $secretService->getFilledData($secretItem);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result['hash']);
        $this->assertNotEmpty($result['secretText']);
        $this->assertNotEmpty($result['createdAt']);
        $this->assertNotEmpty($result['expiresAt']);
        $this->assertNotEmpty($result['remainingViews']);
    }
}
