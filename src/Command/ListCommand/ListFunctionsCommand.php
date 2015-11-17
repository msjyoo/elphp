<?php

namespace Elphp\Command\ListCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

final class ListFunctionsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName("list:functions")
            ->setDescription("List all functions inside a file or directory")
            ->addArgument(
                "file",
                InputArgument::REQUIRED,
                "File or directory to scan"
            )
        ;
    }

    protected function execute()
    {

    }
}
