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
use Nirvarnia\ApiView\ApiViewInterface;
use Nirvarnia\ApiView\Collection;
use Nirvarnia\ApiView\ImageCollection;
use Nirvarnia\ApiView\Notice;
use Nirvarnia\ApiView\Record;

/**
 * An interface for compiling RESTful API responses that are consistent with
 * the apiview.org specification.
 */
class ApiView implements ApiViewInterface, JsonSerializable
{

    // /**
    //  * Mappings for common notice types and subtypes. Usage example:
    //  *
    //  *     $view = new \Nirvarnia\API\View\Json\Response();
    //  *     $view->addNotice($view::ERROR);
    //  */
    // const OK             = 'OK';
    // const WARNING        = 'Warning';
    // const ERROR          = 'Error';
    // const INFO           = 'Info';

    // const DATABASE_ERROR = 'Database Error';
    // const INVALID_FIELD  = 'Invalid Field';
    // const NOT_FOUND      = 'Endpoint Not Found';

    /**
     * @var  array  $data
     * The data to be serialized to JSON or XML.
     */
    protected $data = [
        'success' => false
    ];

    /**
     * Returns a string representation of the view.
     *
     * By default, the string representation is the JSON view.
     */
    public function __toString()
    {
        return $this->render('json');
    }

    /**
     * Set the "success" property to true.
     *
     * @return  self
     */
    public function isSuccessful()
    {
        $this->data['success'] = true;
        return $this;
    }

    /**
     * Add a new dataset to the top-level "data" property.
     *
     * The second parameter may be one of the following:
     *
     * - A Nirvarnia\API\View\Json\Response\Record instance
     * - A Nirvarnia\API\View\Json\Response\Collection instance
     *
     * Returns a reference to the object passed as the second parameter.
     *
     * @param   string                                                   $name
     * @param   Nirvarnia\API\View\Json\Response\Record|Collection  $record_or_collection
     * @return  Nirvarnia\API\View\Json\Response\Record|Collection
     * @throws  InvalidArgumentException
     */
    public function addResource($name, $record_or_collection)
    {
        if ( ! is_array($record_or_collection)) {
            if (( ! $record_or_collection instanceof Collection) && ( ! $record_or_collection instanceof Record)) {
                throw new InvalidArgumentException('Expect Collection or Record.');
            }
        }

        if ( ! array_key_exists('resources', $this->data)) {
            $this->data['resources'] = [];
        }

        if (array_key_exists($name, $this->data['resources'])) {
            throw new InvalidArgumentException('Resource already defined: ' . $name);
        }

        $this->data['resources'][$name] = $record_or_collection;
        return $record_or_collection;
    }

    /**
     * Add a new general message to the JSON response.
     *
     * @param   string       $type
     * @param   string|null  $code
     * @param   string|null  $context
     * @param   string|null  $message
     * @return  self
     */
    public function addNotice($type, $code = null, $context = null, $message = null)
    {
        if ( ! array_key_exists('notices', $this->data)) {
            $this->data['notices'] = [];
        }

        $notice = new Notice($type, $code, $context, $message);
        $this->data['notices'][] = $notice;

        return $notice;
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
     * Helper function to return a new Collection instance.
     *
     * @return  Nirvarnia\API\View\Json\Response\Collection
     */
    public function newCollection()
    {
        $collection = new Collection();
        return $collection;
    }

    /**
     * Helper function to return a new ImageCollection instance.
     *
     * @return  Nirvarnia\API\View\Json\Response\ImageCollection
     */
    public function newImageCollection()
    {
        $image_collection = new ImageCollection();
        return $image_collection;
    }

    /**
     * Helper function to return a new Record instance.
     *
     * @return  Nirvarnia\xxxx
     */
    public function newRecord()
    {
        $record = new Record();
        return $record;
    }

    /**
     * Return the view content as a string.
     *
     * The default output format is JSON.
     *
     * @param   string  $format
     * @return  string
     */
    public function render($format = 'json')
    {
        if ($format === 'json') {
            return (json_encode($this->data));
        }
        throw new InvalidArgumentException(sprintf(
            'Format not supported: %s.', $format
        ));
    }

}
