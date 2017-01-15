# Laravel 4 'database' queue driver

Adds a 'database' queue driver to Laravel 4.

### Installation
Use `composer require` to add this package to your `composer.json` file and install it:

    composer require relaxsd/l4-database-queue:^1.0

Add the Service Provider to the providers array in config/app.php. 
Make sure to add it after the `Illuminate\Queue\QueueServiceProvider`.

````
    'providers' => array(

         /* Make sure to add after QueueServiceProvider */
         'Relaxsd\Queue\L4DatabaseServiceProvider'

    ),
````

### Driver Prerequisites

#### Database

In order to use the `database` queue driver, you will need a database table to hold the jobs. To generate a migration that creates this table, run the `queue:table` Artisan command. Once the migration has been created, you may migrate your database using the `migrate` command:

    php artisan queue:table

    php artisan migrate

#### Configuration

Finally, add the `database` queue driver to `config/queue.php`:

````
   /*
    |--------------------------------------------------------------------------
    | Default Queue Driver
    |--------------------------------------------------------------------------
    */

    'default'     => 'database',

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    */

    'connections' => array(

        ...
        
        'database'   => array(
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
        )

    )
````
