<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    const JOBNAME = 'testJob';

    public function indexAction()
    {
        return new ViewModel();
    }

    public function testGearManAction()
    {
        $data = $this->getRequest()->getContent();

        $this
            ->serviceLocator
            ->get('GearmanListener')
            ->addJobToQueue($data[1], self::JOBNAME);

        return new ViewModel();
    }

    public function testGearManWorkerAction()
    {
        $this
            ->serviceLocator
            ->get('GearmanListener')
            ->writeToLog(self::JOBNAME);

        return new ViewModel();
    }
}
