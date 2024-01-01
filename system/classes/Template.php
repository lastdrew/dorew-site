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

use Twig\Extra\String\StringExtension;

class Template
{
    private $twig;

    private $load;

    function __construct()
    {
        $this->load = Container::get(Loader::class);

        $FilesystemLoader = new \Twig\Loader\FilesystemLoader(ROOT . 'templates');
        $twig = new \Twig\Environment($FilesystemLoader);

        $this->addGlobal($twig);
        $this->addFunction($twig);
        $this->addExtension($twig);

        // load extensions
        $this->twig = $twig;
    }

    public function addGlobal($twig)
    {
        // get uri segment
        $uri = preg_replace('#^/#', '', $_SERVER['REQUEST_URI']);
        $uri = explode('?', $uri)[0];
        $uri = explode('/', $uri);
        $twig->addGlobal('uri', $uri);
        $twig->addGlobal('get_uri_segments', $uri);

        $current_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $twig->addGlobal('current_url', $current_url);
        $twig->addGlobal('user_agent', $_SERVER['HTTP_USER_AGENT']);

        $twig->addGlobal('_SERVER', $_SERVER);
        $twig->addGlobal('_SESSION', $_SESSION);
        $twig->addGlobal('_COOKIE', $_COOKIE);

        $twig->addGlobal('dir', [
            'css' => '/css',
            'js' => '/js',
            'img' => '/img'
        ]);
    }

    public function addFunction($twig)
    {
        /**
         * Add function from system/functions.php
         */
        $config = new \Twig\TwigFunction('config', 'config');
        $twig->addFunction($config);
        $display_error = new \Twig\TwigFunction('display_error', 'display_error');
        $twig->addFunction($display_error);

        $is_value = new \Twig\TwigFunction('ctype_digit', 'ctype_digit');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_string', 'is_string');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_array', 'is_array');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_float', 'is_float');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_int', 'is_int');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_iterable', 'is_iterable');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_null', 'is_null');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('is_numeric', 'is_numeric');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('isset', 'isset');
        $twig->addFunction($is_value);
        $is_value = new \Twig\TwigFunction('empty', 'empty');
        $twig->addFunction($is_value);

        $someone_from_php = new \Twig\TwigFunction('exit', 'exit');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('rand', 'rand');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('md5', 'md5');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('time', 'time');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('html_entity_decode', 'html_entity_decode');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('htmlspecialchars', 'htmlspecialchars');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('htmlspecialchars_decode', 'htmlspecialchars_decode');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('mb_strtolower', 'mb_strtolower');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('addslashes', 'addslashes');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('trim', 'trim');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('urlencode', 'urlencode');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('explode', 'explode');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('strtr', 'strtr');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('str_replace', 'str_replace');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('preg_replace', 'preg_replace');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('count', 'count');
        $twig->addFunction($someone_from_php);
        $someone_from_php = new \Twig\TwigFunction('strip_tags', 'strip_tags');
        $twig->addFunction($someone_from_php);

        $redirect = new \Twig\TwigFunction('redirect', 'redirect');
        $twig->addFunction($redirect);
        $generateCSRFToken = new \Twig\TwigFunction('generateCSRFToken', 'generateCSRFToken');
        $twig->addFunction($generateCSRFToken);
        $isCSRFTokenValid = new \Twig\TwigFunction('isCSRFTokenValid', 'isCSRFTokenValid');
        $twig->addFunction($isCSRFTokenValid);

        $user_agent = new \Twig\TwigFunction('user_agent', [request(), 'getUserAgent']);
        $twig->addFunction($user_agent);
        $getIp = new \Twig\TwigFunction('getIp', [request(), 'getIp']);
        $twig->addFunction($getIp);
        $getIpViaProxy = new \Twig\TwigFunction('getIpViaProxy', [request(), 'getIpViaProxy']);
        $twig->addFunction($getIpViaProxy);
        $getIpList = new \Twig\TwigFunction('getIpList', [request(), 'getIpList']);
        $twig->addFunction($getIpList);
        $isAjax = new \Twig\TwigFunction('isAjax', [request(), 'isAjax']);
        $twig->addFunction($isAjax);
        $getMethod = new \Twig\TwigFunction('getMethod', [request(), 'getMethod']);
        $twig->addFunction($getMethod);
        $isPost = new \Twig\TwigFunction('isPost', [request(), 'isPost']);
        $twig->addFunction($isPost);

        $json_decode = new \Twig\TwigFunction('json_decode', 'json_decode');
        $twig->addFunction($json_decode);
        $json_encode = new \Twig\TwigFunction('json_encode', 'json_encode');
        $twig->addFunction($json_encode);

        $url = new \Twig\TwigFunction('url', 'url');
        $twig->addFunction($url);
        $current_url = new \Twig\TwigFunction('current_url', 'current_url');
        $twig->addFunction($current_url);

        $checkExtension = new \Twig\TwigFunction('checkExtension', 'checkExtension');
        $twig->addFunction($checkExtension);
        $pagingConfig = new \Twig\TwigFunction('pagingConfig', 'pagingConfig');
        $twig->addFunction($pagingConfig);
        $ago = new \Twig\TwigFunction('ago', 'ago');
        $twig->addFunction($ago);
        $print_r = new \Twig\TwigFunction('print_r', 'print_r');
        $twig->addFunction($print_r);

        $slug = new \Twig\TwigFunction('slug', 'slug');
        $twig->addFunction($slug);
        $slug = new \Twig\TwigFunction('rwurl', 'slug');
        $twig->addFunction($slug);
    }

    public function addExtension($twig)
    {
        $twig->addExtension(new StringExtension());
        $twig->addExtension($this->load->extension('Select'));
        $twig->addExtension($this->load->extension('Update'));
        $twig->addExtension($this->load->extension('Cookie'));
        $twig->addExtension($this->load->extension('Encrypt'));
        $twig->addExtension($this->load->extension('Request'));
        $twig->addExtension($this->load->extension('Markdown'));
        $twig->addExtension($this->load->extension('ArraySort'));
        $twig->addExtension($this->load->extension('Regex'));
    }

    public function render($file, $data = [], $assets = null)
    {
        try {
            // content type
            $ext_path = explode('.', $file);
            if (count($ext_path) < 2) {
                $check_ext = 'html';
            } else {
                $check_ext = array_pop($ext_path);
            }
            $check_ext = strtolower($check_ext);
            header('Content-Type: ' . self::get_format($check_ext));

            // view
            if (!in_array($assets, MIME_RENDER)) {
                $result = readfile(TPL . '/' . $file);
            } else {
                $result = $this->twig->render($file . '.twig', $data);
            }
        } catch (\Twig\Error\SyntaxError $e) {
            $result = 'Syntax Error: ' . $this->exceptionPath($e->getMessage(), $e->getTemplateLine(), $e->getSourceContext()->getPath());
        } catch (\Twig\Error\RuntimeError $e) {
            $result = 'Runtime Error: ' . $this->exceptionPath($e->getMessage(), $e->getTemplateLine(), $e->getSourceContext()->getPath());
        } catch (\Twig\Error\LoaderError $e) {
            $result = 'Loader Error: ' . $this->exceptionPath($e->getMessage(), $e->getTemplateLine(), $e->getSourceContext()->getPath());
        } catch (\Twig\Error\Error $e) {
            $result = 'Error: ' . $this->exceptionPath($e->getMessage(), $e->getTemplateLine(), $e->getSourceContext()->getPath());
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }

    private function exceptionPath($getMessage = null, $getTemplateLine = null, $getPath = null)
    {
        if (!$getMessage || !$getTemplateLine || !$getPath) {
            return false;
        } else {
            return $getMessage . ' at line ' . $getTemplateLine . ' in: ' . str_replace(TPL, '/TPL/', $getPath);
        }
    }

    public function error()
    {
        return $this->render('error', ['title' => '404 Not Found']);
    }

    public static function get_format($ext)
    {
        $mime = MIME_TYPE;
        if ($mime[$ext]) {
            return $mime[$ext];
        } else {
            return 'text/html';
        }
    }
}
