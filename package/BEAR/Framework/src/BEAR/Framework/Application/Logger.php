<?php
/**
 *  BEAR.Framework
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Framework\Application;

use BEAR\Framework\AppContext;

use BEAR\Framework\Application\ResourceLogIterator;
use BEAR\Framework\Application\Fireable;
use BEAR\Resource\LoggerInterface as ResourceLoggerInterface;
use BEAR\Resource\Logger as ResourceLogger;

/**
 * Logger
 *
 * @package    BEAR.Framework
 * @subpackage Log
 * @author     Akihito Koriyama <akihito.koriyama@gmail.com>
 */
final class Logger implements LoggerInterface
{
    /**
     * Resorce logger
     *
     * @var ResourceLogger
     */
    private $resourceLogger;

    /**
     * Set resource logger
     *
     * @param ResourceLoggerInterface $logger
     *
     * @Inject
     */
    public function __construct(ResourceLoggerInterface $resourceLogger){
        $this->resourceLogger = $resourceLogger;
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Framework\Application.LoggerInterface::log()
     */
    private function logOnShutdown(AppContext $app)
    {
        $logs = new ResourceLogIterator($this->resourceLogger);
        foreach ($logs as $log) {
            $log->apcLog();
        }
        // @todo to enable store $app, eliminate all unserializable object.
        // apc_store('request-' . get_class($app), var_export($app, true));
    }

    /**
     * (non-PHPdoc)
     * @see BEAR\Framework\Application.LoggerInterface::register()
     */
    public function register(AppContext $app)
    {
        register_shutdown_function(function() use ($app){
            $logOnShutdown = [$this, 'logOnShutdown'];
            $logOnShutdown($app);
        });
    }

    /**
     * Output web console log (FirePHP log)
     *
     * @return void
     */
    public function outputWebConsoleLog()
    {
        $logs = new ResourceLogIterator($this->resourceLogger);
        foreach ($logs as $log) {
            $log->fire();
        }
    }
}
