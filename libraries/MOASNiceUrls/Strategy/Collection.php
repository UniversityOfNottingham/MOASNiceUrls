<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_Strategy_Collection implements MOASNiceUrls_UrlInterface
{
    const URL = '/solr-search?facet=collection:';

    public function getUrl($record)
    {
        $collection = get_db()->getTable('Collection')->find($record->record_id);
        return static::URL . $this->buildQuery($collection);
    }

    private function buildQuery($collection)
    {
        $title = metadata($collection, array('Dublin Core', 'Title'));
        return '"' . str_replace(' ', '+', $title) . '"';
    }
}