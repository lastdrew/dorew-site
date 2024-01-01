<?php

use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class MarkdownExtension extends Extension implements ExtensionInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('markdown', [$this, 'markdown'])
        ];
    }
    public function getFilters()
    {
        return [];
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

    public function markdown($string = null, $inline = false) 
    {
        if (!$string) return null;
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        if ($inline) {
            return $Parsedown->line($string);
        } else {
            return $Parsedown->text($string);
        }
    }
}
