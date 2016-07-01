<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_Controller_Plugin_SlugGenerateFilter extends Zend_Controller_Plugin_Abstract
{
    /**
     * All routes that render an item element form, including those requested
     * via AJAX.
     *
     * @var array
     */
    protected $_defaultRoutes = array(
        array('module' => 'default', 'controller' => 'items',
            'actions' => array('add', 'edit'), 'type' => 'Item'),
        array('module' => 'default', 'controller' => 'collections',
            'actions' => array('add', 'edit'), 'type' => 'Collection'),
        array('module' => 'default', 'controller' => 'elements',
            'actions' => array('element-form'))
    );

    /**
     * Set the filters pre-dispatch only on configured routes.
     *
     * @param Zend_Controller_Request_Abstract
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $db = get_db();

        // Some routes don't have a default module, which resolves to NULL.
        $currentRoute = array(
            'module' => is_null($request->getModuleName()) ? 'default' : $request->getModuleName(),
            'controller' => $request->getControllerName(),
            'action' => $request->getActionName()
        );



        foreach ($this->_defaultRoutes as $route) {
            // Check registered routed against the current route.
            if ($route['module'] != $currentRoute['module']
                || $route['controller'] != $currentRoute['controller']
                || !in_array($currentRoute['action'], $route['actions']))
            {
                continue;
            }

            $element = $db->getTable('MOASNiceUrlsElement')->getSlugElement();
            $elementSet = $db->getTable('ElementSet')->find($element->element_set_id);
            add_filter(array('ElementInput', $route['type'], $elementSet->name, $element->name),
                array($this, 'filterElementInput'));

            break;
        }
    }

    public function filterElementInput($components, $args)
    {
        $components['html_checkbox'] = false;
        $components['input'] =
            get_view()->formText($args['input_name_stem'] . '[text]', $args['value'],
                array('style' => 'width: 250px;','class' => 'js-moas-slug-input')
            ).
            get_view()->formButton('', 'generate', array(
                'class' => 'js-moas-slug-generate'
            ));

        return $components;
    }
}