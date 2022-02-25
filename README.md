# Pushover

This is a Drupal 8 and 9 module that allows you to send push notification through [Pushover](https://pushover.net) service.

# Code Sample

## Signature
```
\Drupal::service('pushover.sender')->sendNotification($title, $message, $url = NULL, $url_title = NULL);
```
## Simple example

```
\Drupal::service('pushover.sender')->sendNotification('Test config', 'Hello world !');
```
## Full example

```
\Drupal::service('pushover.sender')->sendNotification($title, $description, $url, $url_label);
```

# Module download

This module is not (yet ?) on drupal.org, but you can download it with composer :
```
composer config repositories.drupal-pushover vcs https://github.com/kgaut/drupal-pushover
composer require drupal/pushover
```
