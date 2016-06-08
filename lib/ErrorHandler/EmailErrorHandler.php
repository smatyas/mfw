<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.08.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\Mfw\ErrorHandler;

class EmailErrorHandler
{
    /**
     * The configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Creates a new EmailErrorHandler instance.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Initializes the error handler.
     */
    public function init()
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleFatal']);
    }

    protected function sendMail($to, $subject, $message)
    {
        mail($to, $subject, $message);
    }

    public function handleException(\Exception $exception)
    {
        $this->sendMail($this->config['to'], 'Uncaught exception', print_r($exception, true));
    }

    public function handleError(...$params)
    {
        $this->sendMail($this->config['to'], 'Error', print_r($params, true));
        return false; // Let the default error handler do its job too.
    }

    public function handleFatal()
    {
        $error = error_get_last();
        if ($error['type'] === E_ERROR) {
            $this->sendMail($this->config['to'], 'Fatal error', print_r($error, true));
        }
    }
}
