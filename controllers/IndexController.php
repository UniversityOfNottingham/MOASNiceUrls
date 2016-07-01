<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_IndexController extends Omeka_Controller_AbstractActionController
{
    public function createAction()
    {
        $response = [];
        $slug = MOASNiceUrls_Helpers_Slugs::slugify($this->_getParam('title'));

        if (!MOASNiceUrls_Helpers_Slugs::checkSlugExists($slug))
        {
            $response['slug'] = $slug;
        }
        
        $this->_helper->json($response);
    }

    public function checkAction()
    {
        $response = [];

        $slug = $this->_getParam('slug');

        $response['exists'] = MOASNiceUrls_Helpers_Slugs::checkSlugExists($slug);
        $this->_helper->json($response);
    }

}