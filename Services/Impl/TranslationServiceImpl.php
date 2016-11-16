<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Services\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPostPullEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPostPushEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPrePullEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPrePushEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationUpdateEvent;
use OpenClassrooms\Bundle\OneSkyBundle\Model\FileFactory;
use OpenClassrooms\Bundle\OneSkyBundle\Services\FileService;
use OpenClassrooms\Bundle\OneSkyBundle\Services\TranslationService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TranslationServiceImpl implements TranslationService
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string[]
     */
    private $filePaths;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var string
     */
    private $fileFormat;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var string[]
     */
    private $requestedLocales;

    /**
     * @var string
     */
    private $sourceLocale;

    /**
     * {@inheritdoc}
     */
    public function update($projectId, array $filePaths = [], array $locales = [])
    {
        $this->eventDispatcher->dispatch(TranslationUpdateEvent::getEventName(), new TranslationUpdateEvent());

        return [$this->pull($projectId, $filePaths, $locales), $this->push($projectId, $filePaths)];
    }

    /**
     * {@inheritdoc}
     */
    public function pull($projectId, array $filePaths, array $locales = [])
    {
        $exportFiles = [];
        /** @var SplFileInfo $file */
        foreach ($this->getFilePaths($filePaths, $projectId) as $projectId => &$paths) {
            foreach ($this->getFiles($paths, $this->getSourceLocales()) as $file) {
                foreach ($this->getRequestedLocales($locales) as $locale) {
                    $exportFiles[] = $this->fileFactory->createExportFile($file->getRealPath(), $projectId, $locale);
                }
            }
        }

        $this->eventDispatcher->dispatch(
            TranslationPrePullEvent::getEventName(),
            new TranslationPrePullEvent($exportFiles)
        );

        $downloadedFiles = $this->fileService->download($exportFiles);

        $this->eventDispatcher->dispatch(
            TranslationPostPullEvent::getEventName(),
            new TranslationPostPullEvent($downloadedFiles)
        );

        return $downloadedFiles;
    }

    /**
     * @return Finder
     */
    private function getFiles(array $paths, array $locales)
    {
        return Finder::create()
            ->files()
            ->in($paths)
            ->name('*.{'.implode(',', $locales).'}.'.$this->fileFormat);
    }

    /**
     * @return string[]
     */
    private function getFilePaths(array $filePaths, $projectId)
    {
        if(empty($filePaths)) {
            if($projectId && isset($this->filePaths[$projectId]))
                return array($projectId => $this->filePaths[$projectId]);
            else
                return $this->filePaths;
        }
        elseif ($projectId) {
            return array($projectId => $filePaths);
        }
        else
            return $this->filePaths;
    }

    /**
     * @return string[]
     */
    private function getSourceLocales(array $locales = [])
    {
        return empty($locales) ? [$this->sourceLocale] : $locales;
    }

    /**
     * @return string[]
     */
    private function getRequestedLocales(array $locales)
    {
        return empty($locales) ? $this->requestedLocales : $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function push($projectId, array $filePaths, array $locales = [])
    {

        $uploadFiles = [];
        /* @var SplFileInfo $file */
        foreach ($this->getSourceLocales($locales) as $locale) {
            foreach ($this->getFilePaths($filePaths, $projectId) as $projectId => &$paths) {
                foreach ($this->getFiles($paths, [$locale]) as $file) {
                    $uploadFiles[] = $this->fileFactory->createUploadFile($file->getRealPath(), $projectId, $locale);
                }
            }
        }

        $this->eventDispatcher->dispatch(
            TranslationPrePushEvent::getEventName(),
            new TranslationPrePushEvent($uploadFiles)
        );

        $uploadedFiles = $this->fileService->upload($uploadFiles);

        $this->eventDispatcher->dispatch(
            TranslationPostPushEvent::getEventName(),
            new TranslationPostPushEvent($uploadedFiles)
        );

        return $uploadedFiles;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setFileFactory(FileFactory $fileFactory)
    {
        $this->fileFactory = $fileFactory;
    }

    public function setFileFormat($fileFormat)
    {
        $this->fileFormat = $fileFormat;
    }

    public function setFilePaths(array $filePaths)
    {
        $this->filePaths = $filePaths;
    }

    public function setFileService(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function setRequestedLocales(array $requestedLocales)
    {
        $this->requestedLocales = $requestedLocales;
    }

    public function setSourceLocale($sourceLocale)
    {
        $this->sourceLocale = $sourceLocale;
    }
}
