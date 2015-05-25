<?php
namespace Samurai\Cleaner;

use Samurai\Task\ITask;
use Samurai\Task\Task;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CleaningTask
 * @package Samurai\Cleaner
 * @author Raphaël Lefebvre <raphael@raphaellefebvre.be>
 */
class CleaningTask extends Task
{
    /**
     * @var array
     */
    private static $patterns = [
        'CHANGELOG*',
        'UPGRADE*',
        'CONTRIBUTING*',
    ];

    /**
     * @var bool
     */
    private $hasError = false;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Cleaning files</info>');
        $this->remove($output);
        return $this->hasError ? ITask::NON_BLOCKING_ERROR_CODE : ITask::NO_ERROR_CODE;
    }

    /**
     * @param OutputInterface $output
     */
    private function remove(OutputInterface $output)
    {
        $projectDir = $this->getService('project')->getDirectoryPath() ?: getcwd();
        foreach (self::$patterns as $pattern) {
            $this->removeByPattern($output, $projectDir . DIRECTORY_SEPARATOR . $pattern);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string $pattern
     */
    private function removeByPattern(OutputInterface $output, $pattern)
    {
        foreach (glob($pattern) as $filename) {
            $output->writeln('Removing file '.$filename);
            if(!unlink($filename)){
                $output->writeln('<error>Error: file '.$filename.' not deleted!</error>');
                $this->hasError = true;
            }
        }
    }
}
