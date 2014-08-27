<?php
namespace Fallback;

use Nicklasos\Router\App as Router;

class Application
{
    private $getContentFun;
    private $getId;
    private $onError;
    private $onSuccess;
    private $on404;
    private $config;
    private $dynamo;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->dynamo = new Dynamo($config['aws']);
    }

    public function getContent(callable $fun)
    {
        $this->getContentFun = $fun;
    }

    public function onError(callable $onError)
    {
        $this->onError = $onError;
    }

    public function onSuccess(callable $onSuccess)
    {
        $this->onSuccess = $onSuccess;
    }

    public function on404(callable $on404)
    {
        $this->on404 = $on404;
    }

    public function getId(callable $getId)
    {
        $this->getId = $getId;
    }

    public function run()
    {
        $router = new Router();

        $router->get($this->config['save-url'], function () {
            $getContent = $this->getContentFun;
            $content = $getContent();

            $content = $this->addAttributes($content);

            if ($this->dynamo->save($content)) {
                $success = $this->onSuccess;
                return $success();
            } else {
                $error = $this->onError;
                return $error();
            }
        });

        /*
        $router->get($this->config['stats-url'], function () {
            header('Content-Type: application/json');
            return json_encode($this->dynamo->getStats());
        });
        */

        $on404 = $this->on404;
        $router->notFound($on404);

        $router->run();
    }

    public function restore()
    {

    }

    /**
     * Add default fields to data, ip, time, etc...
     * @param array $content
     * @return array
     */
    private function addAttributes($content)
    {
        $getId           = $this->getId;
        $content['ip']   = get_client_ip();
        $content['id']   = $getId();
        $content['time'] = time();

        return $content;
    }
}
