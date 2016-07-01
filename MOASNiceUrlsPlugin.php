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
        'define_routes',
        'initialize',
        'uninstall_message'
    );

    public function hookAdminHead()
    {
        queue_js_file('moas-niceurls');
        queue_css_file('moas-niceurls');
    }

    public function hookDefineRoutes($args)
    {
        if (is_admin_theme()) {
            $args['router']->addConfig(new Zend_Config_Ini(
                __DIR__.'/routes.ini'
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
}