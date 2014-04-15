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
        $gearmanClient = new \GearmanClient();
        $gearmanClient->addServer();

        $gearmanClient->doBackground('flimp',$workload);

        $gearmanWorker = new \GearmanWorker();
        $gearmanWorker->addServer();

        $gearmanWorker->addFunction('flimp', function ($gearmanJob) {

                $logfile = fopen('/tmp/gearman.txt', 'a+');

                if($logfile) {
                    for($i=0; $i<5; $i++) {
                        sleep(1);
                        $logData = sprintf('[%s]: %s %s', date('d-m-Y h:i:s'), $gearmanJob->workload(), PHP_EOL);
                        fwrite($logfile, $logData);
                    }
                }
            });

        while($gearmanWorker->work());
    }

}


