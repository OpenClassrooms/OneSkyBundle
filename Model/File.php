<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class File
{
    const FILENAME_SEPARATOR = '__';

    const PROJECT_ID = 'project_id';

    const SOURCE_FILE_PATH = 'file';

    /**
     * @var int
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $sourceFilePath;

    /**
     * @var string
     */
    protected $sourceFilePathRelativeToProject;

    /**
     * {@inheritdoc}
     */
    public function __construct($projectId, $sourceFilePath, $projectDirectory)
    {
        $this->projectId = $projectId;
        $this->sourceFilePath = realpath($sourceFilePath);
        $this->sourceFilePathRelativeToProject = str_replace(realpath($projectDirectory), '', $this->sourceFilePath);
    }

    /**
     * @return string
     */
    public function getSourceFilePathRelativeToProject()
    {
        return $this->sourceFilePathRelativeToProject;
    }

    /**
     * @return string[]
     */
    abstract public function format();

    /**
     * @return string
     */
    public function getEncodedSourceFileName()
    {
        return str_replace(DIRECTORY_SEPARATOR, self::FILENAME_SEPARATOR, $this->sourceFilePathRelativeToProject);
    }


    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }
}
