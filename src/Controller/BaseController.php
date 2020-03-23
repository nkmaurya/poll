<?php
namespace App\Controller;

/**
 * BaseController
 */
class BaseController
{
    
    /**
     * request
     *
     * @var mixed
     */
    private $request;
   
    /**
     * utility
     *
     * @var mixed
     */
    private $utility;

    
    /**
     * renderView
     *
     * @param  mixed $viewPath
     * @param  mixed $result
     * @param  mixed $isExtract
     * @return void
     */
    protected function renderView($viewPath, $result=null, $isExtract = true)
    {
        $isExtract ? extract($result):$result;
        ob_start();
        require_once($viewPath);
        $content=ob_get_contents();
        ob_end_clean();
        echo $content;
    }
}
