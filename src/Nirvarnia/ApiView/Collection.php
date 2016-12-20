<?php declare(strict_types=1);

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2010-2016 Kieran Potts
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package  nirvarnia/apiview
 * @see      https://github.com/nirvarnia/apiview
 * @see      https://www.nirvarnia.org/
 */

namespace Nirvarnia\ApiView;

use JsonSerializable;
use Nirvarnia\ApiView\Filters;
use Nirvarnia\ApiView\MetaInformation;
use Nirvarnia\ApiView\Pagination;
use Nirvarnia\ApiView\CollectionRecord;
use Nirvarnia\ApiView\Sort;

/**
 * Represents a collection of one or more records, each with identical
 * properties.
 */
class Collection implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'data' => []
    ];

    /**
     * Add a single piece of meta information, or lots of meta information as
     * key/value pairs.
     *
     * @param   string|array  $key
     * @param   mixed         $value
     * @return  self
     */
    public function addMetadata($key, $value = null)
    {
        if ( ! array_key_exists('meta', $this->data)) {
            $this->data['meta'] = new Metadata();
        }

        $meta = $this->data['meta'];
        $meta->set($key, $value);

        return $this;
    }

    /**
     * Creates a new Record instance and attaches it to the collection's dataset.
     *
     * Returns the new Record instance.
     *
     * @return  Nirvarnia\API\View\Json\Response\Record
     */
    public function addRecord()
    {
        $record = new CollectionRecord();
        $this->data['data'][] = $record;
        return $record;
    }

    /**
     * Add a new image to the collection.
     *
     * The image's MIME type must be specified.
     *
     * @param   string  $mime_type
     * @return  Nirvarnia\API\View\Json\Response\Image
     */
    public function addImage($mime_type)
    {
        $image = new Image($mime_type);
        $this->data['data'][] = $image;
        return $image;
    }

    /**
     * Add options to the "filters.disabled" property.
     *
     * @param   string  $field_name
     * @param   array   $disabled_ids
     * @return  self
     */
    public function addDisabledFilters($field_name, $disabled_ids)
    {
        if ( ! array_key_exists('filters', $this->data)) {
            $this->data['filters'] = new Filters();
        }

        $filters = $this->data['filters'];
        $filters->addDisabledFilters($field_name, $disabled_ids);

        return $this;
    }

    /**
     * Add options to the "filters.selected" property.
     *
     * @param   string  $field_name
     * @param   array   $disabled_ids
     * @return  self
     */
    public function addSelectedFilters($field_name, array $selected_ids)
    {
        if ( ! array_key_exists('filters', $this->data)) {
            $this->data['filters'] = new Filters();
        }

        $filters = $this->data['filters'];
        $filters->addSelectedFilters($field_name, $selected_ids);

        return $this;
    }

    /**
     * Required by the JsonSerializable interface.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize() {
        return $this->data;
    }

    /**
     * Set the "pagination" property.
     *
     * @param   integer  $page
     * @param   integer  $pages
     * @param   integer  $per_page
     * @return  self
     */
    public function setPagination($page, $pages, $per_page)
    {
        if ( ! array_key_exists('pagination', $this->data)) {
            $this->data['pagination'] = new Pagination();
        }

        $pagination = $this->data['pagination'];
        $pagination->setCurrentPage($page);
        $pagination->setTotalPages($pages);
        $pagination->setNumberOfResultsPerPage($per_page);

        return $this;
    }

    /**
     * Set the "pagination" property.
     *
     * @param   integer  $page
     * @param   integer  $pages
     * @param   integer  $per_page
     * @return  self
     */
    public function setSorting($by, $order)
    {
        if ( ! array_key_exists('sort', $this->data)) {
            $this->data['sort'] = new Sort();
        }

        $sort = $this->data['sort'];
        $sort->setSortBy($by);
        $sort->setSortOrder($order);

        return $this;
    }

}
