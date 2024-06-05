<?php

namespace MyExample\Commands;

use HiFolks\DataType\Arr;
use MyExample\Mapi\Assets;
use MyExample\Mapi\Spaces;
use MyExample\TermLib\Term;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\search;

class UploadAssetCommand extends Command
{
    #[\Override]
    protected function configure()
    {
        $this
            ->setName('upload')
            ->setDescription('Upload Asset')
            ->setHelp('This command Upload an asset.')
            ->addOption(
                'spaceid',
                's',
                InputOption::VALUE_REQUIRED,
                'The Space ID',
            )

            ->addArgument(
                'files',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'List of files to upload',
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
        $assets = new Assets($client);
        $spacesApi = new Spaces($client);

        $files = $input->getArgument('files');
        $spaceId = $input->getOption('spaceid');
        if (is_null($spaceId)) {
            $spaces = $spacesApi->all();
            //Arr::make($client->get('/v1/spaces')->getBody())->getArr('spaces');
            //var_dump($spaces);
            $ids = [];
            foreach ($spaces as $space) {
                $ids[$space["id"]] = $space["id"] . " - " . $space["name"];
            }


            $spaceId = search(
                label: 'Search for the space id for uploading the assets',
                placeholder: 'The space identifier (a number)',
                options: static fn($value): array => strlen($value) > 0
                ? array_filter($ids, static fn($e): bool => str_contains($e, $value))
                : [],
                hint: 'Insert the space id.',
            );


        }

        //var_dump($files);
        //var_dump($spaceId);


        Term::title(
            'Uploading %d assets, into %s space ',
            count($files),
            $spaceId,
        );


        foreach ($files as $filename) {
            $result = $assets->upload(
                $filename,
                $spaceId,
                imageToText: true,
            );
            Term::sectionTitle(" ** Image (%d)** ", $result["id"]);
            Term::labelValue("Image Identifier", $result["id"]);
            Term::labelValue("Space Identifier", $result["spaceid"]);
            Term::labelValue("Space URL", sprintf('https://app.storyblok.com/#/me/spaces/%s/assets/0', $result['spaceid']));
            Term::labelValue("URL", $result["url"]);
            Term::labelValue("Alt Text", $result["text"]);

        }



        return Command::SUCCESS;
    }
}
