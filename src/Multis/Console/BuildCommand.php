<?php

namespace Multis\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class BuildCommand extends Command
{
    const LIB_PATH = 'src/Multis/Lib';
    const BUILD_MAP_FILE = 'build.map.json';

    /** @inheritdoc */
    protected static $defaultName = 'build';

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Build specific game')
            ->setHelp('Creates a single *.php file, that can be uploaded to www.codingame.com.')
            ->addArgument('game', InputArgument::REQUIRED, 'game name')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $game = $input->getArgument('game');

        $buildMap = file_exists($this->getBuildMapPath($game))
            ? json_decode(file_get_contents($this->getBuildMapPath($game)))
            : $this->createBuildMap($game)
        ;

        $output->write($this->processBuildMap($buildMap, $game));
    }

    /**
     * @param string $game
     * @return array
     */
    public function createBuildMap(string $game): array
    {
        $buildMap = [];

        foreach ([self::LIB_PATH, dirname(__DIR__) . DIRECTORY_SEPARATOR . $game] as $dir) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

            foreach ($iterator as $file) {
                if ($file->isFile() && substr($file, -4) == '.php') {
                    $buildMap[] = str_replace([$this->getRootDir(), DIRECTORY_SEPARATOR], ['', '/'], $file);
                }
            }
        }

        return $buildMap;
    }

    /**
     * @param array $buildMap
     * @param string $game
     * @return string
     * @throws \Exception
     */
    public function processBuildMap(array $buildMap, string $game): string
    {
        $output = '<?php' . PHP_EOL . PHP_EOL;

        foreach (str_replace('/', DIRECTORY_SEPARATOR, $buildMap) as $fileName) {
            if (!file_exists($this->getRootDir() . $fileName)) {
                throw new \Exception('Could not find file ' . $fileName);
            }

            $file = file_get_contents($fileName);
            $output .= $this->stripContent($file) . PHP_EOL . PHP_EOL;
        }

        $output .= 'namespace {' . PHP_EOL;
        $output .= '    use Multis\\' . $game . '\\' . $game . ';' . PHP_EOL . PHP_EOL;
        $output .= '    $application = new ' . $game . '();' . PHP_EOL;
        $output .= '    $application->execute();' . PHP_EOL;
        $output .= '}' . PHP_EOL;

        return $output;
    }

    /**
     * @param string $file
     * @return string
     */
    private function stripContent(string $file): string
    {
        $replace = [
            '<?php', '?>'
        ];

        $file = trim(str_replace($replace, '', $file));

        if (strtolower(substr($file, 0, 9)) == 'namespace') {
            $namespace = strtok($file, PHP_EOL);
            $file = str_replace($namespace, '', $file);
            $file = str_replace(';', '', $namespace) . ' {' . PHP_EOL . str_replace(PHP_EOL, PHP_EOL . '    ', $file) . PHP_EOL . '}';
        }

        return $file;
    }

    /**
     * @param string $game
     * @return string
     */
    private function getBuildMapPath(string $game): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . $game . DIRECTORY_SEPARATOR . self::BUILD_MAP_FILE;
    }

    /**
     * @return string
     */
    private function getRootDir(): string
    {
        return dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
    }
}
