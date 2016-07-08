<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrlsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'admin_head',
        'before_save_item',
        'define_routes',
        'initialize',
        'uninstall_message'
    );

    public function hookAdminHead()
    {
        queue_js_file('moas-niceurls');
        queue_css_file('moas-niceurls');
    }

    public function hookBeforeSaveItem($args)
    {
        $this->validateSlugs($args['post']['Elements'], $args['record'], 'Item');
    }

    public function hookBeforeSaveCollection($args)
    {
        $this->validateSlugs($args['post']['Elements'], $args['record'], 'Collection');
    }


    public function hookDefineRoutes($args)
    {
        if (is_admin_theme()) {
            $args['router']->addConfig(new Zend_Config_Ini(
                __DIR__ . '/routes.ini'
            ));
        } else {
            $router = $args['router'];
            foreach (MOASNiceUrls_Helpers_Slugs::fetchSlugs() as $slug) {
                $router->addRoute(
                    'moas_nice_urls_redirect_' . $slug['slug'],
                    new Zend_Controller_Router_Route(
                        $slug['slug'],
                        array(
                            'module' => 'moas-nice-urls',
                            'controller' => 'redirect',
                            'action' => 'redirect',
                            'id' => $slug['id']
                        )
                    )
                );
            }
        }
    }
    
    public function hookInitialize()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new MOASNiceUrls_Controller_Plugin_SlugGenerateFilter());
    }

    /**
     * Display the uninstall message.
     */
    public function hookUninstallMessage()
    {
        echo __('%sWarning%s: This will cause all nice urls to result in 404 errors.%s'
            , '<p><strong>', '</strong>', '</p>');
    }

    private function validateSlugs($elements, $record, $recordType)
    {
        $slugElement = MOASNiceUrls_Helpers_Slugs::getSlugElementID();
        $slugs = $elements[$slugElement];


        $currentSlugs = is_null($record->id) ? array() : MOASNiceUrls_Helpers_Slugs::getRecordsSlugs($record->id, $recordType);
        $filteredSlugs = array_filter($slugs, array(new MOASNiceUrls_Filters_ExistingSlugs($currentSlugs), 'filter'));
        $errors = [];
        foreach ($filteredSlugs as $slug) {
            $slugError = MOASNiceUrls_Helpers_Slugs::validate($slug['text']);
            if (!empty($slugError)) {
                $errors[$slug['text']] = $slugError[0];
            }
        }

        foreach ($errors as $slug => $error) {
            $record->addError("Slug - '" . $slug . "'", $error);
        }
    }
}