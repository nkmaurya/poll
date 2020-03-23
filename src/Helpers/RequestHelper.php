<?php
namespace App\Helpers;

use App\Helpers\UtilityHelper;
use App\Helpers\SessionHelper;

/**
 * RequestHelper
 */
class RequestHelper
{
    
    /**
     * action
     *
     * @var mixed
     */
    private $action;
    
    /**
     * requestData
     *
     * @var mixed
     */
    public $requestData;
    
    /**
     * method
     *
     * @var mixed
     */
    private $method;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        try {
            $this->method = $_SERVER['REQUEST_METHOD'];
        } catch (Exception $e) {
            die("Something went wrong - ". $e->getMessage());
        }
    }
    
    /**
     * processRequet
     *
     * @return void
     */
    public function processRequet()
    {
        switch ($this->method) {
            case 'GET':
              $this->processGetRequet();
              break;
            case 'POST':
              $this->processPostRequet();
              break;
            default:
              die("Unable to haddle request");
              break;
          }
    }
    
    /**
     * processGetRequet
     *
     * @return void
     */
    public function processGetRequet()
    {
        $data = $_GET;
        $data = UtilityHelper::sanitizeData($data);
        $this->requestData = $data;
    }
    
    /**
     * processPostRequet
     *
     * @return void
     */
    public function processPostRequet()
    {
        $data = $_POST;
        $session = new SessionHelper();
        if ($session->tokenExpired($data)) {
            //UtilityHelper::redirect("?action=list&sessionExpired=1");
        }
        $data = UtilityHelper::sanitizeData($data);
        $this->requestData = $data;
    }
    
    /**
     * __call
     *
     * @param  mixed $method
     * @param  mixed $arguments
     * @return void
     */
    public function __call($method, $arguments)
    {
        $this->processRequet();
        $data = $this->requestData;
        if (count($arguments) > 0) {
            if (isset($data[$arguments[0]])) {
                $data = $data[$arguments[0]];
            } else {
                $this->requestData;
            }
        }
        return $data;
    }
    
    /**
     * sanitizeString
     *
     * @param  mixed $string
     * @return void
     */
    private function sanitizeString($string)
    {
        return $string = filter_var($string, FILTER_SANITIZE_STRING);
    }
    
    /**
     * isPOST
     *
     * @return void
     */
    public function isPOST()
    {
        return $this->method == "POST";
    }
    
    /**
     * isGet
     *
     * @return void
     */
    public function isGet()
    {
        return $this->method == "GET";
    }
}
