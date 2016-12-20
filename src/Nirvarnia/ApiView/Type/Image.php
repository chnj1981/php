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
 * Represents a single image object, which has the following structure:
 *
 *     {
 *			"id": null,
 *			"description": "",
 *			"mime_type": "image/jpeg",
 *			"sizes": [{
 *				"width": 240,
 *				"height": 200,
 *				"uri": "https://static.example.com/..."
 *			},{
 *				"width": 480,
 *				"height": 400,
 *				"uri": "https://static.example.com/..."
 *			},{
 *				"width": 960,
 *				"height": 800,
 *				"uri": "https://static.example.com/..."
 *			}]
 *      }
 *
 * The properties are as follows:
 *
 * - id: null or an integer if the image is cross-referenced from elsewhere.
 * - description: string, up to 120 characters in length, might be empty.
 * - mime_type: string, one of: "image/png", "image/jpeg", "image/svg+xml"
 * - sizes: array of one or more objects each with the following properties:
 *     - width: required, integer, image width in pixels
 *     - height: required, integer, image width in pixels
 *     - uri: required, string, the image's absolute URL
 *
 * All properties are included in an image object, even if their values are null
 * or otherwise empty.
 */
class Image implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'id'          => null,
        'description' => '',
        'mime_type'   => null,
        'sizes'       => []
    ];

    /**
     * Constructor.
     *
     * The image's MIME type must be declared on construction.
     *
     * @param   string  $mime_type
     */
    public function __construct($mime_type)
    {
        if ( ! is_null($mime_type)) {
            $this->setMimeType($mime_type);
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
     * Set the "id" property.
     *
     * @param   integer  $id
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setId($id)
    {
        if ( ! is_integer($id)) {
            throw new InvalidArgumentException(sprintf(
                'Expected integer, got %s.',
                (is_object($id) ? get_class($id) : gettype($id))
            ));
        }

        if ($id < 1) {
            throw new InvalidArgumentException('Expect integer of 1 or more.');
        }

        $this->data['id'] = $id;
        return $this;
    }

    /**
     * Set the "description" property.
     *
     * @param   string  $description
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setDescription($description)
    {
        if ( ! is_string($description)) {
            throw new InvalidArgumentException('Expect string.');
        }

        $this->data['description'] = $description;
        return $this;
    }

    /**
     * Set the "mime_type" property.
     *
     * @param   string  $mime_type
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function setMimeType($mime_type)
    {
        if ( ! in_array($mime_type, ['image/png', 'image/jpeg', 'image/svg+xml'])) {
            throw new InvalidArgumentException('Invalid image MIME type.');
        }

        $this->data['mime_type'] = $mime_type;
        return $this;
    }

    /**
     * Each image is supplied in one or more sizes. This method adds a
     * new size variation of the image.
     *
     * @param   integer  $width
     * @param   integer  $height
     * @param   string   $uri
     * @return  self
     * @throws  InvalidArgumentException
     */
    public function addSize($width, $height, $uri)
    {
        if ( ! is_integer($width) || $width < 1) {
            throw new InvalidArgumentException('Expect integer of 1 or more.');
        }

        if ( ! is_integer($height) || $height < 1) {
            throw new InvalidArgumentException('Expect integer of 1 or more.');
        }

        if ( ! is_string($uri) || empty($uri)) {
            throw new InvalidArgumentException('Expect non-empty string.');
        }

        $this->data['sizes'][] = [
            'width'  => $width,
            'height' => $height,
            'uri'    => $uri
        ];
        return $this;
    }

}
