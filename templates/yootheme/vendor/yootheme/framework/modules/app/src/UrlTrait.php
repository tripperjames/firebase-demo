<?php

namespace YOOtheme;

trait UrlTrait
{
    /**
     * @see UrlGenerator::to
     */
    public function url($path, array $parameters = [], $secure = null)
    {
        return $this['url']->to($path, $parameters, $secure);
    }

    /**
     * @see UrlGenerator::route
     */
    public function route($pattern = '', array $parameters = [], $secure = null)
    {
        return $this['url']->route($pattern, $parameters, $secure);
    }
}
