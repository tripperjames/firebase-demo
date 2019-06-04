<?php

namespace YOOtheme\Theme\Joomla;

use YOOtheme\EventSubscriber;

class UrlListener extends EventSubscriber
{
    const REGEX_URL = '/
                        \s                                 # match a space
                        (?<attr>(?:data-)?(?:src|poster))= # match the attribute
                        (["\'])                            # start with a single or double quote
                        (?!\/|\#|[a-z0-9-.]+:)             # make sure it is a relative path
                        (?<url>[^"\'>]+)                   # match the actual src value
                        \2                                 # match the previous quote
                       /xiU';

    public $inject = [
        'view' => 'app.view',
    ];

    public function onSite()
    {
        $this->view->addLoader($this);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($name, $parameters, $next)
    {
        if (!is_string($content = $next($name, $parameters))) {
            return $content;
        }

        return preg_replace_callback(self::REGEX_URL, function ($matches) {
            return sprintf(' %s="%s"', $matches['attr'], $this->app->url(html_entity_decode($matches['url'])));
        }, $content);

    }

    public static function getSubscribedEvents()
    {
        return [
            'theme.site' => 'onSite',
        ];
    }
}
