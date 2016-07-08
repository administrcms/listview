# ListView package for AdministrCMS

Although it is written to work with the aministr package, you can use this one as a standalone with Laravel 5.2.

It is still a work-in-progress.

# Installation

Using [Composer](https://getcomposer.org/):

```
composer require administrcms/listview
```

Add the service provider:

```php
\Administr\ListView\ListViewServiceProvider::class,
```

# What it does

It creates a table representation of your data.

# Usage

```php
// The datasource can be an array
$data = [
    ['id' => 1, 'name' => 'test 1'],
    ['id' => 2, 'name' => 'test 2'],
];

// Collection of Eloquent models
$data = User::all();

// Paginated result from a model,
// in this case it will display the pagination
$data = User::paginate(20);

$listView = new ListView(
    $data
);

// You can pass options with the magical setter,
// which will be translated in table attributes
$listView->class = 'table table-bordered table-hover';

// Defining a field
// text is a type which is dynamically set
// using the magic __call method.
// It will look for a field definition
// and if it fails will use a simple text representation.
// Available fields are text, boolean, date, datetime, time.
// Date and time formats can be modified from the config file.
$listView
    ->text('id', '#')
    ->text('name', 'Name')
    ->text('created_at', 'Created');

// You can set formatters on each column.
// This allows you to manipulate the output value of the column.
// It is possible to pass multiple formatters to a columns.
// In this example - add path to the value, if you are not
// keeping the whole path in db. Put an image tag instead of plain text.
// Possible values are a Closure, path to formatter class
// that implements the *Administr\ListView\Contracts\Formatter* contract,
// string that is mapped to a formatter class in the config file *administr.listview*
// and an array of all above as well as multiple parameters to the method format
$listView
    ->text('logo_img', 'Logo', function(Column $column, array $row){
        $column->format(function(array $row){
                return "path/to/file/{$row['logo_img']}";
            }, 'image')
            ->format(SomeCustomFormatter::class);
    });

// Action columns

// Global action
$listView
    ->action('add', 'Add')
    ->setGlobal()
    ->icon('fa fa-plus')
    ->url( route('resource.create') );

// Context action
// For context actions which require value for the row,
// you can use the define method, which accepts a Closure
// that has two parameters - the Action instance and an array
// with the data of the current row - in the example - id, name.
$listView
    ->action('edit', '')
    ->icon('fa fa-edit')
    ->define(function(Action $action, array $item) {
        $action->url(route('resource.edit', [$item['id']]));
    });
    
// Render the table with data
$listView->render();

// Getting the global actions
$list->getActions('global');
```