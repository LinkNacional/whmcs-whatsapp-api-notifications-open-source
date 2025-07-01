<?php

namespace Lkn\HookNotification\Core\AdminUI\Infrastructure;

use Lkn\HookNotification\Core\Shared\Infrastructure\View\View;
use WHMCS\Database\Capsule;

final class AdminUIRenderer
{
    private array $routes;
    private string $navbarPath;

    public function __construct()
    {
        $this->routes     =  require_once __DIR__ . '/endpoints.php';
        $this->navbarPath = __DIR__ . '/navbar.php';
    }

    public function getView(string $endpoint): string
    {
        $matched = $this->extractRouteParams($endpoint);

        $view = new View();

        if ($matched === null) {
            $view->setTemplateDir(__DIR__ . '/../Http/Views');

            return $view->view('pages/404')->render();
        }

        $matchedEndpoint                      = $matched['matchedEndpoint'];
        $endpointParams                       = $matched['endpointParams'];
        [$controllerClass, $controllerMethod] = $this->routes[$matchedEndpoint]['class'];


        $controllerInstance = new $controllerClass($view);

        $postParams = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $getParams  = $_GET;

        unset($postParams['token']);
        unset($getParams['module']);
        unset($getParams['page']);

        $request = ['request' => array_merge($postParams, $getParams)];

        $controllerMethodParams = array_merge($endpointParams, $request);

        $controllerOutput = call_user_func(
            [$controllerInstance, $controllerMethod],
            ...$controllerMethodParams
        );

        $controllerInstance->view->assign('lkn_hn', [
            'system_url' => Capsule::table('tblconfiguration')->where('setting', 'SystemURL')->first(['value'])->value,
            'navbar' => require $this->navbarPath,
            'current_endpoint' => $endpoint,
        ]);

        return $controllerInstance->view->render();
    }

    private function extractRouteParams(string $received): ?array
    {
        $endpoints = array_keys($this->routes);

        foreach ($endpoints as $endpoint) {
            $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {
                return '(?P<' . $matches[1] . '>[^\/]+)';
            }, $endpoint);

            if (preg_match('#^' . $pattern . '$#', $received, $matches)) {
                return [
                    'matchedEndpoint' => $endpoint,
                    'endpointParams' => array_values(array_filter(
                        $matches,
                        'is_string',
                        ARRAY_FILTER_USE_KEY
                    )),
                ];
            }
        }

        return null;
    }
}
