<?php

namespace Services\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\ExportFileImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\FileFactoryImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Impl\UploadFileImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Services\Impl\TranslationServiceImpl;
use OpenClassrooms\Bundle\OneSkyBundle\Services\TranslationService;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ProjectsStub;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services\FileServiceMock;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TranslationServiceImplTest extends \PHPUnit_Framework_TestCase
{
    const KERNEL_ROOT_DIR = __DIR__.'/../../';
    const PROJECT_DIRECTORY = __DIR__.'/../../../';

    /**
     * @var TranslationService
     */
    private $service;

    /**
     * @test
     */
    public function pull_with_locales()
    {
        $this->service->pull([] , ProjectsStub::$projects[1]["file_paths"], ['ja']);
        $this->assertEquals(
            [$this->buildExportFile1ja(), $this->buildExportFile2ja()],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @test
     */
    public function pull_with_locales_and_projects()
    {
        $this->service->pull([1] , ProjectsStub::$projects[1]["file_paths"], ['ja']);
        $this->assertEquals(
            [$this->buildExportFile1ja(), $this->buildExportFile2ja()],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile1ja()
    {
        return new ExportFileImpl(
            ProjectsStub::$projects[1], ProjectsStub::$projects[1]["file_paths"][0].'messages.en.yml',
            self::PROJECT_DIRECTORY,
            'ja'
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile2ja()
    {
        return new ExportFileImpl(
            ProjectsStub::$projects[1], ProjectsStub::$projects[1]["file_paths"][0].'subDirectory/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'ja'
        );
    }

    /**
     * @test
     */
    public function WithoutFilePaths_pull()
    {
        $this->service->pull([], []);
        $this->assertEquals(
            [
                $this->buildExportFile1en(),
                $this->buildExportFile1ja(),
                $this->buildExportFile2en(),
                $this->buildExportFile2ja(),
            ],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile1en()
    {
        return new ExportFileImpl(
            ProjectsStub::$projects[1], ProjectsStub::$projects[1]["file_paths"][0].'messages.en.yml',
            self::PROJECT_DIRECTORY,
            'en'
        );
    }

    /**
     * @return ExportFileImpl
     */
    private function buildExportFile2en()
    {
        return new ExportFileImpl(
            ProjectsStub::$projects[1], ProjectsStub::$projects[1]["file_paths"][0].'subDirectory/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'en'
        );
    }

    /**
     * @test
     */
    public function pull()
    {
        $this->service->pull([], [ProjectsStub::$projects[1]["file_paths"][0].'subDirectory']);
        $this->assertEquals(
            [
                $this->buildExportFile2en(),
                $this->buildExportFile2ja(),
            ],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @test
     */
    public function pull_with_projects()
    {
        $this->service->pull([1], [ProjectsStub::$projects[1]["file_paths"][0].'subDirectory']);
        $this->assertEquals(
            [
                $this->buildExportFile2en(),
                $this->buildExportFile2ja(),
            ],
            FileServiceMock::$downloadedFiles
        );
    }

    /**
     * @test
     */
    public function WithoutFilePath_push()
    {
        $this->service->push([], []);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @return UploadFileImpl
     */
    private function buildUploadFile1()
    {
        $file = new UploadFileImpl(
            ProjectsStub::$projects[1],
            ProjectsStub::$projects[1]["file_paths"][0].'messages.en.yml',
            self::PROJECT_DIRECTORY,
            'en'
        );

        return $file;
    }

    /**
     * @return UploadFileImpl
     */
    private function buildUploadFile2()
    {
        $file = new UploadFileImpl(
            ProjectsStub::$projects[1],
            ProjectsStub::$projects[1]["file_paths"][0].'subDirectory/messages.en.yml',
            self::PROJECT_DIRECTORY,
            'en'
        );

        return $file;
    }

    /**
     * @test
     */
    public function push()
    {
        $this->service->push([], ProjectsStub::$projects[1]["file_paths"]);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @test
     */
    public function push_with_projects()
    {
        $this->service->push([1], ProjectsStub::$projects[1]["file_paths"]);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @test
     */
    public function push_with_locales()
    {
        $this->service->push([], ProjectsStub::$projects[1]["file_paths"], ["en"]);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @test
     */
    public function push_with_projects_and_locales()
    {
        $this->service->push([1], ProjectsStub::$projects[1]["file_paths"], ["en"]);
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @test
     */
    public function WithLocales_update_Update()
    {
        $this->service->update([], ProjectsStub::$projects[1]["file_paths"], ['ja']);
        $this->assertEquals(
            [$this->buildExportFile1ja(), $this->buildExportFile2ja()],
            FileServiceMock::$downloadedFiles
        );
        $this->assertEquals([$this->buildUploadFile1(), $this->buildUploadFile2()], FileServiceMock::$uploadedFiles);
    }

    /**
     * @test
     */
    public function WithLocales_and_projects_update_Update()
    {
        $this->service->update([1], ProjectsStub::$projects[1]["file_paths"], ['ja']);
        $this->assertEquals(
            [$this->buildExportFile1ja(), $this->buildExportFile2ja()],
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
        $fileFactory->setKernelRootDir(self::KERNEL_ROOT_DIR);
        $this->service->setEventDispatcher(new EventDispatcher());
        $this->service->setFileFactory($fileFactory);
        $this->service->setFileService(new FileServiceMock());
        $this->service->setProjects([1 => ProjectsStub::$projects[1]]);
    }
}
