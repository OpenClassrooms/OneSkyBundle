<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class UploadFile extends File
{
    const IS_KEEPING_ALL_STRINGS = 'is_keeping_all_strings';

    const SOURCE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected $formattedSourceFilePath;

    /**
     * @var string
     */
    protected $sourceLocale;

    /**
     * @var bool
     */
    protected $isKeepingAllStrings = true;

    public function __construct($project, $sourceFilePath, $projectDirectory, $sourceLocale)
    {
        parent::__construct($project, $sourceFilePath, $projectDirectory);
        $this->formattedSourceFilePath = sys_get_temp_dir().'/'.$this->getEncodedSourceFileName();
        copy($sourceFilePath, $this->formattedSourceFilePath);
        $this->sourceLocale = $sourceLocale;
        $this->isKeepingAllStrings = $project["keep_all_strings"];
    }

    /**
     * @return string[]
     */
    public function format()
    {
        return [
            self::PROJECT_ID             => $this->projectId,
            self::SOURCE_FILE_PATH       => $this->formattedSourceFilePath,
            self::FILE_FORMAT            => $this->fileFormat,
            self::SOURCE_LOCALE          => $this->sourceLocale,
            self::IS_KEEPING_ALL_STRINGS => $this->isKeepingAllStrings
        ];
    }

    /**
     * @return string
     */
    public function getSourceLocale()
    {
        return $this->sourceLocale;
    }

}
