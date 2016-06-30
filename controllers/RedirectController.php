<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_RedirectController extends Omeka_Controller_AbstractActionController
{

    public function redirectAction()
    {
        $elementID = $this->_getParam('id');
        $element = $this->_helper->db->getTable('ElementText')->find($elementID);

        $url = new MOASNiceUrls_UrlContext($element->record_type);
        $this->redirect($url->getUrl($element));
    }
}