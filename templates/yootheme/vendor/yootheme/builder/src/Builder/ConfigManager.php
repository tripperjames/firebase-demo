<?php

namespace YOOtheme\Builder;

use YOOtheme\Util\Arr;

class ConfigManager
{
    const REGEX_ALIAS = '/^(~~?|\.\.?)\//';
    const REGEX_REPLACE = '/\${(\$?(\w+(?:\.\w+)*)(?::[^}]*)?)}/';
    const CONFIG_GLOBAL = '@global';

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $cache;

    /**
     * @var int
     */
    protected $ctime;

    /**
     * @var \ArrayObject
     */
    protected $config;

    /**
     * @var \ArrayObject
     */
    protected $params;

    /**
     * Constructor.
     *
     * @param string $cache
     * @param array  $params
     */
    public function __construct($cache = null, array $params = [])
    {
        $config = [
            'env' => $_ENV,
            'server' => $_SERVER,
            'globals' => $GLOBALS,
            self::CONFIG_GLOBAL => [],
        ];

        if (is_dir($cache)) {
            $this->cache = $cache;
            $this->ctime = filectime(__FILE__);
        }

        $this->config = new \ArrayObject($config);
        $this->params = new \ArrayObject($params);
    }

    /**
     * Gets a configuration value.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $parts = explode(':', $key, 2);

        // get value from root
        if (!isset($parts[1])) {
            return Arr::get($this->config[self::CONFIG_GLOBAL], $key, $default);
        }

        // get value from namespace
        if (isset($this->config[$parts[0]])) {

            $config = $this->config[$parts[0]];

            if (is_callable($config)) {
                return $config($parts[1], $this->file, $this);
            }

            if ($parts[1] === '') {
                return $config;
            }

            return Arr::get($config, $parts[1], $default);
        }

        return $default;
    }

    /**
     * Adds a configuration file, array or callback.
     *
     * @param  string $namespace
     * @param  mixed  $config
     * @throws \RuntimeException
     * @return self
     */
    public function add($namespace, $config)
    {
        // load file?
        if (is_string($config)) {
            $config = $this->load($config);
        }

        // merge array?
        if (isset($this->config[$namespace]) && !is_callable($this->config[$namespace])) {
            $this->config[$namespace] = array_merge($this->config[$namespace], $config);
        } else {
            $this->config[$namespace] = $config;
        }

        return $this;
    }

    /**
     * Loads a configuration.
     *
     * @param  string $file
     * @param  object $context
     * @throws \RuntimeException
     * @return array
     */
    public function load($file, $context = null)
    {
        // parse file info
        $file = strtr($file, '\\', '/');
        $file = pathinfo($file) + ['pathname' => $file];

        // create context
        if (!$context) {
            $context = new \ArrayObject();
            $context->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        }

        // load config file
        $config = $this->loadFile($file, $context);
        $config = $this->resolveImport($config, $context);

        // exchange context array
        if ($context instanceof \ArrayObject) {
            $context->exchangeArray($config);
        }

        return $config;
    }

    /**
     * Loads a configuration file.
     *
     * @param  array  $file
     * @param  object $context
     * @throws \RuntimeException
     * @return array|null
     */
    protected function loadFile(array $file, $context)
    {
        $clone = clone $this;
        $clone->file = $file['pathname'];

        if ($file['extension'] === 'php') {
            return $clone->loadPhpFile($file, $context);
        }

        if ($file['extension'] === 'json') {
            return $clone->loadJsonFile($file, $context);
        }
    }

    /**
     * Loads a PHP configuration file.
     *
     * @param  array  $file
     * @param  object $context
     * @return array
     */
    protected function loadPhpFile(array $file, $context)
    {
        $closure = function ($file, $config, $_params) {

            // extract params to scope
            extract($_params->getArrayCopy(), EXTR_SKIP);

            // include config file
            if (!is_array($data = @include($file['pathname']))) {
                throw new \RuntimeException("Unable to load file '{$file['pathname']}'");
            }

            return $data;
        };

        // bind context object
        $include = $closure->bindTo($context);

        return $include($file, $this, $this->params);
    }

    /**
     * Loads a JSON configuration file.
     *
     * @param  array  $file
     * @param  object $context
     * @throws \RuntimeException
     * @return array
     */
    protected function loadJsonFile(array $file, $context)
    {
        $hash = hash('crc32b', $file['pathname']);
        $cache = "{$this->cache}/{$file['filename']}-{$hash}.php";

        if ($this->cache && file_exists($cache) && filectime($cache) > max($this->ctime, filectime($file['pathname']))) {
            return include($cache);
        }

        if (!$content = @file_get_contents($file['pathname'])) {
            throw new \RuntimeException("Unable to load file '{$file['pathname']}'");
        }

        if (!is_array($data = @json_decode($content, true))) {
            throw new \RuntimeException("Invalid JSON format in '{$file['pathname']}'");
        }

        $export = [$this, 'exportVariable'];
        $banner = "<?php // @file %s\n\nreturn %s;\n";

        if ($this->cache && file_put_contents($cache, sprintf($banner, $file['pathname'], self::exportValue($data, $export)))) {
            return include($cache);
        }

        return $this->replaceVariables($data, compact('file'));
    }

    /**
     * Resolves "@import" in configuration.
     *
     * @param  array  $config
     * @param  object $context
     * @throws \RuntimeException
     * @return array
     */
    protected function resolveImport(array $config, $context)
    {
        $imports = isset($config['@import']) ? (array) $config['@import'] : [];

        foreach ($imports as $import) {
            $config = array_merge($config, $this->load($import, $context));
        }

        unset($config['@import']);

        return $config;
    }

    /**
     * Replaces aliases a in matches.
     *
     * @param  array $matches
     * @return string
     */
    protected function replaceAliases(array $matches)
    {
        return strtr($matches[0], [
            '.' => '${$file.dirname}',
            '..' => '${$file.dirname}/..',
        ]);
    }

    /**
     * Replaces variables a in configuration.
     *
     * @param  mixed $value
     * @param  array $params
     * @return mixed
     */
    protected function replaceVariables($value, array $params = [])
    {
        if (is_string($value)) {

            $value = preg_replace_callback(self::REGEX_ALIAS, [$this, 'replaceAliases'], $value, 1);

            if (preg_match_all(self::REGEX_REPLACE, $value, $matches, PREG_SET_ORDER)) {

                $replace = [];

                if ($value == $matches[0][0]) {
                    return $matches[0][1][0] === '$' ? Arr::get($params, $matches[0][2]) : $this->get($matches[0][1]);
                }

                foreach ($matches as $match) {
                    $replace[$match[0]] = $match[1][0] === '$' ? Arr::get($params, $match[2]) : $this->get($match[1]);
                }

                return strtr($value, $replace);
            }
        }

        if (is_array($value)) {

            foreach ($value as &$val) {
                $val = $this->replaceVariables($val, $params);
            }

        }

        return $value;
    }

    /**
     * Exports variable as parsable string.
     *
     * @param  mixed $value
     * @return string
     */
    protected function exportVariable($value)
    {
        if (is_string($value)) {

            $value = preg_replace_callback(self::REGEX_ALIAS, [$this, 'replaceAliases'], $value, 1);

            if (preg_match_all(self::REGEX_REPLACE, $value, $matches, PREG_SET_ORDER)) {

                $replace = ['"' => '\"'];
                $resolve = function ($subject) {
                    return preg_replace('/\.(\w+)/', "['$1']", $subject);
                };

                if ($value == $matches[0][0]) {
                    return $matches[0][1][0] === '$' ? $resolve($matches[0][1]) : "\$this->get('{$matches[0][1]}')";
                }

                foreach ($matches as $match) {
                    $replace[$match[0]] = $match[1][0] === '$' ? $resolve("{{$match[1]}}") : "{\$this->get('{$match[1]}')}";
                }

                return '"' . strtr($value, $replace) . '"';
            }
        }

        return var_export($value, true);
    }

    /**
     * Exports a parsable string representation of a value.
     *
     * @param  mixed    $value
     * @param  callable $export
     * @param  integer  $indent
     * @return string
     */
    protected static function exportValue($value, callable $export = null, $indent = 0)
    {
        if (is_array($value)) {

            $assoc = array_values($value) !== $value;
            $indention = str_repeat('  ', $indent);
            $indentlast = $assoc ? "\n" . $indention : '';

            foreach ($value as $key => $val) {
                $array[] = ($assoc ? "\n  " . $indention . var_export($key, true) . ' => ' : '') . self::exportValue($val, $export, $indent + 1);
            }

            return '[' . (isset($array) ? join(', ', $array) . $indentlast : '') . ']';
        }

        return $export ? $export($value) : var_export($value, true);
    }
}
