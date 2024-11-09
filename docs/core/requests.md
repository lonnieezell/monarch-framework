# Requests

HTTP requests are represented by the `Request` class. This class provides a simple interface for working with the request data, and provides a few helper methods for common tasks and HTMX integration.

## Getting the Request Object

You can get the current request object by calling the `instance` method on the `Request` class.

```php
$request = \Monarch\HTTP\Request::instance();
```

Or you can you use the `request()` helper function. This is functionally the same as the above code, and returns the singleton instance of the request object.

```php
$request = request();
```

### Getting the Request Data

Most of the data you will need from the request will be available as read-only properties on the request object. For example, to get the request method, you can use the `method` property.

```php
$method = request()->method;
```

The following properties are available on the request object:

- `uri` - The full URI of the request.
- `method` - The HTTP method of the request.
- `scheme` - The scheme of the request (http or https).
- `host` - The host of the request.
- `port` - The port of the request.
- `path` - The path of the request.
- `query` - The query string of the request.
- `body` - The body of the request.
- `headers` - An array of headers from the request.
- `middleware` - An array of middleware that will be run on the request. You will typically not need to access this directly.

### Creating a Request Object

Creation of the request object is typically handled by the framework, but you can create a new request object from the global values if needed.

```php
use Monarch\HTTP\Request;

$request = Request::createFromGlobals();
```

During testing, you can create a request object with specific values.

```php
use Monarch\HTTP\Request;

$request = RequestFromArray([
    'method' => 'GET',
    'uri' => 'http://example.com/',
    'headers' => [
        'Content-Type' => 'text/html',
    ],
]);
```

### Request as an Array

If needed, you can export the request object as an array.

```php
$request = request()->toArray();

print_r($request);
// [
//     'uri' => 'http://example.com/',
//     'method' => 'GET',
//     'scheme' => 'http',
//     'host' => 'example.com',
//     'port' => 80,
//     'path' => '/',
//     'query' => '',
//     'body' => '',
//     'headers' => [
//         'Content-Type' => 'text/html',
//     ],
// ]
```

## Headers

Headers can be accessed from the request object as an array. The keys are the header names, and the values are the header values. Note that this is case-sensitive.

```php
// Check if header exists
if (request()->hasHeader('Content-Type')) {
    // Do something
}

// Get the value of a single header
$contentType = request()->header('Content-Type');

// Get all headers as an array
$headers = request()->headers();
```

## HTMX Integration

Monarch provides a simple way to work with HTMX requests. You can check all of the HTMX headers with the following built in methods:

```php
// Check if the request is an HTMX request
if (request()->isHtmx()) {
    // Do something
}

// Return the current HTMX Url
$url = request()->currentHtmxUrl();

// Check whether the request is for history restoration
// after a miss in the local history cache
if (request()->isHistoryRestore()) {
    // Do something
}

// Check if this is a request for a boosted link
if (request()->isBoosted()) {
    // Do something
}

// Check whether the request was intitiated by an HTMX prompt
if (request()->isPrompt()) {
    // Do something
}

// Returns the target of the HTMX request
$target = request()->target();

// Returns the ID of the element that triggered the HTMX request
$trigger = request()->trigger();

// Returns the name of the element that triggered the HTMX request
$name = request()->triggerName();
```
