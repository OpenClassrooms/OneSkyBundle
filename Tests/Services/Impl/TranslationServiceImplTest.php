<?php

namespace Services\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\ExportFileImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\FileFactoryImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\UploadFileImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Services\Impl\TranslationServiceImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Services\TranslationService;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services\FileServiceMock;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TranslationServiceImplTest extends \PHPUnit_Framework_TestCase
{
    const IS_KEEPING_ALL_STRINGS = false;
    const FILE_FORMAT = 'yml';
    const FILE_FORMATS = [1 => 'yml'];
    const KERNEL_ROOT_DIR = __DIR__.'/../../';
    const PROJECT_DIRECTORY = __DIR__.'/../../../';
    const SOURCE_LOCALE = 'en';
    const PROJECT_ID = 1;

    /**
     * @var TranslationService
     */
    private $service;

    /**
     * @test
     */
    public function pull_with_locales()
    {
        $this->service->pull(self::PROJECT_ID , [__DIR__.'/../../Fixtures/Resources/translations'], ['es']);
        $this->assertEquals(
            [$this->buildExportFile1es(), $this->buildExportFile2es()],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile1es()
    {
        return new ExportFileImpl(
            self::PROJECT_ID, __DIR__.'/../../Fixtures/Resources/translations/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'es'
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile2es()
    {
        return new ExportFileImpl(
            self::PROJECT_ID, __DIR__.'/../../Fixtures/Resources/translations/subDirectory/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'es'
        );
    }

    /**
     * @test
     */
    public function WithoutFilePaths_pull()
    {
        $this->service->pull(self::PROJECT_ID, []);
        $this->assertEquals(
            [
                $this->buildExportFile1fr(),
                $this->buildExportFile1es(),
                $this->buildExportFile2fr(),
                $this->buildExportFile2es(),
            ],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile1fr()
    {
        return new ExportFileImpl(
            self::PROJECT_ID, __DIR__.'/../../Fixtures/Resources/translations/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'fr'
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile2fr()
    {
        return new ExportFileImpl(
            self::PROJECT_ID, __DIR__.'/../../Fixtures/Resources/translations/subDirectory/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'fr'
        );
    }

    /**
     * @test
     */
    public function pull()
    {
        $this->service->pull(self::PROJECT_ID, [__DIR__.'/../../Fixtures/Resources/translations/subDirectory']);
        $this->assertEquals(
            [
                $this->buildExportFile2fr(),
                $this->buildExportFile2es(),
            ],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @test
     */
    public function WithoutFilePath_push()
    {
        $this->service->push(self::PROJECT_ID, []);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @return UploadFileImpl
     */
    private function buildUploadFile1()
    {
        $file = new UploadFileImpl(
            self::PROJECT_ID,
            __DIR__.'/../../Fixtures/Resources/translations/messages.en.yml',
            self::PROJECT_DIRECTORY,
            self::FILE_FORMAT,
            self::SOURCE_LOCALE
        );
        $file->setKeepingAllStrings(self::IS_KEEPING_ALL_STRINGS);

        return $file;
    }

    /**
     * @return UploadFileImpl
     */
    private function buildUploadFile2()
    {
        $file = new UploadFileImpl(
            self::PROJECT_ID,
            __DIR__.'/../../Fixtures/Resources/translations/subDirectory/messages.en.yml',
            self::PROJECT_DIRECTORY,
            self::FILE_FORMAT,
            self::SOURCE_LOCALE
        );
        $file->setKeepingAllStrings(self::IS_KEEPING_ALL_STRINGS);

        return $file;
    }

    /**
     * @test
     */
    public function push()
    {
        $this->service->push(self::PROJECT_ID, [__DIR__.'/../../Fixtures/Resources/*']);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @test
     */
    public function WithLocales_update_Update()
    {
        $this->service->update(self::PROJECT_ID, [__DIR__.'/../../Fixtures/Resources/'], ['es']);
        $this->assertEquals(
            [$this->buildExportFile1es(), $this->buildExportFile2es()],
            FileServiceMock::$downloadedFiles
        );
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->service = new TranslationServiceImpl();
        $fileFactory = new FileFactoryImpl();
        $fileFactory->setKeepingAllStrings(self::IS_KEEPING_ALL_STRINGS);
        $fileFactory->setFileFormat(self::FILE_FORMAT, self::FILE_FORMATS);
        $fileFactory->setKernelRootDir(self::KERNEL_ROOT_DIR);
        $fileFactory->setSourceLocale(self::SOURCE_LOCALE);
        $this->service->setEventDispatcher(new EventDispatcher());
        $this->service->setFileFactory($fileFactory);
        $this->service->setFileFormat(self::FILE_FORMAT, self::FILE_FORMATS);
        $this->service->setFilePaths([ self::PROJECT_ID => [__DIR__.'/../../Fixtures/Resources/*']]);
        $this->service->setFileService(new FileServiceMock());
        $this->service->setRequestedLocales(['fr', 'es']);
        $this->service->setSourceLocale(self::SOURCE_LOCALE);
    }
}
