# OneSkyBundle
[![Build Status](https://travis-ci.org/OpenClassrooms/OneSkyBundle.svg?branch=master)](https://travis-ci.org/OpenClassrooms/OneSkyBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/87d6eebd-6344-4e30-86a6-71e501a2aa8b/mini.png)](https://insight.sensiolabs.com/projects/87d6eebd-6344-4e30-86a6-71e501a2aa8b)
[![Coverage Status](https://coveralls.io/repos/github/OpenClassrooms/OneSkyBundle/badge.svg?branch=master)](https://coveralls.io/github/OpenClassrooms/OneSkyBundle?branch=master)

The OneSkyBundle offers integration of [OneSky Client](https://github.com/onesky/api-library-php5) for common tasks like pulling and pushing translations.  
[OneSky](https://www.oneskyapp.com/) is a plateform that provides translations management.

## Installation
This bundle can be installed using composer:

```composer require openclassrooms/onesky-bundle```

or by adding the package to the composer.json file directly:

```json
{
    "require": {
        "openclassrooms/onesky-bundle": "*"
    }
}
```

After the package has been installed, add the bundle to the AppKernel.php file:
```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new OpenClassrooms\Bundle\OneSkyBundle\OpenClassroomsOneSkyBundle(),
    // ...
);
```

## Configuration
```yml
# app/config/config.yml

openclassrooms_onesky:
    api_key:  %onesky.api_key%
    api_secret: %onesky.api_secret%
    source_locale: %source_locale% #optional, default en
    locales:
      - fr
      - es
    file_format: %onesky.file_format% #optional, default xliff
    file_paths:
        %onesky.project_id%:
                - %path.to.translations.files.directory%
    keep_all_strings: false # default true
    
```

## Usage
### Pull
Pull the translations from the OneSky API using the default configuration.


```bash
php app/console openclassrooms:one-sky:pull
```

#### Options
##### filePath
filePath source can be set as an option.
```bash
php app/console openclassrooms:one-sky:pull --filePath=/path/to/source/files
php app/console openclassrooms:one-sky:pull --filePath=/path/to/source/files --filePath=/path/to/another/source/file
```
##### locale
Locale can be set as an option.
```bash
php app/console openclassrooms:one-sky:pull --locale=fr
php app/console openclassrooms:one-sky:pull --locale=fr --locale=es
```

### Push
Push the translations from the OneSky API using the default configuration.


```bash
php app/console openclassrooms:one-sky:push
```

#### Options
##### filePath
filePath source can be set as an option.
```bash
php app/console openclassrooms:one-sky:push --filePath=/path/to/source/files
php app/console openclassrooms:one-sky:push --filePath=/path/to/source/files --filePath=/path/to/another/source/file
```
##### locale
Locale can be set as an option.
```bash
php app/console openclassrooms:one-sky:push --locale=en
php app/console openclassrooms:one-sky:push --locale=en --locale=fr
```

### Update
Pull then push translations from the OneSky API using the default configuration.


```bash
php app/console openclassrooms:one-sky:update
```

### Check translation progress
Check the translation progress from the OneSky API using the default configuration.


```bash
php app/console openclassrooms:one-sky:check-translation-progress
```

#### Options
##### locale
Locale can be set as an option.
```bash
php app/console openclassrooms:one-sky:check-translation-progress --locale=en
php app/console openclassrooms:one-sky:check-translation-progress --locale=en --locale=fr
```
