<?php

namespace YOOtheme\View;

use YOOtheme\View;

class MetadataHelper implements \IteratorAggregate
{
    const METAS = '/^(article|fb|og|twitter):/i';
    const EQUIV = '/^(content-type|default-style|refresh|x-ua-compatible)/i';
    const LINKS = '/^(alternate|canonical|help|icon|license|next|prefetch|prev|search|shortcut)/i';

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * Register helper.
     *
     * @param View $view
     */
    public function register(View $view)
    {
        $view->addFunction('meta', [$this, 'add']);
        $view['sections']->add('head', [$this, 'render']);
    }

    /**
     * Gets a metadata tag.
     *
     * @param  string $name
     * @return string|array
     */
    public function get($name)
    {
        return isset($this->metadata[$name]) ? $this->metadata[$name] : null;
    }

    /**
     * Adds a metadata tag.
     *
     * @param string $name
     * @param string $value
     */
    public function add($name, $value)
    {
        $this->metadata[$name] = $value;
    }

    /**
     * Merges multiple metadata tags.
     *
     * @param array $metadata
     */
    public function merge(array $metadata)
    {
        $this->metadata = array_replace($this->metadata, $metadata);
    }

    /**
     * Renders metadata tags.
     *
     * @return string
     */
    public function render()
    {
        $output = [];

        foreach ($this->metadata as $name => $value) {

            $value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');

            if ($name == 'title') {
                $output[] = "<title>{$value}</title>";
            } elseif ($name == 'base') {
                $output[] = "<base href=\"{$value}\">";
            } elseif (preg_match(static::LINKS, $name)) {
                $output[] = "<link rel=\"{$name}\" href=\"{$value}\">";
            } elseif (preg_match(static::METAS, $name)) {
                $output[] = "<meta property=\"{$name}\" content=\"{$value}\">";
            } elseif (preg_match(static::EQUIV, $name)) {
                $output[] = "<meta http-equiv=\"{$name}\" content=\"{$value}\">";
            } else {
                $output[] = "<meta name=\"{$name}\" content=\"{$value}\">";
            }
        }

        return $output ? implode("\n", $output)."\n" : '';
    }

    /**
     * Returns an iterator for metadata tags.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->metadata);
    }
}
