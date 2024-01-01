<?php

/**
 * Developer: valedrat
 * Email: khanh65me1@gmail.com
 * 
 * Product: DorewSite for Wap4
 * Release date: 2023-12-27
 * Version: 0.2.0-RC1
 * 
 * License: MIT License (http://www.opensource.org/licenses/mit-license)
 */

class Kernel
{
    public function run(Request $request)
    {
        $this->removeHeaders();

        /** @var Router */
        $router = Container::get(Router::class);

        $router->setAllowedMethods($request->getAllowedMethods());
        $router->setBasePath(SITE_PATH);

        $this->matchRoute($request, $router);

        /** @var Template */
        $view = Container::get(Template::class);

        die($view->error());
    }

    protected function matchRoute(Request $request, Router $router)
    {
        $router->match($request->getMethod(), $request->getRoute());

        $callable = $router->getRequestParams();

        if ($callable) {
            $callback = $callable['callback'];
            $result = null;

            if (is_array($callback)) {
                $controllerObj = Container::get($callback['controller']);

                if ($controllerObj) {
                    if (method_exists($controllerObj, $callback['method'])) {
                        $result = call_user_func_array([$controllerObj, $callback['method']], $callable['params']);
                    }
                }
            } else {
                $result = call_user_func($callback, ...$callable['params']);
            }

            if ($result) {
                if (is_array($result)) {
                    header('Content-Type: application/json');
                    echo json_encode($result);
                } elseif ($result instanceof GdImage) {
                    header('Content-Type: image/png');
                    imagepng($result);
                    imagedestroy($result);
                } else {
                    echo $result;
                }

                exit;
            }
        }
        /** @var Template */
        $view = Container::get(Template::class);

        if ($view->error()) {
            //header('HTTP/1.1 404 Not Found', true, 404);
            die($view->error());
        }
    }

    protected function removeHeaders()
    {
        foreach (headers_list() as $header) {
            if (strpos(strtolower($header), 'x-powered-by:') !== false) {
                header_remove('x-powered-by');
            }
        }
    }
}
