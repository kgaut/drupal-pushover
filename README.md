# drupal-pushover


TL;DR : Service allowing to send pushover notification from Drupal

Code Sample :

```
\Drupal::service('pushover.sender')->sendNotification('Test config', 'Hello world !');
```

```
\Drupal::service('pushover.sender')->sendNotification($title, $description, $url, $url_label);
```