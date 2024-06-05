<?php

namespace MyExample\Commands;

use HiFolks\DataType\Arr;
use MyExample\TermLib\Term;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WhoAmICommand extends Command
{
    #[\Override]
    protected function configure()
    {
        $this
            ->setName('whoami')
            ->setDescription('Show current Storyblok User')
            ->setHelp('This command prints info about the current user in Storyblok.');
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $client = new \Storyblok\ManagementClient(
            apiKey: $_ENV['STORYBLOK_OAUTH_TOKEN'],
            apiEndpoint: 'api.storyblok.com',
        );

        $me =
        Arr::make($client->get('/v1/users/me')->getBody())->getArr('user');
        Term::title('Who Am I');
        $output->writeln('Hello, ' . $me->get('friendly_name') . '!');

        $me->forEach(
            static function ($element, $key): void {
                if (is_scalar($element)) {
                    Term::labelValue($key, $element);
                } elseif (gettype($element) === 'array') {
                    //var_dump($element);
                    Term::labelValue($key, '# ' . count($element) . ' items', 'blue');
                } else {
                    Term::labelValue($key, gettype($element), 'blue');
                }
            },
        );

        return Command::SUCCESS;
    }
}
