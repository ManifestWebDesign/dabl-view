# Dabl View
Simple class for rendering pure PHP views

## Example

app.php:
```php
use Dabl\View\View;

View::addDirectory(__DIR__ . '/views');
$output = View::load(
    'view', // view name
    array(
        'my_param' => 'hello world' // param to be injecting into the view
    ),
    true // return output instead of sending output to browser
);
echo $output;
```

views/view.php:
```php
<div><?= $my_param ?></div>
```

output:
```html
<div>hello world</div>
```