<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Gateways\Impl;

use Onesky\Api\Client;
use OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageException;
use OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageGateway;
use OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageNotFoundException;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Language;
use OpenClassrooms\Bundle\OneSkyBundle\Model\LanguageFactory;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class LanguageGatewayImpl implements LanguageGateway
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var LanguageFactory
     */
    private $languageFactory;

    /**
     * @return \OpenClassrooms\Bundle\OneSkyBundle\Model\Language[]
     *
     * @throws LanguageException
     * @throws LanguageNotFoundException
     */
    public function findLanguages(array $locales, $projectId)
    {
        $jsonResponse = $this->client->projects(self::LANGUAGES_METHOD, ['project_id' => $projectId]);
        $response = json_decode($jsonResponse, true);

        $this->checkResponse($response, $jsonResponse);

        $languages = $this->createLanguages($response, $projectId);
        $requestedLanguages = [];
        foreach ($locales as $locale) {
            if (isset($languages[$locale])) {
                $requestedLanguages[] = $languages[$locale];
            } else {
                throw new LanguageNotFoundException($locale);
            }
        }

        return $requestedLanguages;
    }

    /**
     * @throws LanguageException
     */
    private function checkResponse($response, $jsonResponse)
    {
        if (200 !== $response['meta']['status']) {
            throw new LanguageException($jsonResponse);
        }
    }

    /**
     * @return \OpenClassrooms\Bundle\OneSkyBundle\Model\Language[]
     */
    private function createLanguages($response, $projectId)
    {
        $languages = $this->languageFactory->createFromCollection($response['data'], $projectId);

        return $this->formatLanguages($languages);
    }

    /**
     * @param Language[] $languages
     *
     * @return Language[]
     */
    private function formatLanguages(array $languages)
    {
        $languageLocales = [];
        foreach ($languages as $language) {
            $languageLocales[$language->getLocale()] = $language;
        }

        return $languageLocales;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function setLanguageFactory(LanguageFactory $languageFactory)
    {
        $this->languageFactory = $languageFactory;
    }
}
