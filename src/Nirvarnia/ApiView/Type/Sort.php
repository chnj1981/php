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
 * Represents the sort object within a collection. Example:
 *
 *     "sort": {
 *         "by": "field_name",
 *         "order": "asc"
 *     }
 *
 */
class Sort implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'by'    => null,
        'order' => 'asc'
    ];

    /**
     * Constructor.
     *
     * Optionally, set the "by" and "order" settings.
     *
     * @param  string  $by
     * @param  string  $order
     */
    public function __construct($by = null, $order = null)
    {
        if ( ! is_null($by)) {
            $this->setSortBy($by);
            $this->setSortOrder($order);
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
     * Set the "by" property
     *
     * @param   string  $by
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setSortBy($by)
    {
        if ( ! is_string($by) || empty($by)) {
            throw new InvalidArgumentException('Invalid "sort.by" setting.');
        }

        $this->data['by'] = $by;
        return $this;
    }

    /**
     * Set the "sort.order" property
     *
     * @param   string  $order
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setSortOrder($order)
    {
        if ( ! in_array($order, ['asc', 'desc'])) {
            throw new InvalidArgumentException('Invalid "sort.order" setting.');
        }

        $this->data['order'] = $order;
        return $this;
    }

}
