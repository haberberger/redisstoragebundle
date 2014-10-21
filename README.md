# Some higher level storage strategies for Redis as a Symfony2 Bundle


Step 1: Setting up the bundle
=============================
### A) Add dependency to RedisStorageBundle to your composer.json

```yaml
# composer.json
{
    "require": {
        "haberberger/redisstoragebundle": "dev-master"
    }
}
```

### B) Enable the bundle in the kernel

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Haberberger\RedisStorageBundle\HaberbergerRedisStorageBundle(),
    );
}
```

### C) Add configuration (optional)

If the default redis configuration (Redis server runs on localhost:6379) doesn't suit you, you can override it like that:

```yaml
# /app/config/config.yml
{
    haberberger_redis_storage:
        url: "%redis_url%"
}
```

```yaml
# /app/config/parameters.yml
{
    redis_url: 'redis://localhost:6379'
}
```

Or your personal redis connection params