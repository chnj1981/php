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

/**
 * An interface for the ApiView library.
 */
interface ApiViewInterface
{

    /**
     * Must returns a string representation of the view.
     *
     * By default, the string representation must be JSON.
     */
    public function __toString();

    /**
     * Sets the "success" property to true.
     *
     * @return  self
     */
    public function isSuccessful();

    /**
     * Adds a new dataset to the top-level "data" property.
     *
     * @param   string                                                   $name
     * @param   Nirvarnia\API\View\Json\Response\Record|Collection  $record_or_collection
     * @return  Nirvarnia\API\View\Json\Response\Record|Collection
     * @throws  InvalidArgumentException
     */
    public function addResource($name, $record_or_collection);

    /**
     * Adds a new general message to the JSON response.
     *
     * @param   string       $type
     * @param   string|null  $code
     * @param   string|null  $context
     * @param   string|null  $message
     * @return  self
     */
    public function addNotice($type, $code = null, $context = null, $message = null);

    /**
     * Required by the JsonSerializable interface.
     *
     * @see  http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize();

    /**
     * Helper function to return a new Collection instance.
     *
     * @return  Nirvarnia\API\View\Json\Response\Collection
     */
    public function newCollection();

    /**
     * Helper function to return a new ImageCollection instance.
     *
     * @return  Nirvarnia\API\View\Json\Response\ImageCollection
     */
    public function newImageCollection();

    /**
     * Helper function to return a new Record instance.
     *
     * @return  Nirvarnia\API\View\Json\Response\Record
     */
    public function newRecord();

    /**
     * Return the view content as a string.
     *
     * The default output format is JSON.
     *
     * @param   string  $format
     * @return  string
     */
    public function render($format = 'json');

}
