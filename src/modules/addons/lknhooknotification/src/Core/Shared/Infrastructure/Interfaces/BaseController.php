<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Interfaces;

use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;

class BaseController
{
    protected string $viewsBasePath;
    public View $view;

    public function __construct(View $view)
    {
        $this->viewsBasePath = dirname((new \ReflectionClass($this))->getFileName()) . '/../Views';
        $this->view          = $view;
        $this->view->setTemplateDir($this->viewsBasePath);
    }
}
