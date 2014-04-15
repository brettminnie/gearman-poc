<?php


namespace Application\Service;


use mwGearman\Task\Task;

class GearmanListener extends ServiceAbstract
{

    /**
     * @param $workload
     * @return mixed
     */
    public function addJobToQueue($workload)
    {
        $gearmanClient = $this->serviceLocator->get('mwGearman\Client\Pecl');
        $gearmanClient->connect();

        $task = new Task();
        $task
            ->setBackground(true)
            ->setFunction('writeJob')
            ->setWorkload($workload)
            ->setUnique(crc32($workload));

        return $gearmanClient->doTask($task);
    }

    /***
     * @param $jobName
     */
    public function retrieveJobFromQueue($jobName)
    {
        $gearmanClient = $this->serviceLocator->get('mwGearman\Worker\Pecl');
        $gearmanClient->register('writeJob', 'pushToLog');
        $gearmanClient->connect();

        while($gearmanClient->work());
    }

    /**
     * @param $gearmanJob
     */
    public function pushToLog($gearmanJob)
    {
        $logfile = fopen('/tmp/gearman.txt', 'a+');

        if($logfile) {
            $logData = sprintf('[%s]: %s %s', date('d-m-Y h:i'), $gearmanJob->workload(), PHP_EOL);
            fwrite($logfile, $logData);
        }
    }
}
