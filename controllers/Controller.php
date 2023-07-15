<?php


namespace controllers;


class Controller
{
    protected function render($view, $data = null)
    {
        ob_start();
            $result = $data;
            require(__DIR__."/../views/$view.php");
        return ob_get_clean();
    }
}