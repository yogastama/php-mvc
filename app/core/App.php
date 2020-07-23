<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();
        // cek dulu apakah ada controller nya
        if (file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        // params
        if (!empty($url)) {
            $this->params = array_values($url);
        }
        // jalankan controller & method dan params
        // function untuk menjalankan sebuah method dan mengirimkan dan param
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            // filter_url berfungsi untuk menghilangkan simbol dari karakter yang tidak sesuai untuk url
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
