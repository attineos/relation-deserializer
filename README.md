Relation Deserializer Sample Project
=====================

A sample api project to allow the Symfony serializer to denormalize doctrine entities from their identifier, using a 
custom denormalizer.

The denormalizer supports all types of relations:
- OneToOne
- ManyToOne / OneToMany
- ManyToMany

Installation
------------

To install the application dependencies, run the command below:

```
composer install
```

To initialize the database, set the `DATABASE_URL` variable in the `.env` file with your database server URL, then run the following commands:

```
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate
```

The project comes with a data fixture for each entity, to execute them, run the following command:

```
php bin/console doctrine:fixtures:load
```

Example
-------

Sending a POST request on `/books` with the following body:

```json
{
  "title": "Test",
  "isbn": "9780648883258",
  "description": "Quidem nihil qui qui aperiam incidunt. Omnis sed dicta officiis sit non. Officiis et ex possimus.",
  "authors": [2, 3]
}
```

The denormalizer then replaces the authors identifiers with the corresponding entities by requesting doctrine, in response we 
get the persisted book serialized in json:

```json
{
  "id": 101,
  "title": "Test",
  "isbn": "9780648883258",
  "description": "Quidem nihil qui qui aperiam incidunt. Omnis sed dicta officiis sit non. Officiis et ex possimus.",
  "reviews": [],
  "authors": [
    {
      "id": 2,
      "name": "Roland Fournier"
    },
    {
      "id": 3,
      "name": "Corinne Lacroix"
    }
  ]
}
```

Credits
-------

The custom denormalizer was mainly taken from [this medium article](https://medium.com/cloudstek/using-the-symfony-serializer-with-doctrine-relations-69ecb17e6ebd).
