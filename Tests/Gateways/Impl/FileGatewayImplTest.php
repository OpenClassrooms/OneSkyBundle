<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Gateways\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Gateways\FileGateway;
use OpenClassrooms\Bundle\OneSkyBundle\Gateways\Impl\FileGatewayImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Model\ExportFile;
use OpenClassrooms\Bundle\OneSkyBundle\Model\UploadFile;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ExportFileStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ExportFileStub2;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\UploadFileStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\UploadFileStub2;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\OneSky\Api\ClientMock;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class FileGatewayImplTest extends \PHPUnit_Framework_TestCase
{
    const EXPECTED_DOWNLOADED_FILE_1 = __DIR__.'/../../Fixtures/Resources/translations/messages.fr.yml';

    const EXPECTED_DOWNLOADED_FILE_2 = __DIR__.'/../../Fixtures/Resources/translations/subDirectory/messages.fr.yml';

    /**
     * @var FileGateway
     */
    private $gateway;

    /**
     * @test
     */
    public function WithoutFiles_upload_DoNothing()
    {
        $uploadedTranslations = $this->gateway->uploadTranslations([]);
        $this->assertNull(ClientMock::$action);
        $this->assertEmpty($uploadedTranslations);
    }

    /**
     * @test
     */
    public function OneFile_upload()
    {
        $uploadFileStub1 = new UploadFileStub1();
        $uploadedTranslations = $this->gateway->uploadTranslations([$uploadFileStub1]);
        $this->assertEquals(ClientMock::$action, FileGateway::UPLOAD_METHOD);
        $this->assertEquals(
            [
                [
                    UploadFile::PROJECT_ID             => UploadFileStub1::PROJECT_ID,
                    UploadFile::SOURCE_FILE_PATH       => $uploadFileStub1->getFormattedFilePath(),
                    UploadFile::FILE_FORMAT            => UploadFileStub1::FILE_FORMAT,
                    UploadFile::SOURCE_LOCALE          => UploadFileStub1::SOURCE_LOCALE,
                    UploadFile::IS_KEEPING_ALL_STRINGS => UploadFileStub1::IS_KEEPING_ALL_STRINGS,
                ],
            ],
            ClientMock::$parameters
        );
        $this->assertEquals([new UploadFileStub1()], $uploadedTranslations);
    }

    /**
     * @test
     */
    public function ManyFiles_upload_Upload()
    {
        $uploadFileStub1 = new UploadFileStub1();
        $uploadFileStub2 = new UploadFileStub2();
        $uploadedTranslations = $this->gateway->uploadTranslations([$uploadFileStub1, $uploadFileStub2]);
        $this->assertEquals(ClientMock::$action, FileGateway::UPLOAD_METHOD);
        $this->assertEquals(
            [
                [
                    UploadFile::PROJECT_ID             => UploadFileStub1::PROJECT_ID,
                    UploadFile::SOURCE_FILE_PATH       => $uploadFileStub1->getFormattedFilePath(),
                    UploadFile::FILE_FORMAT            => UploadFileStub1::FILE_FORMAT,
                    UploadFile::SOURCE_LOCALE          => UploadFileStub1::SOURCE_LOCALE,
                    UploadFile::IS_KEEPING_ALL_STRINGS => UploadFileStub1::IS_KEEPING_ALL_STRINGS,
                ],
                [
                    UploadFile::PROJECT_ID             => UploadFileStub2::PROJECT_ID,
                    UploadFile::SOURCE_FILE_PATH       => $uploadFileStub2->getFormattedFilePath(),
                    UploadFile::FILE_FORMAT            => UploadFileStub2::FILE_FORMAT,
                    UploadFile::SOURCE_LOCALE          => UploadFileStub2::SOURCE_LOCALE,
                    UploadFile::IS_KEEPING_ALL_STRINGS => UploadFileStub2::IS_KEEPING_ALL_STRINGS
                ],
            ],
            ClientMock::$parameters
        );
        $this->assertEquals([new UploadFileStub1(), new UploadFileStub2()], $uploadedTranslations);
    }

    /**
     * @test
     * @expectedException \OpenClassrooms\Bundle\OneSkyBundle\Gateways\InvalidContentException
     */
    public function WithApiException_download_ThrowException()
    {
        ClientMock::$downloadedContent = '{exception}';
        $this->gateway->downloadTranslations([new ExportFileStub1()]);
    }

    /**
     * @test
     */
    public function WithFileNotOnApiException_download_DoNothing()
    {
        ClientMock::$downloadedContent = '{"meta":{"status":400,"message":"Invalid source file"},"data":{}}';
        $this->gateway->downloadTranslations([new ExportFileStub1()]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException;
     */
    public function ApiServerError_download_ThrowException()
    {
        $exception = null;
        ClientMock::$downloadedContent = '{"meta":{"status":500,"message":"Something went wrong. Please try again or contact us at support@oneskyapp.com"},"data":{}}';
        try {
            $this->gateway->downloadTranslations([new ExportFileStub1()]);
        } catch (HttpException $e) {
            $exception = $e;
        }
        $this->assertNotNull($exception);
        $this->assertEquals(500, $exception->getStatusCode());
    }

    /**
     * @test
     */
    public function WithoutFile_download_DoNothing()
    {
        $downloadedTranslations = $this->gateway->downloadTranslations([]);
        $this->assertNull(ClientMock::$action);
        $this->assertEmpty($downloadedTranslations);
    }

    /**
     * @test
     */
    public function WithOneFile_download_Download()
    {
        $exportFileStub1 = new ExportFileStub1();
        $downloadedTranslations = $this->gateway->downloadTranslations([$exportFileStub1]);
        $this->assertEquals(ClientMock::$action, FileGateway::DOWNLOAD_METHOD);
        $this->assertEquals(
            ClientMock::$parameters,
            [
                [
                    ExportFile::PROJECT_ID                 => ExportFileStub1::PROJECT_ID,
                    ExportFile::REQUESTED_LOCALE           => ExportFileStub1::REQUESTED_LOCALE,
                    ExportFile::REQUESTED_SOURCE_FILE_NAME => $exportFileStub1->getEncodedSourceFileName(),
                ],
            ]
        );
        $this->assertStringEqualsFile(self::EXPECTED_DOWNLOADED_FILE_1, 'Download : 1');
        $this->assertEquals([new ExportFileStub1()], $downloadedTranslations);
    }

    /**
     * @test
     */
    public function WithManyFiles_upload_Upload()
    {
        $exportFileStub1 = new ExportFileStub1();
        $exportFileStub2 = new ExportFileStub2();
        $downloadedTranslations = $this->gateway->downloadTranslations([$exportFileStub1, $exportFileStub2]);
        $this->assertEquals(ClientMock::$action, FileGateway::DOWNLOAD_METHOD);
        $this->assertEquals(
            ClientMock::$parameters,
            [
                [
                    ExportFile::PROJECT_ID                 => ExportFileStub1::PROJECT_ID,
                    ExportFile::REQUESTED_LOCALE           => ExportFileStub1::REQUESTED_LOCALE,
                    ExportFile::REQUESTED_SOURCE_FILE_NAME => $exportFileStub1->getEncodedSourceFileName(),
                ],
                [
                    ExportFile::PROJECT_ID                 => ExportFileStub2::PROJECT_ID,
                    ExportFile::REQUESTED_LOCALE           => ExportFileStub2::REQUESTED_LOCALE,
                    ExportFile::REQUESTED_SOURCE_FILE_NAME => $exportFileStub2->getEncodedSourceFileName(),
                ],
            ]
        );
        $this->assertStringEqualsFile(self::EXPECTED_DOWNLOADED_FILE_1, 'Download : 1');
        $this->assertStringEqualsFile(self::EXPECTED_DOWNLOADED_FILE_2, 'Download : 2');
        $this->assertEquals([new ExportFileStub1(), new ExportFileStub2()], $downloadedTranslations);
    }

    protected function setUp()
    {
        $this->gateway = new FileGatewayImpl();
        $this->gateway->setClient(new ClientMock());
        $this->gateway->setEventDispatcher(new EventDispatcher());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        file_exists(self::EXPECTED_DOWNLOADED_FILE_1) ? unlink(self::EXPECTED_DOWNLOADED_FILE_1) : null;
        file_exists(self::EXPECTED_DOWNLOADED_FILE_2) ? unlink(self::EXPECTED_DOWNLOADED_FILE_2) : null;
    }
}
