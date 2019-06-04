<?php

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\PluginHelper;

$config = [

    'name' => 'yootheme/joomla-editor',

    'inject' => [
        'scripts' => 'app.scripts',
        'customizer' => 'theme.customizer',
    ],

    'events' => [

        'theme.admin' => function () {

            $config = Factory::getConfig();
            $editor = $config->get('editor');

            if (in_array($editor, ['tinymce', 'jce'])) {
                // all good, use enabled visual editor
            } elseif (!in_array($editor, ['none', 'codemirror'])) {
                // tinymce installed? use as visual
                $editor = PluginHelper::getPlugin('editors', 'tinymce') ? 'tinymce' : null;
            } else {
                $editor = null;
            }

            if ($editor) {

                HTMLHelper::_('behavior.modal');
                HTMLHelper::_('jquery.framework');

                $instance = Editor::getInstance($editor);
                $instance->display('yo_dummy_editor', '', '', '', '', '', ['pagebreak', 'readmore', 'widgetkit']);

                if ($editor === 'jce') {
                    $this->customizer->mergeData([
                        'editorButtonsXtd' => LayoutHelper::render(
                            'joomla.editors.buttons',
                            $instance->getButtons('yo_dummy_editor', ['pagebreak', 'readmore', 'widgetkit'])
                        ),
                    ]);
                }
            }

            $this->scripts->add('customizer-editor', "{$this->path}/app/editor.min.js", 'customizer');
        },

    ],

];

return defined('_JEXEC') ? $config : false;
