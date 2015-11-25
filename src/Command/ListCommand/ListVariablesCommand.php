<?php

namespace Elphp\Command\ListCommand;

use Elphp\Component\Indexer\Indexer;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListVariablesCommand extends Command
{
    /** @var Filesystem $filesystem */
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    protected function configure()
    {
        $this
            ->setName("list:variables")
            ->setDescription("List all variables inside a file or directory")
            ->addArgument(
                "file",
                InputArgument::REQUIRED,
                "File or directory to scan"
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument("file");
        $list = array_filter($this->filesystem->listContents($file, true), function ($file) {
            return (isset($file['type']) and
                ($file['type'] === "file" and isset($file['extension']) and $file['extension'] === "php")
            );
        });

        // If the file argument is not directory, the listContents will return empty array.
        // In this case the user has specified a file
        if(empty($list))
        {
            $list = [["path" => $this->filesystem->get($file)->getPath()]];
        }

        $dump = array_map(function ($file) use ($output) {
            $output->writeln("Indexing ".$file['path']."...");
            return Indexer::index($this->filesystem->get($file['path']));
        }, $list);

        $table = new Table($output);

        $outputs = [];

        foreach($dump as $a)
        {
            foreach($a['variables'] as $val)
            {
                $outputs[] = [$val['file']->getPath(), $val['name'], implode('|', $val['type']), $val['scope']];
            }
        }

        $output->writeln("Indexing complete!");
        $output->writeln("Scanned ".count($list)." files.");
        $output->writeln("Detected ".count($outputs)." variables.");
        $output->writeln("Rendering Table...");

        $table
            ->setHeaders(['File', 'Name', 'Types', 'Scope'])
            ->setRows($outputs)
        ;
        $table->render();
    }
}
