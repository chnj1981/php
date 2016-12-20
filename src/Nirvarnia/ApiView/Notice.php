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
 * Represents an individual object in the "notices" array:
 *
 *     {
 *         "notices": [
 *             {
 *                 "type": "Error",
 *                 "subtype": "Invalid Field",
 *                 "context": "username",
 *                 "message": "Already taken"
 *             }
 *         ]
 *     }
 *
 */
class Notice implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'type' => 'OK'
    ];

    /**
     * Constructor.
     *
     * @param   string  $type
     * @param   string  $subtype
     * @param   string  $context
     * @param   string  $message
     */
    public function __construct($type, $subtype = null, $context = null, $message = null)
    {
        $this->setType($type);

        if ( ! is_null($subtype)) {
            $this->setSubType($subtype);
        }

        if ( ! is_null($context)) {
            $this->setContext($context);
        }

        if ( ! is_null($message)) {
            $this->setMessage($message);
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
     * Set the notice type, one of: "OK", "Warning", "Error", "Info".
     *
     * @param   string  $type
     * @return  InvalidArgumentException
     */
    public function setType($type)
    {
        if ( ! in_array($type, ['OK', 'Warning', 'Error', 'Info'])) {
            throw new InvalidArgumentException('Invalid notice type.');
        }

        $this->data['type'] = $type;
    }

    /**
     * Set the notice subtype, eg. "Authentication Failed".
     *
     * @param   string  $subtype
     * @return  InvalidArgumentException
     */
    public function setSubType($subtype)
    {
        if ( ! is_string($subtype) || empty($subtype)) {
            throw new InvalidArgumentException('Invalid notice subtype.');
        }

        $this->data['subtype'] = $subtype;
    }

    /**
     * Set the field name where the notice was found, eg. "username".
     *
     * @param   string  $context
     * @return  InvalidArgumentException
     */
    public function setContext($context)
    {
        if ( ! is_string($context) || empty($context)) {
            throw new InvalidArgumentException('Invalid context field.');
        }

        $this->data['context'] = $context;
    }

    /**
     * Add a human-friendly message.
     *
     * @param   string  $message
     * @return  InvalidArgumentException
     */
    public function setMessage($message)
    {
        if ( ! is_string($message) || empty($message)) {
            throw new InvalidArgumentException('Invalid message.');
        }

        $strlen = mb_strlen($message);

        if ( ! $strlen || $strlen > 120) {
            throw new InvalidArgumentException(
                'Error messages must be 120 characters or less'
            );
        }
        $this->data['message'] = $message;
    }

}
