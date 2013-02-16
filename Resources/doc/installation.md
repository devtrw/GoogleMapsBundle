Installation
============

Follow the steps below to install and setup this bundle

### 1. Download bundle and requirements using composer ###

Update composer.json 

```json
"require" : {
   ...
   "devtrw/guzzle-bundle": "1.0.3",
   "devtrw/google-maps-bundle": "*"
},
"repositories": [
    ...
    {
        "type": "vcs",
        "url": "https://github.com/devtrw/GuzzleBundle"
    },
    {
        "type": "vcs",
        "url": "https://github.com/devtrw/GoogleMapsBundle"
    }
]
```

Download the resources
```
$ php composer.phar update
```

### 2. Enable the bundle ###

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Devtrw\Bundles\GoogleMapsBundle\DevtrwGoogleMapsBundle(),
        new Ddeboer\GuzzleBundle\DdeboerGuzzleBundle()
    ];
}
```

### 3. Configure the Guzzle Bundle ###

If you are not already using the Guzzle bundle then you can just
point the config over to the GoogleMapsBundle. If you do this
make sure that the guzzle bundle is loaded after the GoogleMapsBundle
in the kernel configuration.

```yaml
ddeboer_guzzle:
    service_builder:
        configuration_file: "%devtrw_google_maps.guzzle.config%"
```

If you are allready using the Guzzle Bundle then import the service
definitions into your existing guzzle config.
