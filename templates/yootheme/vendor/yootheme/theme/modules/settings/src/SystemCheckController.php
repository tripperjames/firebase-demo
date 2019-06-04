<?php

namespace YOOtheme\Theme;

use YOOtheme\ContainerTrait;

class SystemCheckController
{
    use ContainerTrait;

    protected $inject = [
        'systemcheck' => 'app.systemcheck',
    ];

    public function index($request, $response)
    {
        return $response->withJson($this->systemcheck);
    }
}
