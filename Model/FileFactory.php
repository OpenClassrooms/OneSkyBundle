<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface FileFactory
{
    /**
     * @return ExportFile
     */
    public function createExportFile($sourceFilePath, $projectId, $requestedLocale);

    /**
     * @return UploadFile
     */
    public function createUploadFile($filePath, $projectId, $locale = null);
}
