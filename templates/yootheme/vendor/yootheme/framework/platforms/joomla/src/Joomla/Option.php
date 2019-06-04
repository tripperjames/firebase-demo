<?php

namespace YOOtheme\Joomla;

use Joomla\CMS\Factory;
use YOOtheme\Util\Collection;

class Option extends Collection
{
    /**
     * Constructor.
     *
     * @param Database $db
     * @param string   $element
     * @param string   $folder
     */
    public function __construct($db, $element, $folder = '')
    {
        $row = $db->fetchAssoc("SELECT custom_data FROM @extensions WHERE element = :element AND folder = :folder LIMIT 1", compact('element', 'folder'));

        if ($data = json_decode($row['custom_data'], true)) {
            parent::__construct($data);
        }

        $app = Factory::getApplication();
        $app->registerEvent('onAfterRespond', function () use ($db, $row, $element, $folder) {
            if ($data = $this->json() and $data != $row['custom_data']) {
                $db->update('@extensions', ['custom_data' => $data], compact('element', 'folder'));
            }
        });
    }
}
