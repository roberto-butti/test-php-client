<?php

namespace MyExample\Commands;

use MyExample\Mapi\Stories;
use MyExample\Mapi\Workflows;
use MyExample\TermLib\Term;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StoriesCommand extends Command
{
    #[\Override]
    protected function configure()
    {
        $this
            ->setName('stories')
            ->setDescription('Manage Stories')
            ->setHelp('This command allows you to manage the Stories.')
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

        $storiesEndpoint = new Stories($client);
        $stories = $storiesEndpoint->list($spaceId);
        var_dump($stories);

        Term::title('Stories');

        $stories->forEach(
            static function (array $element, $key) use ($storiesEndpoint, $spaceId): void {
                //Term::labelValue($key, $element);
                Term::labelValue($element["id"], $element["name"]);
                if ($element["content_type"] === "default-page") {
                    $storiesEndpoint->applyWorkflow($spaceId, $element["id"], 561595);
                }
            },
        );
        $workflowsEndpoint = new Workflows($client);
        $workflows = $workflowsEndpoint->list($spaceId);
        $workflows->forEach(
            static function (array $element, $key) use ($workflowsEndpoint, $spaceId): void {
                //Term::labelValue($key, $element);
                Term::labelValue($element["id"], $element["name"]);
                $stages = $workflowsEndpoint->listStages($spaceId, $element["id"]);
                $stages->forEach(
                    static function (array $element, $key): void {
                        Term::labelValue($element["id"], $element["name"]);
                    },
                );
            },
        );


        return Command::SUCCESS;
    }
}
