<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class UpdateCommand extends Command
{
    const COMMAND_NAME = 'openclassrooms:one-sky:update';

    const COMMAND_DESCRIPTION = 'Update translations';

    protected function configure()
    {
        $this->setName($this->getCommandName())
            ->setDescription($this->getCommandDescription())
            ->addOption('projectId', null, InputOption::VALUE_OPTIONAL, 'Project Id');
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
        $output->writeln("<info>Updating translations</info>\n");
        $this->handlePullDisplay($output);
        $this->handlePushDisplay($output);
        $this->getContainer()->get('openclassrooms.onesky.services.translation_service')->update($input->getOption('projectId'));
    }
}
