<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Model\ExportFile;
use OpenClassrooms\Bundle\OneSkyBundle\Model\FileFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class FileFactoryImpl implements FileFactory
{
    /**
     * @var string
     */
    private $fileFormat;
    /**
     * @var array
     */
    private $fileFormats;

    /**
     * @var string
     */
    private $kernelRootDir;

    /**
     * @var string
     */
    private $sourceLocale;

    /**
     * @var bool
     */
    private $isKeepingAllStrings;

    /**
     * @return ExportFile
     */
    public function createExportFile($sourceFilePath, $projectId, $requestedLocale)
    {
        return new ExportFileImpl($projectId, $sourceFilePath, $this->getProjectDirectory(), $requestedLocale);
    }

    /**
     * @return string
     */
    private function getProjectDirectory()
    {
        return $this->kernelRootDir.'/../';
    }

    /**
     * {@inheritdoc}
     */
    public function createUploadFile($filePath, $projectId, $locale = null)
    {
        $file =  new UploadFileImpl(
            $projectId,
            $filePath,
            $this->getProjectDirectory(),
            isset($this->fileFormats[$projectId])?$this->fileFormats[$projectId]:$this->fileFormat,
            empty($locale) ? $this->sourceLocale : $locale
        );

        $file->setKeepingAllStrings($this->isKeepingAllStrings);

        return $file;
    }

    public function setKeepingAllStrings($isKeepingAllStrings)
    {
        $this->isKeepingAllStrings = $isKeepingAllStrings;
    }

    public function setFileFormat($fileFormat, $fileFormats)
    {
        $this->fileFormat = $fileFormat;
        $this->fileFormats = $fileFormats;
    }

    public function setKernelRootDir($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

    public function setSourceLocale($sourceLocale)
    {
        $this->sourceLocale = $sourceLocale;
    }
}
