<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFilter;

class RegexExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [];
    }
    public function getFilters()
    {
        return [
            new TwigFilter('preg_quote', [$this, 'quote']),
            new TwigFilter('preg_match', [$this, 'match']),
            new TwigFilter('preg_get', [$this, 'get']),
            new TwigFilter('preg_get_all', [$this, 'getAll']),
            new TwigFilter('preg_grep', [$this, 'grep']),
            new TwigFilter('preg_replace', [$this, 'replace']),
            new TwigFilter('preg_filter', [$this, 'filter']),
            new TwigFilter('preg_split', [$this, 'split']),

            new TwigFilter('paragraph', [$this, 'paragraph'], ['pre_escape' => 'html', 'is_safe' => ['html']]),
            new TwigFilter('line', [$this, 'line']),
            new TwigFilter('less', [$this, 'less'], ['pre_escape' => 'html', 'is_safe' => ['html']]),
            new TwigFilter('linkify', [$this, 'linkify'], ['pre_escape' => 'html', 'is_safe' => ['html']]),
        ];
    }

    public function getTokenParsers()
    {
        return [];
    }
    public function getNodeVisitors()
    {
        return [];
    }
    public function getTests()
    {
        return [];
    }
    public function getOperators()
    {
        return [];
    }

    public function twig_array_unique($array)
    {
        return array_unique($array);
    }

    public function paragraph($value)
    {
        if (!isset($value)) {
            return null;
        }
        return '<p>' . preg_replace(['~\n(\s*)\n\s*~', '~(?<!</p>)\n\s*~'], ["</p>\n\$1<p>", "<br>\n"], trim($value)) . '</p>';
    }


    /**
     * Get a single line
     *
     * @param string $value
     * @param int    $line   Line number (starts at 1)
     * @return string
     */
    public function line($value, $line = 1)
    {
        if (!isset($value)) {
            return null;
        }
        $lines = explode("\n", $value);
        return isset($lines[$line - 1]) ? $lines[$line - 1] : null;
    }


    /**
     * Cut of text on a pagebreak.
     *
     * @param string $value
     * @param string $replace
     * @param string $break
     * @return string
     */
    public function less($value, $replace = '...', $break = '<!-- pagebreak -->')
    {
        if (!isset($value)) {
            return null;
        }
        $pos = stripos($value, $break);
        return $pos === false ? $value : substr($value, 0, $pos) . $replace;
    }


    /**
     * Linkify a HTTP(S) link.
     *
     * @param string $protocol  'http' or 'https'
     * @param string $text
     * @param array  $links     OUTPUT
     * @param string $attr
     * @param string $mode
     * @return string
     */
    protected function linkifyHttp($protocol, $text, array &$links, $attr, $mode)
    {
        $regexp = $mode != 'all'
            ? '~(?:(https?)://([^\s<>]+)|(?<!\w@)\b(www\.[^\s<>]+?\.[^\s<>]+))(?<![\.,:;\?!\'"\|])~i'
            : '~(?:(https?)://([^\s<>]+)|(?<!\w@)\b([^\s<>@]+?\.[^\s<>]+)(?<![\.,:]))~i';

        return preg_replace_callback($regexp, function ($match) use ($protocol, &$links, $attr) {
            if ($match[1]) {
                $protocol = $match[1];
            }
            $link = $match[2] ?: $match[3];
            return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . '://' . $link . '">'
                . rtrim($link, '/') . '</a>') . '>';
        }, $text);
    }


    /**
     * Linkify a mail link.
     *
     * @param string $text
     * @param array  $links     OUTPUT
     * @param string $attr
     * @return string
     */
    protected function linkifyMail($text, array &$links, $attr)
    {
        $regexp = '~([^\s<>]+?@[^\s<>]+?\.[^\s<>]+)(?<![\.,:;\?!\'"\|])~';
        return preg_replace_callback($regexp, function ($match) use (&$links, $attr) {
            return '<' . array_push($links, '<a' . $attr . ' href="mailto:' . $match[1] . '">' . $match[1] . '</a>')
                . '>';
        }, $text);
    }


    /**
     * Linkify a link.
     *
     * @param string $protocol
     * @param string $text
     * @param array  $links     OUTPUT
     * @param string $attr
     * @param string $mode
     * @return string
     */
    protected function linkifyOther($protocol, $text, array &$links, $attr, $mode)
    {
        if (strpos($protocol, ':') === false) {
            $protocol .= in_array($protocol, ['ftp', 'tftp', 'ssh', 'scp']) ? '://' : ':';
        }
        $regexp = $mode != 'all'
            ? '~' . preg_quote($protocol, '~') . '([^\s<>]+)(?<![\.,:;\?!\'"\|])~i'
            : '~([^\s<>]+)(?<![\.,:])~i';
        return preg_replace_callback($regexp, function ($match) use ($protocol, &$links, $attr) {
            return '<' . array_push($links, '<a' . $attr . ' href="' . $protocol . $match[1] . '">' . $match[1]
                . '</a>') . '>';
        }, $text);
    }


    /**
     * Turn all URLs in clickable links.
     *
     * @param string $value
     * @param array  $protocols   'http'/'https', 'mail' and also 'ftp', 'scp', 'tel', etc
     * @param array  $attributes  HTML attributes for the link
     * @param string $mode        normal or all
     * @return string
     */
    public function linkify($value, $protocols = ['http', 'mail'], array $attributes = [], $mode = 'normal')
    {
        if (!isset($value)) {
            return null;
        }
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
        }
        $links = [];
        // Extract existing links and tags
        $text = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) {
            return '<' . array_push($links, $match[1]) . '>';
        }, $value);
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':
                    $text = $this->linkifyHttp($protocol, $text, $links, $attr, $mode);
                    break;
                case 'mail':
                    $text = $this->linkifyMail($text, $links, $attr);
                    break;
                default:
                    $text = $this->linkifyOther($protocol, $text, $links, $attr, $mode);
                    break;
            }
        }
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) {
            return $links[$match[1] - 1];
        }, $text);
    }


    /**
     * Check that the regex doesn't use the eval modifier
     *
     * @param string $pattern
     * @throws \LogicException
     */
    protected function assertNoEval($pattern)
    {
        $pos = strrpos($pattern, $pattern[0]);
        $modifiers = substr($pattern, $pos + 1);
        if (strpos($modifiers, 'e') !== false) {
            throw new \Twig\Error\RuntimeError("Using the eval modifier for regular expressions is not allowed");
        }
    }


    /**
     * Quote regular expression characters.
     *
     * @param string $value
     * @param string $delimiter
     * @return string
     */
    public function quote($value, $delimiter = '/')
    {
        if (!isset($value)) {
            return null;
        }
        return preg_quote($value, $delimiter);
    }


    /**
     * Perform a regular expression match.
     *
     * @param string $value
     * @param string $pattern
     * @return boolean
     */
    public function match($value, $pattern)
    {
        if (!isset($value)) {
            return null;
        }
        return preg_match($pattern, $value);
    }


    /**
     * Perform a regular expression match and return a matched group.
     *
     * @param string $value
     * @param string $pattern
     * @return string
     */
    public function get($value, $pattern, $group = 0)
    {
        if (!isset($value)) {
            return null;
        }
        return preg_match($pattern, $value, $matches) && isset($matches[$group]) ? $matches[$group] : null;
    }


    /**
     * Perform a regular expression match and return the group for all matches.
     *
     * @param string $value
     * @param string $pattern
     * @return array
     */
    public function getAll($value, $pattern, $group = 0)
    {
        if (!isset($value)) {
            return null;
        }
        return preg_match_all($pattern, $value, $matches, PREG_PATTERN_ORDER) && isset($matches[$group])
            ? $matches[$group] : [];
    }


    /**
     * Perform a regular expression match and return an array of entries that match the pattern
     *
     * @param array  $values
     * @param string $pattern
     * @param string $flags    Optional 'invert' to return entries that do not match the given pattern.
     * @return array
     */
    public function grep($values, $pattern, $flags = '')
    {
        if (!isset($values)) {
            return null;
        }
        if (is_string($flags)) {
            $flags = $flags === 'invert' ? PREG_GREP_INVERT : 0;
        }
        return preg_grep($pattern, $values, $flags);
    }


    /**
     * Perform a regular expression search and replace.
     *
     * @param string $value
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     * @return string
     */
    public function replace($value, $pattern, $replacement = '', $limit = -1)
    {
        $this->assertNoEval($pattern);
        if (!isset($value)) {
            return null;
        }
        return preg_replace($pattern, $replacement, $value, $limit);
    }


    /**
     * Perform a regular expression search and replace, returning only matched subjects.
     *
     * @param string $value
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     * @return string
     */
    public function filter($value, $pattern, $replacement = '', $limit = -1)
    {
        $this->assertNoEval($pattern);
        if (!isset($value)) {
            return null;
        }
        return preg_filter($pattern, $replacement, $value, $limit);
    }


    /**
     * Split text into an array using a regular expression.
     *
     * @param string $value
     * @param string $pattern
     * @return array
     */
    public function split($value, $pattern)
    {
        if (!isset($value)) {
            return null;
        }
        return preg_split($pattern, $value);
    }
}
