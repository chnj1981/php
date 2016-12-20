
# Usage

    use Nirvarnia\ApiView\Writer as ApiViewWriter;

    $view = new ApiViewWriter();
    json_encode($view);

The output of ``json_encode()`` will be:

    { "success": false }

This is the default API response. If the request is successful, you must explicitly call the ``isSuccessful()`` method to change the "success" property to ``true``:

    $view->isSuccessful();

Now the output of ``json_encode($view)`` will be:

    { "success": true }

### Notices

Optionally, an API response may contain one or more notices. As per the [apiview.org](https://www.apiview.org/) spec, there are three types of notice:

* "OK"
* "Error"
* "Info"

To add an "OK" notice, which is the default:

    use Nirvarnia\ApiView\Writer as ApiViewWriter;

    $view = new ApiViewWriter();
    $view->isSuccessful();
    $notice = $view->addNotice('You are logged in.');
    json_encode($view);

Output:

    {
        "success": true,
        "notices": [
            {
                "type": "OK",
                "message": "You are logged in."
            }
        ]
    }

Call the ``isError()`` or ``isInfo()`` methods to change a notice to th "Error" and "Info" types respectively.

    use Nirvarnia\ApiView\Writer as ApiViewWriter;

    $view = new ApiViewWriter();
    $notice = $view->addNotice('Invalid username.');
    $notice->isError();

Output:

    {
        "success": false,
        "notices": [
            {
                "type": "Error",
                "message": "Invalid username."
            }
        ]
    }

The "message" and "type" properties are mandatory. A notice message can be any human-friendly string.

Optionally, a notice may contain additional meta information. This can be anything you like. In the following example, a notice is extended with meta data that is meant to make it possible for client applications to identify the source and reason of the error.

    use Nirvarnia\ApiView\Writer as ApiViewWriter;

    $view = new ApiViewWriter();
    $notice = $view->addNotice('Invalid username.');
    $notice->isError();

    $notice->addMeta('code', 'INVALID_FIELD');
    $notice->addMeta('context', 'username');

Alternative syntax:

    $notice->addMeta([
        'code' => 'INVALID_FIELD',
        'context' => 'username'
    ]);

Now the output of ``json_encode($view)`` will be:

    {
        "success": false,
        "notices": [
            {
                "type": "Error",
                "message": "Invalid username.",
                "meta": {
                    "code": "INVALID_FIELD",
                    "context": "username"
                }
            }
        ]
    }

An API response may carry multiple notices.

    use Nirvarnia\ApiView\Writer as ApiViewWriter;

    $view = new ApiViewWriter();

    $notice = $view->addNotice('Invalid username.');
    $notice->isError();
    $notice->addMeta([
        'code' => 'INVALID_FIELD',
        'context' => 'username'
    ]);

    $notice = $view->addNotice('Invalid password.');
    $notice->isError();
    $notice->addMeta([
        'code' => 'INVALID_FIELD',
        'context' => 'password'
    ]);

    json_encode($view);

Output:

    {
        "success": false,
        "notices": [
            {
                "type": "Error",
                "message": "Invalid username.",
                "meta": {
                    "code": "INVALID_FIELD",
                    "context": "username"
                }
            },{
                "type": "Error",
                "message": "Invalid password.",
                "meta": {
                    "code": "INVALID_FIELD",
                    "context": "password"
                }
            }
        ]
    }


### Resources

Optionally, an API response may contain one or more resources. Each resource has a unique name. To add a resource called "users":

    use Nirvarnia\ApiView\Writer    as ApiViewWriter;
    use Nirvarnia\ApiView\Type\User as UserType;

    $view = new ApiViewWriter();
    $view->isSuccessful();

    $resource = $view->addResource('users', UserType::class);

The output of ``json_encode($view)`` will be:

    {
        "success": true,
        "resources": {
            "users": {
                "data": []
            }
        }
    }


A resource is a collection of one or more records. The recordset is attached to the resource's "data" property.

For consistency, each record in a resource is meant to have the same properties and data types. To enforce this, the Nirvarnia\ApiView library requires that the resource's "type" be configured before records can be added to it. The resource type is the second parameter passed to ``addResource()``. It is a fully-qualified class name, one instance of which will represent a single record in the collection.

The Nirvarnia\ApiView library comes with a suite of built-in resource types. (You can also create your own - see the section on custom resource types, below.) The built-in types have the ``Nirvarnia\ApiView\Type`` namespace. To create a resource called "users" with two ``Nirvarnia\ApiView\Type\User`` objects:

    use Nirvarnia\ApiView\Writer    as ApiViewWriter;
    use Nirvarnia\ApiView\Type\User as UserType;

    $view = new ApiViewWriter();
    $view->isSuccessful();

    $resource = $view->addResource('users', UserType::class);

    $record = $resource->appendRecord('James', 'Bond');
    $record->setUsername('jbond');

    $record = $resource->appendRecord('Jason', 'Bourne');
    $record->setUsername('jasonbourne');

    $record = $resource->appendRecord('Jack', 'Bauer');

The ``appendRecord()`` method returns a new object instance of the type defined in the second parameter given to ``addResource()``. Typically, mandatory data is passed to each new record on its construction via the ``appendRecord()`` method. Optional data is typically passed in via subsequent setting methods.

The result of JSON-encoding the ``$view`` object now will be:

    {
        "success": true,
        "resources": {
            "users": {
                "data": [
                    {
                        "first_name": "James",
                        "last_name": "Bond",
                        "username": "jbond"
                    },
                    {
                        "first_name": "Jason",
                        "last_name": "Bourne",
                        "username": "jasonbourne"
                    },
                    {
                        "first_name": "Jack",
                        "last_name": "Bauer",
                        "username": ""
                    }
                ]
            }
        }
    }

A resource is always a collection of one or more records. Even if a resource contains only a single record, the resource structure will always be an array of objects. This is consistent with the [apiview.org](https://www.apiview.org/) spec. For this reason, it is conventional that resource names be plural.

Here's another example. The API response contains a resource called "photos" which returns objects of the built-in ``Nirvarnia\ApiView\Type\Image`` type:

    use Nirvarnia\ApiView\Writer as ApiViewWriter;
    use Nirvarnia\ApiView\Type\Image as ImageType;

    $view = new ApiViewWriter();
    $view->isSuccessful();

    $resource = $view->addResource('photos', ImageType::class);

    $image = $resource->appendRecord('jpeg');
    $image->addSize(120, 80, 'https://cdn.example.com/photos/small/001.jpeg');
    $image->addSize(240, 160, 'https://cdn.example.com/photos/medium/001.jpeg');
    $image->addSize(360, 240, 'https://cdn.example.com/photos/large/001.jpeg');

    $image = $resource->appendRecord('jpeg');
    $image->addSize(120, 80, 'https://cdn.example.com/photos/small/002.jpeg');
    $image->addSize(240, 160, 'https://cdn.example.com/photos/medium/002.jpeg');
    $image->addSize(360, 240, 'https://cdn.example.com/photos/large/002.jpeg');

The output of ``json_encode($view)`` is:

    {
        "success": true,
        "resources": {
            "photos": {
                "data": [
                    {
                        "mimetype": "image/jpeg",
                        "description": "",
                        "sizes": [
                            {
                                "width": 120,
                                "height": 80,
                                "uri": "https://cdn.example.com/photos/small/001.jpeg"
                            },{
                                "width": 240,
                                "height": 160,
                                "uri": "https://cdn.example.com/photos/medium/001.jpeg"
                            },{
                                "width": 360,
                                "height": 240,
                                "uri": "https://cdn.example.com/photos/large/001.jpeg"
                            }
                        ]
                    },{
                        "mimetype": "image/jpeg",
                        "description": "",
                        "sizes": [
                            {
                                "width": 120,
                                "height": 80,
                                "uri": "https://cdn.example.com/photos/small/002.jpeg"
                            },{
                                "width": 240,
                                "height": 160,
                                "uri": "https://cdn.example.com/photos/medium/002.jpeg"
                            },{
                                "width": 360,
                                "height": 240,
                                "uri": "https://cdn.example.com/photos/large/002.jpeg"
                            }
                        ]
                    }
                ]
            }
        }
    }


### Custom resource types

The Nirvarnia\ApiView built-in resource types serve only as an example of how to compose API endpoints around a persistent set of global types. Every API is unique, so you will most likely need to design your own resource types.

Each resource type is represented by a class. To create a new resource type, create a new class that extends from ``Nirvarnia\ApiView\Type\AbstractType``. Example:

    namespace Application\API\View\Type;
    use Nirvarnia\ApiView\Type\AbstractType;

    final class SearchResult extends AbstractType
    {
        private $data = [
            'id'          => 0,
            'title'       => '',
            'description' => ''
        ];
    }

The ``$data`` class property is mandatory. It must be an associative array. It sets the properties and default values for each object.

To allow data to be inputted, add some setting methods. It is recommended that you require all mandatory data to be inputted via the ``__construct()`` method, ensuring that all generated objects are immediately valid.

    namespace Application\API\View\Type;
    use Nirvarnia\ApiView\Type\AbstractType;

    final class SearchResult extends AbstractType
    {
        private $data = [
            'id'          => 0,
            'title'       => '',
            'description' => ''
        ];

        public function __construct(int $id, string $title)
        {
            $this->id    = $id;
            $this->title = $title;
        }

        public function setDescription(string $description)
        {
            $this->description = $description;
        }
    }

Usage:

    use Nirvarnia\ApiView\Writer               as ApiViewWriter;
    use Application\API\View\Type\SearchResult as SearchResultType;

    $view = new ApiViewWriter();
    $view->isSuccessful();

    $resource = $view->addResource('search_results', SearchResultType::class);

    $record = $resource->appendRecord(71929, 'Jack Johnson');
    $record->setDescription('The kingpin of campfire acoustic pop-rock.');

    $record = $resource->appendRecord(348876, 'Jack Savoretti');
    $record->setDescription('Poet-turned-acoustic singer/songwriter.');

    $record = $resource->appendRecord(8677752, 'Jackie Greene');
    $record->setDescription('Singer-songwriter and multi-instrumentalist.);

    json_encode($view);

Output:

    {
        "success": true,
        "resources": {
            "search_results": {
                "data": [
                    {
                        "id": 71929,
                        "title": "Jack Johnson",
                        "description": "The kingpin of campfire acoustic pop-rock."
                    },{
                        "id": 348876,
                        "title": "Jack Savoretti",
                        "description": "Poet-turned-acoustic singer/songwriter."
                    },{
                        "id": 8677752,
                        "title": "Jackie Greene",
                        "description": "Singer-songwriter and multi-instrumentalist."
                    }
                ]
            }
        }
    }


### Metadata

Optional metadata may be added to any resource via the ``addMetadata()`` method. Dot-noted key strings may be used to represent depth.

    $resource->addMetadata('total_results', 3);
    $resource->addMetadata('pagination.page', 1);
    $resource->addMetadata('pagination.per_page', 10);
    $resource->addMetadata('pagination.pages', 1);

Alternative syntax:

    $resource->addMetadata([
        'total_results' => 3,
        'pagination' => [
            'page' => 1,
            'per_page' => 10,
            'pages' => 1
        ]
    ]);

Output:

    {
        "success": true,
        "resources": {
            "search_results": {
                "meta": {
                    "total_results": 3,
                    "pagination": {
                        "page": 1,
                        "per_page": 10,
                        "pages": 1
                    }
                },
                "data": [
                    {
                        "id": 71929,
                        "title": "Jack Johnson",
                        "description": "The kingpin of campfire acoustic pop-rock."
                    },{
                        "id": 348876,
                        "title": "Jack Savoretti",
                        "description": "Poet-turned-acoustic singer/songwriter."
                    },{
                        "id": 8677752,
                        "title": "Jackie Greene",
                        "description": "Singer-songwriter and multi-instrumentalist."
                    }
                ]
            }
        }
    }

