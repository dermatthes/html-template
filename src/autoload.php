<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 09.06.16
     * Time: 11:34
     *
     *
     *
     */

    namespace Gismo\Component\Template;



    
    spl_autoload_register(function ($class) {
        if (substr($class, 0, strlen(__NAMESPACE__)) != __NAMESPACE__)
            return;
        $path = __DIR__ . "/phbeam/" . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
        require_once($path);
    });