<?php

namespace App\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:api-demo',
    description: 'MAke api request.',
    aliases: ['app:ad'],
    hidden: false
)]
class ApiDemoCommand extends Command
{
    protected const URL = 'https://reqres.in/api/users';

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('job', InputArgument::REQUIRED, 'The job of the user.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws GuzzleException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = new Client();

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $data = [
            'name' => $input->getArgument('name'),
            'job' => $input->getArgument('job'),
        ];

        $apiResponse = $client->post(self::URL, [
            'headers' => $headers,
            'json' => $data,
        ]);

        // Get the response code
        $responseCode = $apiResponse->getStatusCode();
        $output->write(print_r([
            'status' => $apiResponse->getStatusCode(),
            'body' => $apiResponse->getBody()->getContents(),
        ], true));

        return Command::SUCCESS;
    }
}
