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
     * @var string[]
     */
    private $requestedLocales;

    /**
     * @return \OpenClassrooms\Bundle\OneSkyBundle\Model\Language[]
     */
    public function getLanguages($projectId, array $locales = [])
    {
        if (empty($locales)) {
            $locales = $this->requestedLocales;
        }

        return $this->languageGateway->findLanguages($locales, $projectId);
    }

    public function setLanguageGateway(LanguageGateway $languageGateway)
    {
        $this->languageGateway = $languageGateway;
    }

    public function setRequestedLocales(array $requestedLocales)
    {
        $this->requestedLocales = $requestedLocales;
    }
}
