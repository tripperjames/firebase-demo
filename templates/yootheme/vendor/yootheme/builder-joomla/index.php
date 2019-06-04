<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Component\ComponentHelper;
use YOOtheme\Builder\Joomla\ContentListener;
use YOOtheme\Http\Uri;

$config = [

    'name' => 'yootheme/builder-joomla',

    'main' => function ($app) {
        $app->subscribe(new ContentListener());
    },

    'inject' => [
        'view' => 'app.view',
        'builder' => 'app.builder',
        'sections' => 'app.view.sections',
    ],

    'routes' => function ($routes) {

        $routes->post('/builder/image', function ($src, $md5 = null, $response) {

            $app = Factory::getApplication();
            $http = HttpFactory::getHttp();
            $params = ComponentHelper::getParams('com_media');

            try {

                $uri = Uri::fromString($src);
                $file = basename($uri->getPath());

                if ($uri->getHost() === 'images.unsplash.com') {
                    $file .= '.'.$uri->getQueryParam('fm', 'jpg');
                }

                $file = File::makeSafe($file);
                $path = Path::check(rtrim(implode('/', [JPATH_ROOT, $params->get('image_path'), $this->theme->get('media_folder')]), '/\\'));

                // file already exists?
                while ($iterate = @md5_file("{$path}/{$file}")) {

                    if ($iterate === $md5 || is_null($md5)) {
                        return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));
                    }

                    $file = preg_replace_callback('/-?(\d*)(\.[^.]+)?$/', function ($match) {
                        return sprintf('-%02d%s', intval($match[1]) + 1, isset($match[2]) ? $match[2] : '');
                    }, $file, 1);
                }

                // create file
                File::write("{$path}/{$file}", '');

                // download file
                $tmp = "{$path}/".uniqid();
                $res = $http->get($src);

                if ($res->code != 200) {
                    throw new Exception('Download failed.');
                } elseif (!File::write($tmp, $res->body)) {
                    throw new Exception('Error writing file.');
                }

                // allow .svg files
                $params->set('upload_extensions', $params->get('upload_extensions').',svg');

                // ignore MIME-type check for .svg files
                $params->set('ignore_extensions', $params->get('ignore_extensions') ? $params->get('ignore_extensions').',svg' : 'svg');

                if (!(new MediaHelper())->canUpload(['name' => $file, 'tmp_name' => $tmp, 'size' => filesize($tmp)])) {

                    File::delete($tmp);

                    $queue = $app->getMessageQueue();
                    $message = count($queue) ? $queue[0]['message'] : '';

                    throw new Exception($message);
                }

                // move file
                if (!File::move($tmp, "{$path}/{$file}")) {
                    throw new Exception('Error writing file.');
                }

                return $response->withJson(strtr(substr("{$path}/{$file}", strlen(JPATH_ROOT) + 1), '\\', '/'));

            } catch (\Exception $e) {

                // delete incomplete file
                File::delete("{$path}/{$file}");

                $this->app->abort(500, $e->getMessage());
            }

        });

    },

    'events' => [

        'builder.init' => function ($builder) {
            $builder->addTypePath("{$this->path}/elements/*/element.json");

            // load child theme elements
            if ($childTheme = $this->theme->get('child_theme')) {
                $builder->addTypePath("{$this->theme->path}_{$childTheme}/builder/*/element.json");
            }
        },

        'theme.site' => function () {

            HTMLHelper::register('builder', function ($node, $params = []) {

                // support old builder arguments
                if (!is_string($node)) {
                    $node = json_encode($node);
                }

                if (is_string($params)) {
                    $params = ['prefix' => $params];
                }

                return $this->builder->render($node, $params);
            });

            $this->view->addLoader(function ($name, $parameters, $next) {
                return \JHtmlContent::prepare($next($name, $parameters));
            }, '*/builder/elements/layout/templates/template.php');

        },

        'dispatch' => function () {

            $document = Factory::getDocument();

            if (!$this->sections->exists('builder') && null !== $data = $this->theme->get('builder')) {
                $this->sections->set('builder', function () use ($data) {
                    $result = $this->builder->render($data['content'], ['prefix' => 'page']).$data['edit'];
                    $this->app->trigger('content', [$result]);
                    return $result;
                });
            }

            if ($this->sections->exists('builder')) {
                $this->theme->set('builder', true);
                $document->setBuffer('', 'component');
            }

        },

    ],

];

return defined('_JEXEC') ? $config : false;
