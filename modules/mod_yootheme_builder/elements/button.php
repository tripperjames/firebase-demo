<?php

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class JFormFieldButton extends JFormField
{
    protected $type = 'Button';

    public function renderField($options = array())
    {
        if (!$params = $this->loadDefaultTemplateParams() or !$params->get('yootheme')) {
            return '<p id="alert-customizer" class="alert alert-error">Please make YOOtheme the <a href="index.php?option=com_templates&filter_search=yootheme">default template style</a>.</p>';
        }

        return parent::renderField($options);
    }

    public function getInput()
    {
        return '<script>

            jQuery(function ($) {

                var label = $("#jform_params_button-lbl");
                var group = label.closest(".control-group").hide();
                var button = $("<a class=\"tm-button\">Open Builder</a>");
                var target = "index.php?option=com_ajax&p=customizer&section=joomla-modules&return=" + encodeURIComponent(location.href);

                if (!$(".uk-modal-page", parent.document).length) {
                    button.attr("href", target).insertAfter(group);
                }

            });

        </script>
        <style>
            .tm-button {
                display: block;
                box-sizing: border-box;
                width: 280px;
                max-width: 100%;
                padding: 20px 30px;
                border-radius: 2px;
                background: linear-gradient(140deg, #FE67D4, #4956E3);
                box-shadow: inset 0 0 1px 0 rgba(0,0,0,0.5);
                line-height: 10px;
                vertical-align: middle;
                color: #fff !important;
                font-size: 11px;
                font-weight: bold;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                text-align: center;
                text-decoration: none !important;
                text-transform: uppercase;
                letter-spacing: 2px;
                -webkit-font-smoothing: antialiased;
            }\
        </style>';
    }

    protected function loadDefaultTemplateParams()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('params')
            ->from('#__template_styles')
            ->where('client_id = 0')
            ->where('home = 1');

        $db->setQuery($query);

        if ($templ = $db->loadObject()) {
            return new Registry($templ->params);
        }
    }
}
