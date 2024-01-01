<?php

function _e(string $text)
{
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    return trim($text);
}

/**
 * Get autoload config
 *
 * @param string|null $path
 * @param mixed $default
 * @return Config|mixed
 */
function config(string $path = null, $default = null)
{
    $config = Container::get(Config::class);

    if (is_null($path)) {
        return $config;
    }

    return $config->get($path, $default);
}

/**
 * Get request instance
 *
 * @return Request
 */
function request()
{
    return Container::get(Request::class);
}

/**
 * Get template instance or render a view
 *
 * @param string|null $template
 * @param array $data
 * @return Template|string
 */
function view(string $template = null, array $data = [])
{
    /** @var Template */
    $view = Container::get(Template::class);

    if (is_null($template)) {
        return $view;
    }

    return $view->render($template, $data);
}

function display_error($error)
{
    if (is_array($error)) {
        if (sizeof($error) === 1) {
            $error = array_pop($error);
        } else {
            $error = '- ' . implode('<br />- ', $error);
        }
    }

    return $error;
}

function redirect(string $uri = '/')
{
    header('Location: ' . SITE_PATH . $uri);
    exit;
}

function url(string $path = '', $absulute = true)
{
    if ($absulute) {
        return SITE_URL . '/' . ltrim($path, '/');
    }

    return (SITE_PATH ? '/' . ltrim(SITE_PATH, '/') : '')
        . '/' . ltrim($path, '/');
}

function is_integer_string($string)
{
    $integer = (int) $string;
    return strval($integer) === $string;
}

function checkExtension($one)
{
    $extension = pathinfo($one, PATHINFO_EXTENSION);

    if (in_array($extension, ['jpg', 'png', 'webp', 'psd', 'heic'])) {
        return 'file-image-o';
    } elseif (in_array($extension, ['mp4', 'mkv', 'webm', 'flv', '3gp'])) {
        return 'file-video-o';
    } elseif (in_array($extension, ['mp3', 'mkv', 'm4a', 'flac', 'wav'])) {
        return 'file-audio-o';
    } elseif (in_array($extension, ['docx', 'doc', 'txt', 'md', 'odt'])) {
        return 'file-text-o';
    } elseif (in_array($extension, ['txt', 'md'])) {
        return 'file-text-o';
    } elseif (in_array($extension, ['docx', 'doc', 'odt'])) {
        return 'file-word-o';
    } elseif (in_array($extension, ['xls', 'xlsx'])) {
        return 'file-excel-o';
    } elseif (in_array($extension, ['ppt', 'pptx'])) {
        return 'file-powerpoint-o';
    } elseif ($extension === 'pdf') {
        return 'file-pdf-o';
    } elseif (in_array($extension, ['zip', 'rar', '7z', 'tar'])) {
        return 'file-archive-o';
    } elseif (in_array($extension, ['cpp', 'cs', 'php', 'html', 'js', 'py'])) {
        return 'file-code-o';
    } elseif ($extension === 'sql') {
        return 'database';
    } else {
        return 'file-o';
    }
}

function ago($time_ago)
{
    $timeht = date('U');
    $time = $time_ago;
    $time_giay = $timeht - $time;
    $time_phut = floor($time_giay / 60);
    $time_day = date('z', $timeht) - date('z', $time);
    $fulltime = date('d.m.Y - H:i', $time_ago);
    $minitime = date('H:i', $time_ago);

    if ($time_day == 0) {
        if ($time_giay <= 60) {
            return $time_giay . ' giây trước';
        } elseif ($time_phut <= 60) {
            return $time_phut . ' phút trước';
        } else {
            return 'Hôm nay, ' . $minitime;
        }
    } elseif ($time_day == 1) {
        return 'Hôm qua, ' . $minitime;
    } else {
        return $fulltime;
    }
}

function pagingConfig($total = 0, $get = 'page', $per = 10)
{
    $page_max = round($total / $per);
    $page = (isset($_GET[$get]) && $_GET[$get] > 0) ? $_GET[$get] : 1;
    if ($page >= $page_max) {
        $page = $page_max;
    }
    if ($page < 1) {
        $page = 1;
    }
    $start = ($page - 1) * $per;
    return [
        'start' => $start,
        'end' => $per,
        'page' => $page,
        'page_max' => $page_max
    ];
}

function generateCSRFToken()
{
    $encrypt = bin2hex(random_bytes(32));
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = $encrypt;
    }
    return true;
}

function isCSRFTokenValid($token)
{
    $output = null;
    $isCSRFTokenValid = isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
    if (!$isCSRFTokenValid) {
        $output = 'Thao tác không hợp lệ';
    }
    return $output;
}

function slug($string)
{
    $slugger = new \Symfony\Component\String\Slugger\AsciiSlugger();
    $slug = $slugger->slug($string)->lower();
    return $slug;
}

function current_url()
{
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}
