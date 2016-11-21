<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Services;

use OpenClassrooms\Bundle\OneSkyBundle\Services\Impl\LanguageServiceImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Services\LanguageService;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Gateways\InMemoryLanguageGateway;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub2;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageServiceImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LanguageService
     */
    private $service;

    const PROJECT_ID = 1;

    /**
     * @test
     */
    public function WithoutLocales_getLanguage()
    {
        $languages = $this->service->getLanguages(self::PROJECT_ID);
        $this->assertEquals([self::PROJECT_ID => [new LanguageStub2()]], $languages);
    }

    /**
     * @test
     */
    public function getLanguage()
    {
        $languages = $this->service->getLanguages(self::PROJECT_ID, [LanguageStub1::LOCALE, LanguageStub2::LOCALE]);
        $this->assertEquals([self::PROJECT_ID => [new LanguageStub1(), new LanguageStub2()]], $languages);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->service = new LanguageServiceImpl();
        $this->service->setLanguageGateway(
            new InMemoryLanguageGateway(
                [self::PROJECT_ID => [LanguageStub1::LOCALE => new LanguageStub1(), LanguageStub2::LOCALE => new LanguageStub2()]]
            )
        );
        $this->service->setRequestedLocales(['ja']);
    }
}
