<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Services;

use OpenClassrooms\Bundle\OneSkyBundle\Model\ExportFile;
use OpenClassrooms\Bundle\OneSkyBundle\Model\UploadFile;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface TranslationService
{
    /**
     * @param integer $projectId
     * @param string[] $filePaths
     * @param string[] $locales
     *
     * @return ExportFile[] $files
     */
    public function pull($projectId, array $filePaths, array $locales = []);

    /**
     * @param integer $projectId
     * @param string[] $filePaths
     *
     * @return UploadFile[] $files
     */
    public function push($projectId, array $filePaths, array $locales = []);

    /**
     * @param integer $projectId
     * @param string[] $filePaths
     *
     * @return [ExportFile[], UploadFile[]] $files
     */
    public function update($projectId, array $filePaths, array $locales = []);
}
