<?php

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\Filesystem\Path;
use YOOtheme\Util\File;
use YOOtheme\Util\Str;

$config = [

    'name' => 'yootheme/joomla-finder',

    'inject' => [
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
    ],

    'routes' => function ($routes) {

        $routes->get('/finder', function ($response) {

            $base = JPATH_ADMINISTRATOR . '/components/com_media';
            $params = ComponentHelper::getParams('com_media');

            JLoader::register('MediaHelper', "{$base}/helpers/media.php");

            define('COM_MEDIA_BASE', JPATH_ROOT . "/{$params->get('file_path')}");

            $files = [];

            foreach (BaseController::getInstance('Media', ['base_path' => $base])->getModel('list')->getList() as $type => $items) {
                foreach ($items as $item) {
                    $files[] = [
                        'name' => $item->get('name'),
                        'path' => $item->get('path_relative'),
                        'url' => strtr(ltrim(substr($item->get('path'), strlen(JPATH_ROOT)), '/'), '\\', '/'),
                        'type' => $type == 'folders' ? 'folder' : 'file',
                        'size' => $item->get('size') ? JHtml::_('number.bytes', $item->get('size')) : 0,
                    ];
                }
            }

            return $response->withJson($files);
        });

        $routes->post('/finder/rename', function ($oldFile, $newName, $response) {

            $user = Factory::getUser();
            $params = ComponentHelper::getParams('com_media');

            if (!$user->authorise('core.create', 'com_media') || !$user->authorise('core.delete', 'com_media')) {
                $this->app->abort(403, 'Insufficient User Rights.');
            }

            $allowed = $params->get('upload_extensions') . ',svg';
            $extension = (new File($newName))->getExtension();

            $isValidFilename = !empty($newName)
                && (empty($extension) || in_array($extension, explode(',', $allowed)))
                && (defined('PHP_WINDOWS_VERSION_MAJOR')
                    ? !preg_match('#[\\/:"*?<>|]#', $newName)
                    : strpos($newName, '/') === false);

            if (!$isValidFilename) {
                $this->app->abort(400, 'Invalid file name.');
            }

            $root = File::normalizePath(JPATH_ROOT.'/'.$params->get('file_path'));
            $oldFile = File::normalizePath(Path::clean("{$root}/{$oldFile}"));
            $path = dirname($oldFile);
            $newPath = File::normalizePath("{$path}/{$newName}");

            if (!Str::startsWith($path, $root) || $path !== dirname($newPath)) {
                $this->app->abort(400, 'Invalid path.');
            }

            if (!JFile::move($oldFile, $newPath)) {
                throw new Exception('Error writing file.');
            }

            return $response->withJson('Successfully renamed.');
        });

    },

    'events' => [

        'theme.admin' => function ($theme) {

            $user = Factory::getUser();

            // add assets
            $this->scripts->add('customizer-finder', "{$this->path}/app/finder.min.js", 'customizer');

            // add data
            $this->customizer->addData('media', [
                // TODO
                // 'path' => ComponentHelper::getParams('com_media')->get('file_path'),
                'canCreate' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.create', 'com_media'),
                'canDelete' => $user->authorise('core.manage', 'com_media') || $user->authorise('core.delete', 'com_media'),
            ]);
        },

    ],

];

return defined('_JEXEC') ? $config : false;
