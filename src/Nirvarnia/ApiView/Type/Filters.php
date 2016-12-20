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
 * Represents the filters object within a collection. Example:
 *
 *     "filters": {
 *         "selected": {
 *             "field-name": [id, id, id],
 *             "field-name": [id, id, id],
 *         },
 *         "disabled": {
 *             "field-name": [id, id, id],
 *             "field-name": [id, id, id],
 *         }
 *     }
 */
class Filters implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'selected' => [],
        'disabled' => []
    ];

    /**
     * Required by the JsonSerializable interface.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize() {
        return $this->data;
    }

    /**
     * Set a field and, optionally, corresponding ids that are "disabled" in a
     * resource collection.
     *
     * @param   string      $field_name
     * @param   array|true  $ids
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setDisabledFilter($field_name, $ids = true)
    {
        if ( ! is_string($field_name) || empty($field_name)) {
            throw new InvalidArgumentException('Invalid field name.');
        }

        if ( ! is_array($ids) || $ids !== true)) {
            throw new InvalidArgumentException('Invalid field name.');
        }

        $this->data['disabled'][$field_name] = $ids;
        return $this;
    }

    /**
     * Set a field and, optionally, corresponding ids that are "selected" in a
     * resource collection.
     *
     * @param   string      $field_name
     * @param   array|true  $ids
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setSelectedFilter($field_name, $ids = true)
    {
        if ( ! is_string($field_name) || empty($field_name)) {
            throw new InvalidArgumentException('Invalid field name.');
        }

        if ( ! is_array($ids) || $ids !== true)) {
            throw new InvalidArgumentException('Invalid field name.');
        }

        $this->data['selected'][$field_name] = $ids;
        return $this;
    }

}
