<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Services;

use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationDownloadTranslationEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPostPullEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPostPushEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPrePullEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPrePushEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationUploadTranslationEvent;
use OpenClassrooms\Bundle\OneSkyBundle\Services\TranslationService;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\ExportFileStub1;
use OpenClassrooms\Bundle\OneSkyBundle\Tests\Doubles\Model\UploadFileStub1;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class TranslationServiceMock implements TranslationService
{
    /**
     * @var string[]
     */
    public static $pulledFilePaths = [];

    /**
     * @var bool
     */
    public static $pullCalled = false;

    /**
     * @var string[]
     */
    public static $pushedFilePaths = [];

    /**
     * @var bool
     */
    public static $pushCalled = false;

    /**
     * @var string[]
     */
    public static $updatedFilePaths = [];

    /**
     * @var bool
     */
    public static $updateCalled = false;

    /**
     * @var string[]
     */
    public static $locales = [];

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        self::$pulledFilePaths = [];
        self::$pullCalled = false;
        self::$pushedFilePaths = [];
        self::$pushCalled = false;
        self::$updatedFilePaths = [];
        self::$updateCalled = false;
        self::$locales = [];
    }

    /**
     * {@inheritdoc}
     */
    public function pull($projectId, array $filePaths = [], array $locales = [])
    {
        $this->eventDispatcher->dispatch(
            TranslationPrePullEvent::getEventName(),
            new TranslationPrePullEvent([new ExportFileStub1()])
        );
        $this->eventDispatcher->dispatch(
            TranslationDownloadTranslationEvent::getEventName(),
            new TranslationDownloadTranslationEvent(new ExportFileStub1())
        );
        $this->eventDispatcher->dispatch(
            TranslationPostPullEvent::getEventName(),
            new TranslationPostPullEvent([new ExportFileStub1()])
        );
        self::$pullCalled = true;
        self::$pulledFilePaths = $filePaths;
        self::$locales = $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function push($projectId, array $filePaths = [], array $locales = [])
    {
        $this->eventDispatcher->dispatch(
            TranslationPrePushEvent::getEventName(),
            new TranslationPrePushEvent([new UploadFileStub1()])
        );
        $this->eventDispatcher->dispatch(
            TranslationUploadTranslationEvent::getEventName(),
            new TranslationUploadTranslationEvent(new UploadFileStub1())
        );
        $this->eventDispatcher->dispatch(
            TranslationPostPushEvent::getEventName(),
            new TranslationPostPushEvent([new UploadFileStub1()])
        );
        self::$pushCalled = true;
        self::$pushedFilePaths = $filePaths;
        self::$locales = $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function update($projectId, array $filePaths = [], array $locales = [])
    {
        self::$updateCalled = true;
        self::$updatedFilePaths = $filePaths;
        self::$locales = $locales;
    }
}
