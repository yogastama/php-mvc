<?php
function view($view, $data = [])
{
    require_once '../app/views/' . $view . '.php';
}
function redirect($path)
{
    header('Location:' . BASE_URL . '/' . $path);
    exit;
}
