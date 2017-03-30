<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class ExportFile extends File
{
    const REQUESTED_LOCALE = 'locale';

    const REQUESTED_SOURCE_FILE_NAME = 'source_file_name';

    /**
     * @var string
     */
    protected $requestedLocale;

    public function __construct($project, $sourceFilePath, $projectDirectory, $requestedLocale)
    {
        parent::__construct($project, $sourceFilePath, $projectDirectory);
        $this->requestedLocale = $requestedLocale;
    }

    /**
     * @return string
     */
    public function getRequestedLocale()
    {
        return $this->requestedLocale;
    }

    /**
     * @return string
     */
    public function getTargetFilePath()
    {
        $explodedFilePath = explode('.', $this->sourceFilePath);
        $explodedFilePath[count($explodedFilePath) - 2] = $this->requestedLocale;

        return implode('.', $explodedFilePath);
    }

    /**
     * @return string
     */
    public function getTargetFilePathRelativeToProject()
    {
        $explodedFilePath = explode('.', $this->sourceFilePathRelativeToProject);
        $explodedFilePath[count($explodedFilePath) - 2] = $this->requestedLocale;

        return implode('.', $explodedFilePath);
    }

    /**
     * @return string[]
     */
    public function format()
    {
        return [
            self::FILE_FORMAT => $this->fileFormat,
            self::PROJECT_ID => $this->projectId,
            self::REQUESTED_LOCALE => $this->requestedLocale,
            self::REQUESTED_SOURCE_FILE_NAME => $this->getEncodedSourceFileName(),
        ];
    }


}
