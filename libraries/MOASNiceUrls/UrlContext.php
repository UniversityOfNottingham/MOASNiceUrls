<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_UrlContext
{
    private $strategy = null;

    /**
     * UrlContext constructor.
     */
    public function __construct($record_type)
    {
        switch ($record_type) {
            case 'Item':
                $this->strategy = new MOASNiceUrls_Strategy_Item();
                break;
            case 'Collection':
                $this->strategy = new MOASNiceUrls_Strategy_Collection();
                break;
        }
    }

    public function getUrl($record)
    {
        return $this->strategy->getUrl($record);
    }


}