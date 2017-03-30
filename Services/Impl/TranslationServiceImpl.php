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
    private $projects;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * {@inheritdoc}
     */
    public function update(array $projectsIds, array $filePaths = [], array $locales = [])
    {
        $this->eventDispatcher->dispatch(TranslationUpdateEvent::getEventName(), new TranslationUpdateEvent());

        return [$this->push($projectsIds, $filePaths), $this->pull($projectsIds, $filePaths, $locales)];
    }

    /**
     * {@inheritdoc}
     */
    public function pull(array $projectsIds, array $filePaths, array $locales = [])
    {
        $exportFiles = [];
        foreach ($this->getProjectsIds($projectsIds) as $projectId) {
            foreach ($this->getFiles($this->getFilePaths($filePaths, $projectId), $this->projects[$projectId]["source_locale"], $projectId) as $file) {
                foreach ($this->getRequestedLocales($locales, $projectId) as $locale) {
                    $exportFiles[] = $this->fileFactory->createExportFile($file->getRealPath(), $this->projects[$projectId], $locale);
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
     * @return
     */
    private function getFiles(array $paths, string $locale, $projectId)
    {
        if(empty($paths))
            return array();
        else
            return Finder::create()
                ->files()
                ->in($paths)
                ->name('*.'.$locale.'.'.$this->projects[$projectId]["file_format"]);
    }

    /**
     * @return string[]
     */
    private function getFilePaths(array $filePaths, $projectId)
    {
        if(empty($filePaths))
            return $this->projects[$projectId]["file_paths"];
        else {
            foreach ($filePaths as $key => &$filePath) {
                $pathFound = false;
                foreach ($this->projects[$projectId]["file_paths"] as $projectFilePath)
                    if (strpos($filePath, $projectFilePath) === 0)
                        $pathFound = true;
                if(!$pathFound)
                    unset($filePaths[$key]);
            }
            return $filePaths;
        }
    }
    /**
     * @return string[]
     */
    private function getProjectsIds($projectsIds)
    {
        if(empty($projectsIds))
            return array_keys($this->projects);
        else
            return $projectsIds;
    }

    /**
     * @return string[]
     */
    private function getSourceLocales(array $locales = [], $projectId)
    {
        if(empty($locales))
            return [$this->projects[$projectId]["source_locale"]];
        else
            return $locales;
    }

    /**
     * @return string[]
     */
    private function getRequestedLocales(array $locales, $projectId)
    {
        return empty($locales) ? $this->projects[$projectId]["locales"] : $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function push(array $projectsIds, array $filePaths, array $locales = [])
    {
        $uploadFiles = [];
        /* @var SplFileInfo $file */
        foreach ($this->getProjectsIds($projectsIds) as $projectId) {
            foreach ($this->getSourceLocales($locales, $projectId) as $locale) {
                foreach ($this->getFiles($this->getFilePaths($filePaths, $projectId), $locale, $projectId) as $file) {
                    $uploadFiles[] = $this->fileFactory->createUploadFile($file->getRealPath(), $this->projects[$projectId], $locale);
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


    public function setFileService(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function setProjects($projects)
    {
        foreach ($projects as $projectId => &$project)
            $project["id"] = $projectId;
        $this->projects = $projects;
    }

}

