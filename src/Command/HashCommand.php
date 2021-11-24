<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;


class HashCommand extends Command
{
    protected static $defaultName = 'app:generate-hash';
    protected static $defaultDescription = 'Return a hash with 0000 prefix by a informed parameter';

    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'Informed input string')
            ->addOption('requests', null, InputOption::VALUE_REQUIRED, 'Number of requests')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputted = $input->getArgument('input');
        $requests = $input->getOption('requests');
        if(!is_numeric($requests) || gettype($requests + 0) != 'integer'){
          $io->error('O valor do parâmetro request deve ser um número inteiro, o valor informado foi: '.$requests);
          return Command::FAILURE;
        }

        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/generate/' . $inputted . '/' . $requests);
        if($response->getStatusCode() == 429){
            $io->error('Ultrapassadas as 10 requisições por minuto, favor tentar novamente mais tarde.');
            return Command::FAILURE;
        }
        $v = $response->getContent();
        $io->success('Hash gerados com sucesso.');

        return Command::SUCCESS;
    }
}
