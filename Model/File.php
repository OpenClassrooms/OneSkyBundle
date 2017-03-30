<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class File
{
    const FILENAME_SEPARATOR = '__';

    const PROJECT_ID = 'project_id';

    const FILE_FORMAT = 'file_format';

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
     * @var string
     */
    protected $fileFormat;

    /**
     * {@inheritdoc}
     */
    public function __construct($project, $sourceFilePath, $projectDirectory)
    {
        $this->fileFormat = $project["file_format"];
        $this->projectId = $project["id"];
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

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->fileFormat;
    }
}
