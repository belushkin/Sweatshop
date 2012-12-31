<?php
use Sweatshop\Queue\RabbitmqQueue;

use Monolog\Handler\StreamHandler;

use Monolog\Logger;

use Sweatshop\Queue\GearmanQueue;

use Sweatshop\Message\Message;

use Sweatshop\Queue\InternalQueue;

use Sweatshop\Sweatshop;

require_once __DIR__.'/../../main.php';

$sweatshop = new Sweatshop();
$logger = new Logger('website');
$logger->pushHandler(new StreamHandler("php://stdout"));
$sweatshop->setLogger($logger);



require_once 'BackgroundPrintWorker.php';

$queue = new RabbitmqQueue($sweatshop,array('max_work_cycles' => 3, 'max_memory_per_thread'=> 10000000));
$worker = new BackgroundPrintWorker($sweatshop);
$queue->registerWorker('topic:test', $worker);
$sweatshop->addQueue($queue);


$sweatshop->runWorkers(array(
	'min_threads_per_queue' => 3	
));