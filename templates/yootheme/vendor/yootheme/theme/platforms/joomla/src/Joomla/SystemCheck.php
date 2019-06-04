<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\ContainerTrait;

class SystemCheck extends \YOOtheme\Theme\SystemCheck
{
    use ContainerTrait;

    private $registry = [];

    protected $inject = [
        'db' => 'app.db',
        'apikey' => 'app.apikey',
    ];

    public function getRequirements()
    {
        $res = [];

        // Page cache @TODO is needed?
        if (PluginHelper::isEnabled('system', 'cache')) {
            $res[] = 'j_page_cache';
        }

        // Check for SEBLOD Plugin and setting
        $components = ComponentHelper::getComponents();
        $cck = isset($components['com_cck']) ? $components['com_cck'] : false;
        if ($cck && $cck->enabled == '1') {
            if ($cck->params->get('hide_edit_icon')) {
                $res[] = 'j_seblod_icon';
            }
        }

        try {

            // Check for RSFirewall settings @TODO check if enabled?
            $rsfw = $this->db->fetchAssoc("SELECT value FROM @rsfirewall_configuration WHERE name = 'verify_emails'");

            if ($rsfw['value'] == 1) {
                $res[] = 'j_rsfw_mail';
            }

        } catch (\Exception $e) {}

        return array_merge($res, parent::getRequirements());
    }

    public function getRecommendations()
    {
        $res = [];

        if (!$this->apikey) {
            $res[] = 'j_apikey';
        }

        return array_merge($res, parent::getRecommendations());
    }
}
