<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Services;

use OpenClassrooms\Bundle\OneSkyBundle\Services\Impl\LanguageServiceImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Services\LanguageService;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Gateways\InMemoryLanguageGateway;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub2;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ProjectsStub;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageServiceImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LanguageService
     */
    private $service;

    /**
     * @test
     */
    public function WithoutLocales_getLanguage()
    {
        $languages = $this->service->getLanguages(ProjectsStub::$projects[1], ProjectsStub::$projects[1]['locales']);
        $this->assertEquals([ProjectsStub::$projects[1]["id"] => [new LanguageStub1(), new LanguageStub2()]], $languages);
    }

    /**
     * @test
     */
    public function getLanguage()
    {
        $languages = $this->service->getLanguages(ProjectsStub::$projects[1], [LanguageStub1::LOCALE]);
        $this->assertEquals([ProjectsStub::$projects[1]["id"] => [new LanguageStub1()]], $languages);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->service = new LanguageServiceImpl();
        $this->service->setLanguageGateway(
            new InMemoryLanguageGateway(
                [ProjectsStub::$projects[1]["id"] => [LanguageStub1::LOCALE => new LanguageStub1(), LanguageStub2::LOCALE => new LanguageStub2()]]
            )
        );
    }
}
