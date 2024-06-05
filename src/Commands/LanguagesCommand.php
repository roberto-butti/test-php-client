<?php

namespace MyExample\Commands;

use MyExample\Mapi\Spaces;
use MyExample\TermLib\Term;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LanguagesCommand extends Command
{
    #[\Override]
    protected function configure()
    {
        $this
            ->setName('languages')
            ->setDescription('Manage Languages')
            ->setHelp('This command allows you to manage the Languages for a Space.')
            ->addOption(
                'spaceid',
                's',
                InputOption::VALUE_REQUIRED,
                'The Space ID',
            );
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $client = new \Storyblok\ManagementClient(
            apiKey: $_ENV['STORYBLOK_OAUTH_TOKEN'],
        );


        $spaceId = $input->getOption('spaceid');

        $space = (new Spaces($client))->get($spaceId);
        Term::title('Languages for Space ', $space->get("name"));
        $languages = $space->getArr("options.languages");
        if ($languages->count() === 0) {
            Term::warning("No Languages found");
        } else {
            foreach ($space->get("options.languages") as $lang) {
                Term::labelValue($lang["code"], $lang["name"]);
            }
        }

        return Command::SUCCESS;
    }
}
