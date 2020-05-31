# Тестовое задание
### Используемые инструменты
- Ubuntu 20.04 LTS
- PhpStorm
- Composer
- Supervisor
- Gearman
- PHP 7.4
```
PHP 7.4.3 (cli) (built: May 26 2020 12:24:22) ( NTS )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
    with Zend OPcache v7.4.3, Copyright (c), by Zend Technologies
```

### Настройки для Supervisor'a
```
# /etc/supervisor/conf.d/supervisor.conf 
[program:gearman-worker]
command=php /var/www/tasks/workers.php
autostart=true
autorestart=true
numprocs=4
process_name=tasks-workers-%(process_num)s
```

## Запуск
1. Запустить Supervisor с указанным конфигом.
2. Запустить строку (находимся корне проекта)
```
php task_manager.php
```