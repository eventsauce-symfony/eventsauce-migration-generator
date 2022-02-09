## eventsauce-generate-migration

Command that generates doctrine migrations 
per aggregate


### Installation

```bash
composer require andreo/eventsauce-generate-migration
```

### Requirements

- PHP ^8.1
- Symfony console ^6.0


### Config doctrine migrations

In the first step, configure the [doctrine migrations](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.3/reference/configuration.html#configuration) package

### Usage

```php

new GenerateAggregateMigrationCommand(
    dependencyFactory: $dependencyFactory, // instance of Doctrine\Migrations\DependencyFactory;
);
```

### Table name suffix

Changing the default table suffixes is as follows

```php

use Doctrine\Migrations\DependencyFactory;
use Andreo\EventSauce\Doctrine\Migration\TableNameSuffix;

new GenerateAggregateMigrationCommand(
    dependencyFactory: $dependencyFactory,
    tableNameSuffix: new TableNameSuffix(event: 'event', snapshot: 'snapshot_state', outbox: 'outbox')
);
```

### Generate command

```bash
andreo:event-sauce:doctrine:migration:generate
```

#### Aggregate name argument

- required
- string

example command for aggregate with name **foo**

```bash
php bin/console andreo:event-sauce:doctrine:migration:generate foo
```

#### Schema option

- optional
- string[]
- available values: event, outbox, snapshot
- default value: [event]

example command for aggregate with **event** and **snapshot** schemas

```bash
php bin/console andreo:event-sauce:doctrine:migration:generate foo --schema=event --schema=snapshot
```

#### Execute migration

Default doctrine migration `command`

```bash
php bin/console d:m:m
```

### Schema builders

There are 3 dedicated interfaces.

#### Event messages

```php

interface EventMessageSchemaBuilder
{
    public function build(string $name, string $uuidType): Schema;
}

```

#### Outbox messages

```php

interface OutboxMessageSchemaBuilder
{
    public function build(string $name): Schema;
}

```

#### Snapshot

```php

interface SnapshotSchemaBuilder
{
    public function build(string $name, string $uuidType): Schema;
}

```

You can write custom builder, and use it when creating a command

```php

use Doctrine\Migrations\DependencyFactory;
use Andreo\EventSauce\Doctrine\Migration\TableNameSuffix;

new GenerateAggregateMigrationCommand(
    dependencyFactory: $dependencyFactory,
    eventMessageSchemaBuilder: new CustomEventMessageSchemaBuilder()
);
```