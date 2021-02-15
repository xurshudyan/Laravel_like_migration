<?php


namespace App\vendor\core\commands;


use App\vendor\core\commands\comandsexecute\CreateTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTableComand extends Command
{
    public static $defaultName = 'make:migration';

    protected function configure()
    {
        $this->setDescription('Create a new migration file')
            ->addArgument('table', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = "text";
        $name = $input->getArgument('table');
        CreateTable::migrate($name);

        $output->writeln("<info>Created Migration: </info>" . CreateTable::camel($name));
        return Command::SUCCESS;
    }
}