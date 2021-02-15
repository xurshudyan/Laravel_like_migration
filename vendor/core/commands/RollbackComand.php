<?php


namespace App\vendor\core\commands;


use App\vendor\core\commands\comandsexecute\Rollback;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackComand extends Command
{
    public static $defaultName = 'migrate:rollback';

    protected function configure()
    {
        $this->setDescription('Rollback the last database migration')
            ->addArgument('step', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $obj = new Rollback();
        $obj->rollback($input->getArgument('step'));
        $output->writeln('<info>Rolled back: successfully</info>');
        return Command::SUCCESS;
    }
}