<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class PullCommand extends Command
{
    const COMMAND_NAME = 'openclassrooms:one-sky:pull';

    const COMMAND_DESCRIPTION = 'Pull translations';

    protected function configure()
    {
        $this->setName($this->getCommandName())
            ->setDescription($this->getCommandDescription())
            ->addOption('projectId', null,  InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Requested projectsIds', [])
            ->addOption('filePath', 'dir', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'File paths', [])
            ->addOption('locale', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Requested requestedLocale', []);
    }

    /**
     * @return string
     */
    protected function getCommandName()
    {
        return self::COMMAND_NAME;
    }

    protected function getCommandDescription()
    {
        return self::COMMAND_DESCRIPTION;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->handlePullDisplay($output);
        $this->getContainer()->get('openclassrooms.onesky.services.translation_service')->pull(
            $input->getOption('projectId'),
            $input->getOption('filePath'),
            $input->getOption('locale')
        );
    }
}
