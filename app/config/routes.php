<?php

$router = new \Phalcon\Mvc\Router();

$router->add(
    "/d([0-9a-z\-_]+)",
    array(
       "controller" => "links",
       "action"     => "redirect",
        "slug"      => 1,
    )
);

return $router;