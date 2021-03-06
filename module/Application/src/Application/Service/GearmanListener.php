<?php


namespace Application\Service;

class GearmanListener extends ServiceAbstract
{

    /**
     * @param $workload
     * @param $jobName
     */
    public function addJobToQueue($workload, $jobName)
    {
        $gearmanClient = new \GearmanClient();
        $gearmanClient->addServer();

        $handle = $gearmanClient->doBackground($jobName, $workload);


    }

    /**
     * @param $jobName
     */
    public function writeToLog($jobName)
    {
        $gearmanWorker = new \GearmanWorker();
        $gearmanWorker->addServer();

        $gearmanWorker->addFunction(
            $jobName,
            function ($gearmanJob) {

                $logfile = fopen('/tmp/gearman.txt', 'a+');

                if ($logfile) {
                    echo date('d-m-Y h:i:s') . ': Data found, processing...' . PHP_EOL;
                    for ($i = 0; $i < 5; $i++) {
                        sleep(1);
                        $logData = sprintf('[%s]: %s %s', date('d-m-Y h:i:s'), $gearmanJob->workload(), PHP_EOL);
                        fwrite($logfile, $logData);
                    }
                }
            }
        );

        while ($gearmanWorker->work()) {
            if (GEARMAN_SUCCESS != $gearmanWorker->returnCode()) {
                echo "Worker failed: " . $gearmanWorker->error() . PHP_EOL;
            }
        }
    }

}


