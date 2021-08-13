<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

use SecretServerBundle\Entity\Secret;
use SecretServerBundle\Repository\SecretRepository;
use SecretServerBundle\Service\SecretService;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->_secretServerEntity = $this->getMockBuilder(Secret::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_secretServerRepository = $this->getMockBuilder(SecretRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

   public function testGetFilledData()
    {
        $now                    = new \DateTime();
        $secretServerEntity     = $this->_secretServerEntity;
        $secretServerRepository = $this->_secretServerRepository;

        /*Settings secret entity mock*/
        $secretServerEntity->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('Secret string'));

        $secretServerEntity->expects($this->any())
            ->method('getCreatedAt')
            ->will($this->returnValue($now));

        $secretServerEntity->expects($this->any())
            ->method('getExpiresAt')
            ->will($this->returnValue(0));

        $secretServerEntity->expects($this->any())
            ->method('getHash')
            ->will($this->returnValue('asddewerfsdfewr2342bfdfgb46'));

        $secretServerEntity->expects($this->any())
            ->method('getRemainingViews')
            ->will($this->returnValue(10));

        /*Settings secret repository mock*/
        $secretServerRepository->expects($this->any())
            ->method('findOneBy')
            ->will($this->returnValue($this->_secretServerEntity));

        $secretServerRepository->expects($this->any())
            ->method('getSecretByHash')
            ->will($this->returnValue($this->_secretServerEntity));

        $secretServerRepository->expects($this->any())
            ->method('reduceRemainingViewsCount')
            ->will($this->returnValue($this->_secretServerEntity));

        $secretService = new SecretService($secretServerRepository);
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
