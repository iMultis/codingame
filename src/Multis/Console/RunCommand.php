<?php

namespace Multis\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /** @inheritdoc */
    protected static $defaultName = 'run';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Run specific game')
            ->setHelp('Runs a specific game.')
            ->addArgument('game', InputArgument::REQUIRED, 'game name')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $game = $input->getArgument('game');
        $gameClass = 'Multis\\' . $game . '\\' . $game;

        $application = new $gameClass();
        $application->execute();
    }
}
