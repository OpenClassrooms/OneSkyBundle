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
    private $kernelRootDir;

    /**
     * @return ExportFile
     */
    public function createExportFile($sourceFilePath, $project, $requestedLocale)
    {
        return new ExportFileImpl($project, $sourceFilePath, $this->getProjectDirectory(), $requestedLocale);
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
    public function createUploadFile($filePath, $project, $locale)
    {
        $file =  new UploadFileImpl(
            $project,
            $filePath,
            $this->getProjectDirectory(),
            $locale
        );

        return $file;
    }

    public function setKernelRootDir($kernelRootDir)
    {
        $this->kernelRootDir = $kernelRootDir;
    }

}
