<?php

namespace Gateways\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Gateways\Impl\LanguageGatewayImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageGateway;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\LanguageFactoryImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Language;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\LanguageStub2;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ProjectsStub;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\OneSky\Api\ClientMock;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageGatewayImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LanguageGateway
     */
    private $gateway;

    /**
     * @test
     * @expectedException \OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageException
     */
    public function ApiException_findLanguage_ThrowException()
    {
        ClientMock::$languagesContent = '{"meta": {"status": 400}}';
        $this->gateway->findLanguages([], ProjectsStub::$projects[1]);
    }

    /**
     * @test
     * @expectedException \OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageNotFoundException
     */
    public function NonExistingLanguage_findLanguages_ThrowException()
    {
        $this->gateway->findLanguages(['fr'], ProjectsStub::$projects[1]);
    }

    /**
     * @test
     */
    public function findLanguages()
    {
        $actualLanguages = $this->gateway->findLanguages([LanguageStub1::LOCALE, LanguageStub2::LOCALE], ProjectsStub::$projects[1]);
        $expectedLanguages = [new LanguageStub1(), new LanguageStub2()];
        $this->assertEquals(LanguageGateway::LANGUAGES_METHOD, ClientMock::$action);
        $this->assertEquals(['project_id' => 1], ClientMock::$parameters);
        /** @var Language $expectedLanguage */
        foreach ($expectedLanguages as $key => $expectedLanguage) {
            /** @var Language $actualLanguage */
            $actualLanguage = $actualLanguages[$key];
            $this->assertEquals($expectedLanguage->getLocale(), $actualLanguage->getLocale());
            $this->assertEquals($expectedLanguage->getTranslationProgress(), $actualLanguage->getTranslationProgress());
            $this->assertEquals($expectedLanguage->isFullyTranslated(), $actualLanguage->isFullyTranslated());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->gateway = new LanguageGatewayImpl();
        $this->gateway->setClient(new ClientMock());
        ClientMock::$languagesContent = '{"meta": {"status": 200,"record_count": 3},"data": [{"code": "en-US","english_name": "English (United States)","local_name": "English (United States)","locale": "en","region": "US","is_base_language": true,"is_ready_to_publish": true,"translation_progress": "100%","uploaded_at": "2013-10-07T15:27:10+0000","uploaded_at_timestamp": 1381159630},{"code": "ja-JP","english_name": "Japanese","local_name": "日本語","locale": "ja","region": "JP","is_base_language": false,"is_ready_to_publish": true,"translation_progress": "98%","uploaded_at": "2013-10-07T15:27:10+0000","uploaded_at_timestamp": 1381159630},{"code": "ko-KR","english_name": "Korean","local_name": "한국어","locale": "ko","region": "KR","is_base_language": false,"is_ready_to_publish": true,"translation_progress": "56%","uploaded_at": "2013-10-07T15:27:10+0000","uploaded_at_timestamp": 1381159630}]}';
        $this->gateway->setLanguageFactory(new LanguageFactoryImpl());
    }
}
