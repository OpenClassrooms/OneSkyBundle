<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\Command;

use OpenClassrooms\Bundle\OneSkyBundle\Gateways\LanguageNotFoundException;
use OpenClassrooms\Bundle\OneSkyBundle\Model\Language;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class CheckTranslationProgressCommand extends ContainerAwareCommand
{
    const COMMAND_NAME = 'openclassrooms:one-sky:check-translation-progress';

    const COMMAND_DESCRIPTION = 'Check translations progress';

    protected function configure()
    {
        $this->setName($this->getCommandName())
            ->setDescription($this->getCommandDescription())
            ->addOption('projectId', null,  InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Requested projectsIds', [])
            ->addOption('locale', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Source locale', []);
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

    private function getLocales($project, array $locales)
    {
        if(empty($locales))
            return $project["locales"];
        else
            return $locales;
    }

    private function getProjects(array $projects, array $projectsIds)
    {
        if(empty($projectsIds))
            return $projects;
        else
        {
            foreach ($projects as $projectId => &$project)
                if(!in_array($projectId, $projectsIds))
                    unset($projects[$projectId]);
            return $projects;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = $this->getProjects($this->getContainer()->getParameter('openclassrooms_onesky.projects'), $input->getOption('projectId'));
        $output->writeln('<info>Check translations progress</info>');
        $languages = [];
        foreach ($projects as $projectId => &$project) {
            try {
                $projectLanguages = $this->getContainer()
                    ->get('openclassrooms.onesky.services.language_service')
                    ->getLanguages($project, $this->getLocales($project, $input->getOption('locale')));
                $languages = array_merge($languages, $projectLanguages);
            } catch(LanguageNotFoundException $e) {
                if(!empty($input->getOption('locale')))
                    $output->writeln('<info>Language '.$input->getOption('locale').' not found for project '.$projectId.'</info>');
                else
                    throw $e;
            }
        }
        if(empty($languages))
            throw new LanguageNotFoundException();
        $table = new Table($output);
        $table
            ->setHeaders(['Project', 'Locale', 'Progression'])
            ->setRows(
                array_map(
                    function (Language $language) {
                        return [$language->getProjectId(), $language->getLocale(), $language->getTranslationProgress()];
                    },
                    $languages
                )
            );
        $table->render();

        foreach ($languages as $language) {
            if (!$language->isFullyTranslated()) {
                return 1;
            }
        }
        return 0;
    }
}
