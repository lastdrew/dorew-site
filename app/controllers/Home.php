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

class HomeController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index($uri = 'index')
    {
        // Xử lý $uri để lấy phần đoạn đầu tiên và thực hiện biến đổi
        $uri = preg_replace('#^/#', '', $uri); // Loại bỏ ký tự '/' đầu tiên (nếu có)
        $uri = explode('?', $uri)[0]; // Loại bỏ phần query string (nếu có)
        $uriSegments = explode('/', $uri);
        $firstSegment = isset($uriSegments[0]) ? $uriSegments[0] : 'index';
        $path = implode('/', $uriSegments);

        // content type
        $ext_path = explode('.', $uri);
        if (count($ext_path) < 2) {
            $check_ext = 'html';
        } else {
            $check_ext = array_pop($ext_path);
        }
        $check_ext = strtolower($check_ext);

        if (in_array($check_ext, MIME_RENDER)) {
            $arr = [];
            $this->page_exists($firstSegment . '.twig', $arr);
            return $this->view->render($firstSegment, $arr);
        } else {
            $this->page_exists($path);
            return $this->view->render($path, [], $check_ext);
        }
    }

    public function error($error_page = 'error', $arr = ['title' => 'Page not found'])
    {
        if (!file_exists($error_page)) {
            die('Page not found');
        } else {
            return $this->view->render($error_page, $arr);
        }
    }

    public function page_exists($path = null, $arr = [])
    {
        if (!file_exists(TPL . $path)) {
            return $this->error();
        }
    }

    public function plugin($plugin = null)
    {
        if (file_exists(TPL . '/plugin/' . $plugin . '.twig')) {
            return $this->view->render('plugin/' . $plugin);
        } else {
            return null;
        }
    }
}
