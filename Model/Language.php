<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Model;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class Language
{
    /**
     * @var string
     */
    protected $projectId;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $translationProgress;

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return bool
     */
    public function isFullyTranslated()
    {
        return '100%' === $this->getTranslationProgress();
    }

    /**
     * @return string
     */
    public function getTranslationProgress()
    {
        return $this->translationProgress;
    }

    /**
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }
}
