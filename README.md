# Ydays symfony  🚀
  
## S1  📚
**New app commands**

Create a traditional symfony web applications

`symfony new project_name --full`

Create a symfony web services or micro api...

`symfony new project_name`

**Server commands**  
  
Start a server  

`symfony serve`  
  
Start a server other way  

`symfony server:start`  
  
Start a server in daemon (background)  

`symfony serve -d`  
  
Stop a server (for daemon)  

`symfony server:stop`  
  
Server logs (for daemon)  

`symfony server:logs`

## S2 📚
**Controller commands**

Create a controller (adding controller and templating)

`php bin/console make:controller ControllerName`

**Utils commands**
Clear the cache of the application

`php bin/console cache:clear`

## S3 📚
**Doctrine and Entities**

Create the database using the DATABASE_URL param in .env file

`php bin/console doctrine:database:create`

Create an Entity (Entity + repository)

`php bin/console make:entity EntityName`

Output SQL to be executed to update the database

`php bin/console doctrine:schema:update --sql`

Update the database

`php bin/console doctrine:schema:update --force`

## S4 📚

**Add symfony/form to our project**

`composer require symfony/form`

**Create a new form type**

`php bin/console make:form FormType`

## S5 📚

**Add the security bundle to our project**

`composer require symfony/security-bundle`

**Use the maker bundle to create the User entity**

`php bin/console make:user`

**Update the database**

`php bin/console doctrine:schema:update --force`

**Use the maker bundle to create the auth**

`php bin/console make:auth`

**Little command to manually encode a password**

`php bin/console security:encode-password`

## S6 📚

**Install mailer component**

`composer require symfony/mailer`

**Install transport**

`composer require symfony/mailgun-mailer`

**Install twig mailing**

`composer require symfony/twig-bundle`

`composer require twig/cssinliner-extra`
