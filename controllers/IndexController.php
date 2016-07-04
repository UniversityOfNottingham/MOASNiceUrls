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
        $title = $this->_getParam('title');
        $slug = MOASNiceUrls_Helpers_Slugs::slugify($title);

        if (MOASNiceUrls_Helpers_Slugs::checkSlugExists($slug)) {
            $length = 45;
            while (MOASNiceUrls_Helpers_Slugs::checkSlugExists($slug) && $length < 80) {
                $slug = MOASNiceUrls_Helpers_Slugs::slugify($title, $length);
                $length += 5;
            }
        }

        $response['slug'] = $slug;
        
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