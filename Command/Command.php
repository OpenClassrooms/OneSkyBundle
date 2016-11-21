<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Command;

use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationDownloadTranslationEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPostPullEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPostPushEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPrePullEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationPrePushEvent;
use OpenClassrooms\Bundle\OneSkyBundle\EventListener\TranslationUploadTranslationEvent;
use OpenClassrooms\Bundle\OneSkyBundle\Model\ExportFile;
use OpenClassrooms\Bundle\OneSkyBundle\Model\UploadFile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

const PROGRESS_BAR_FORMAT = "<comment>%message%</comment>\n %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%";

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
abstract class Command extends ContainerAwareCommand
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * @return string
     */
    abstract protected function getCommandName();

    /**
     * @return string
     */
    abstract protected function getCommandDescription();

    protected function handlePullDisplay(OutputInterface $output)
    {
        $dispatcher = $this->getContainer()->get('openclassrooms.onesky.event_dispatcher');
        $dispatcher->addListener(
            TranslationPrePullEvent::getEventName(),
            function (TranslationPrePullEvent $event) use ($output) {
                $output->writeln("<info>Pulling files </info>\n");
                $this->progressBar = new ProgressBar($output, $event->getExportFilesCount());
                $this->progressBar->setFormat(PROGRESS_BAR_FORMAT);
                $this->getProgressBar()->start();
            }
        );

        $dispatcher->addListener(
            TranslationDownloadTranslationEvent::getEventName(),
            function (TranslationDownloadTranslationEvent $event) {
                $this->getProgressBar()->setMessage(
                    '<comment>'.$event->getExportFile()->getTargetFilePathRelativeToProject().'</comment>'
                );
                $this->getProgressBar()->advance();
            }
        );

        $dispatcher->addListener(
            TranslationPostPullEvent::getEventName(),
            function (TranslationPostPullEvent $event) use ($output) {
                $this->progressBar->finish();
                $output->writeln('<info>'.count($event->getDownloadedFiles()).' files downloaded. </info>');
                $table = new Table($output);
                $table
                    ->setHeaders(['Project', 'File', 'Locale'])
                    ->setRows(
                        array_map(
                            function (ExportFile $file) {
                                return [$file->getProjectId(), $file->getSourceFilePathRelativeToProject(), $file->getRequestedLocale()];
                            },
                            $event->getDownloadedFiles()
                        )
                    );
                $table->render();
            }
        );
    }

    /**
     * @return ProgressBar
     */
    private function getProgressBar()
    {
        return $this->progressBar;
    }

    protected function handlePushDisplay(OutputInterface $output)
    {
        $dispatcher = $this->getContainer()->get('openclassrooms.onesky.event_dispatcher');
        $dispatcher->addListener(
            TranslationPrePushEvent::getEventName(),
            function (TranslationPrePushEvent $event) use ($output) {
                $output->writeln("<info>Pushing files </info>\n");
                $this->progressBar = new ProgressBar($output, $event->getUploadFilesCount());
                $this->progressBar->setFormat(PROGRESS_BAR_FORMAT);
                $this->getProgressBar()->start();
            }
        );

        $dispatcher->addListener(
            TranslationUploadTranslationEvent::getEventName(),
            function (TranslationUploadTranslationEvent $event) {
                $this->getProgressBar()->setMessage($event->getUploadFile()->getSourceFilePathRelativeToProject());
                $this->getProgressBar()->advance();
            }
        );

        $dispatcher->addListener(
            TranslationPostPushEvent::getEventName(),
            function (TranslationPostPushEvent $event) use ($output) {
                $this->progressBar->finish();
                $output->writeln('');
                $output->writeln('<info>'.count($event->getUploadedFiles()).' files downloaded. </info>');
                $table = new Table($output);
                $table
                    ->setHeaders(['Project', 'File', 'Locale'])
                    ->setRows(
                        array_map(
                            function (UploadFile $file) {
                                return [$file->getProjectId(), $file->getSourceFilePathRelativeToProject(), $file->getSourceLocale()];
                            },
                            $event->getUploadedFiles()
                        )
                    );
                $table->render();
            }
        );
    }
}
