# fusionFramework user module

Contains the following features:

 - User registration
 - User sessions (login/out)
 - Password recovery
 - User settings
 - Profiles

## Tasks

**admin:tabs**
Creates permissions for admin user tabs.

```php minion admin:tabs```

**user:owner**
Promotes a user to owner rank.

This task takes either id or username as a parameter

```php minion user:owner --id=1```
```php minion user:owner --username=admin```

## Cronjobs
