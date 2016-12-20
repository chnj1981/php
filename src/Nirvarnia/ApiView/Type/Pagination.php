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

use InvalidArgumentException;
use JsonSerializable;

/**
 * Represents the pagination object within a collection. Example:
 *
 *     "pagination": {
 *         "page": 1,
 *         "pages": 2,
 *	       "per-page": 200
 *     }
 *
 */
class Pagination implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'page'     => 1,
        'pages'    => 1,
        'per-page' => 20
    ];

    /**
     * Constructor.
     *
     * Optionally, set the "page", "pages" and "per_page" settings.
     *
     * @param  integer  $page
     * @param  integer  $pages
     * @param  integer  $per_page
     */
    public function __construct($page = null, $pages = null, $per_page = null)
    {
        if ( ! is_null($page)) {
            $this->setCurrentPage($page);
            $this->setTotalPages($pages);
            $this->setNumberOfResultsPerPage($per_page);
        }
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
     * Set the "page" property
     *
     * @param   integer  $page
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setCurrentPage($page)
    {
        if ( ! is_integer($page) || $page < 1) {
            throw new InvalidArgumentException('Invalid "page" setting.');
        }

        $this->data['page'] = $page;
        return $this;
    }

    /**
     * Set the "pages" property
     *
     * @param   integer  $pages
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setTotalPages($pages)
    {
        if ( ! is_integer($pages) || $pages < 1) {
            throw new InvalidArgumentException('Invalid "pages" setting.');
        }

        $this->data['pages'] = $pages;
        return $this;
    }

    /**
     * Set the "per_page" property
     *
     * @param   integer  $per_page
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setNumberOfResultsPerPage($per_page)
    {
        if ( ! is_integer($per_page) || $per_page < 1) {
            throw new InvalidArgumentException('Invalid "per_page" setting.');
        }

        $this->data['per_page'] = $per_page;
        return $this;
    }

}
