# Wykop API Client 

This library provides you a client for a Wykop API v2.

## Installation

Install the latest version with

```bash
$ composer require dapi/wykop-api-client
```

## Basic Usage
```php
<?php

use WykopApiClient\WykopApi;

// Create a client instance.
$client = new WykopApi('ABCdEF12Gh', '43FEd2cbA1');

// Retrieves the first page of hot entries from last 24h.
$hot24 = $client->request('/Entries/Hot/page/1/period/24');

// Display entry author, body and attachment's URL if available.
foreach($hot24->data as $entry) {
    $author = $entry->author->login;
    $body = (!empty($entry->body) ? $entry->body : '');
    $embed = (!empty($entry->embed) ? $entry->embed->preview : '');

    echo $author . ': '  . $body . ' ' . $embed . '<hr><br>';
}
```

## Documentation
TBC

## About

### Requirements

- Wykop API Client  works with PHP 5.5 or above.

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/chybaDapi/wykop-api-client/issues)

### Author

Dariusz Pisula - <dapiATdapi.net.pl> - <https://dapi.net.pl><br />

### License

Wykop API Client is licensed under the GNU GPL v3 License - see the `LICENSE` file for details
