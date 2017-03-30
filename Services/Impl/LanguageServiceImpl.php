<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Services\Impl;

use OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageGateway;
use OpenClassrooms\Bundle\OneSkyBundle\Services\LanguageService;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageServiceImpl implements LanguageService
{
    /**
     * @var LanguageGateway
     */
    private $languageGateway;

    /**
     * @return \OpenClassrooms\Bundle\OneSkyBundle\Model\Language[]
     */
    public function getLanguages(array $project, array $locales)
    {
        return $this->languageGateway->findLanguages($locales, $project);
    }

    public function setLanguageGateway(LanguageGateway $languageGateway)
    {
        $this->languageGateway = $languageGateway;
    }
}
