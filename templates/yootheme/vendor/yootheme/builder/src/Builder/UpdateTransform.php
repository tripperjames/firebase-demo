<?php

namespace YOOtheme\Builder;

class UpdateTransform
{
    /**
     * @var string
     */
    public $version;

    /**
     * @var array
     */
    public $updates = [];

    /**
     * Constructor.
     *
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Transform callback.
     *
     * @param object $node
     * @param array  $params
     */
    public function __invoke($node, array &$params)
    {
        if (isset($node->version)) {
            $params['version'] = $node->version;
        } elseif (empty($params['version'])) {
            $params['version'] = '1.0.0';
        }

        if ($node->type === 'layout') {
            $node->version = $this->version;
        } else {
            unset($node->version);
        }

        /**
         * @var $type
         * @var $version
         */
        extract($params);

        // check node version
        if (version_compare($version, $this->version, '>=') || empty($type->updates)) {
            return;
        }

        // apply update callbacks
        foreach ($this->resolveUpdates($type, $version) as $update) {
            $update($node, $params);
        }
    }

    /**
     * Resolves updates for a type and current version.
     *
     * @param  object $type
     * @param  string $version
     * @return array
     */
    protected function resolveUpdates($type, $version)
    {
        if (!isset($this->updates[$type->name][$version])) {

            $this->updates[$type->name][$version] = [];

            foreach ($type->updates as $ver => $update) {
                if (version_compare($ver, $version, '>') && is_callable($update)) {
                    $this->updates[$type->name][$version][$ver] = $update;
                }
            }

            uksort($this->updates[$type->name][$version], 'version_compare');
        }

        return $this->updates[$type->name][$version];
    }
}
