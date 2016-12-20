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
use Nirvarnia\ApiView\Image;

/**
 * Represents a collection of image records.
 *
 * ImageCollections are typically nested within a deeper data structure.
 * Images can also be added to generic Collections, which are top-level
 * resources.
 */
class ImageCollection implements JsonSerializable
{

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [];

    /**
     * Required by the JsonSerializable interface.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize() {
        return $this->data;
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
        $this->data[] = $image;
        return $image;
    }

}
