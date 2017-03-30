<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Services;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
interface LanguageService
{
    /**
     * @return \OpenClassrooms\Bundle\OneSkyBundle\Model\Language[]
     */
    public function getLanguages(array $project, array $locales);
}
