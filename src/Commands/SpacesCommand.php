<?php

namespace MyExample\Commands;

use MyExample\Mapi\Spaces;
use MyExample\Mapi\User;
use MyExample\TermLib\Term;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\table as promptsTable;

class SpacesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('spaces')
            ->setDescription('Manage Spaces')
            ->setHelp('This command allows you to manage the Spaces.')
            ->addOption(
                'spaceid',
                's',
                InputOption::VALUE_OPTIONAL,
                'The Space ID',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $client = new \Storyblok\ManagementClient(
            apiKey: $_ENV['STORYBLOK_OAUTH_TOKEN'],
        );
        $clientApi = new \Storyblok\ManagementClient(
            apiKey: $_ENV['STORYBLOK_OAUTH_TOKEN'],
            apiEndpoint: 'api.storyblok.com',
        );


        $spaceId = $input->getOption('spaceid');

        $spaces = (new Spaces($client))->all();
        $me = (new User($clientApi))->me();



        Term::title('Spaces for %s', $me->get("id"));
        $tableRows = [];
        foreach ($spaces as $key => $element) {
            $isMe = ($me->get("id") == $element["owner_id"]) ? "v" : "";
            $tableRows[] = [
                $element["id"],
                $element["name"],
                //$element["owner_id"],
                $isMe,

            ];
        }
        //var_dump($tableRows);
        promptsTable(
            ['ID', 'Space',  "Is me?"],
            $tableRows,
        );

        return Command::SUCCESS;
    }
}
