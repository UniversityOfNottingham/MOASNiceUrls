<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_Strategy_Item implements MOASNiceUrls_UrlInterface
{
    public function getUrl($record)
    {
        $item = get_db()->getTable('Item')->find($record->record_id);
        $url = $item->getRecordUrl();
        return implode('/', $url);
    }
}