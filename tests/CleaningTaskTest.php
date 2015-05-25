<?php
namespace Samurai\Cleaner;

use Pimple\Container;
use Samurai\Project\Project;
use Samurai\Task\ITask;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class CleaningTaskTest
 * @package Samurai\Cleaner
 * @author Raphaël Lefebvre <raphael@raphaellefebvre.be>
 */
class CleaningTaskTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'CHANGELOG';

        $services = new Container();

        $services['project'] = function(){
            $project = new Project();
            $project->setDirectoryPath(__DIR__);
            return $project;
        };

        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        touch($fileName);
        $this->assertTrue(file_exists($fileName));

        $task = new CleaningTask($services);
        $result = $task->execute($input, $output);
        $this->assertSame(ITask::NO_ERROR_CODE, $result);
        $this->assertSame("Cleaning files\nRemoving file $fileName\n", $output->fetch());

        $this->assertFalse(file_exists($fileName));
    }
}
