<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Uri\Uri;
use YOOtheme\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * {@inheritdoc}
     */
    public function route($pattern = '', array $parameters = [], $secure = null)
    {
        if ($pattern !== '') {

            $search = [];

            foreach ($parameters as $key => $value) {
                $search[] = '#:' . preg_quote($key, '#') . '(?!\w)#';
            }

            $pattern = preg_replace($search, $parameters, $pattern);
            $pattern = preg_replace('#\(/?:.+\)|\(|\)|\\\\#', '', $pattern);

            $parameters = array_merge(['p' => $pattern], $parameters);
        }

        $base = Uri::base(true);
        $url = ($base ? "{$base}/" : '').'index.php';

        if ($query = http_build_query($parameters, '', '&')) {
            $url .= strpos($url, '?') ? '&' : '?';
            $url .= $query;
        }

        return $this->to($url, [], $secure);
    }
}
