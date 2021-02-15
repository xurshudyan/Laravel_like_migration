<?php

namespace Core\SRC;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{
    public static $defaultName = 'migrate';
    public function configure()
    {
        $this->setDescription('Run the database migrations');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $migrate = new Migration();
        $migrate->migrate();
        $output->writeln('<info>Migrated successfully</info>');
        return Command::SUCCESS;
    }
}