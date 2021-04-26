# monogatari-collection

Install dependencies with `composer install`
Set environment value for database connection url
   
`$env:CLEARDB_DATABASE_URL = 'mysqli://user:pass@host/db_name'` in powershell or
    
`export CLEARDB_DATABASE_URL = 'mysqli://user:pass@host/db_name'` in bash

Run local server with `php -S localhost:8080 -t web/`
