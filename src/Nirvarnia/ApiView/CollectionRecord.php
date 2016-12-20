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
use Nirvarnia\ApiView\ImageCollection;

/**
 * An OOP interface for compiling custom data records that are part of a
 * collection. This is slightly different to standard records, in that records
 * that are part of a collection have only "data" and not any "meta" data.
 */
class CollectionRecord implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [];

    /**
     * Constructor.
     *
     * Optionally, provide dataset as an associative array.
     *
     * @param   array  $data
     */
    public function __construct(array $data = [])
    {
        if ( ! empty($data)) {
            $this->set($data);
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
     * Sets a single parameter by name, or multiple parameters as an associative
     * array of key/value pairs.
     *
     * Return self normally, unless adding an ImageCollection, in which case
     * the ImageCollection instance is returned.
     *
     * @param   string|array  $key
     * @param   mixed         $value
     * @return  self|ImageCollection
     * @throws  InvalidArgumentException
     */
    public function set($key, $value)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            if ( ! is_string($key) || empty($key)) {
                throw new InvalidArgumentException('Invalid JSON key.');
            }

            // Check for a type supported natively by JSON.
            // Also allow nested ImageCollections
            if ( ! is_integer($value) && ! is_float($value) && ! is_string($value) && ! is_bool($value) && ! is_array($value) && ! is_null($value)) {
                if ( ! $value instanceof ImageCollection) {
                    throw new InvalidArgumentException('Invalid JSON value.');
                }
            }
            $this->data[$key] = $value;
        }

        $return = $this;
        if ($value instanceof ImageCollection) {
            $return = $value;
        }

        return $return;
    }

}
