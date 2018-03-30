ToDoList
========

Todo list for project 8 on Openclassroom

## Requirements

For works this project require PHP 7.2 and Composer.

## Installation

### With Git

You can use git and clone the repository on your folder.

```sh
cd /path/to/myfolder
git clone https://github.com/yanb94/todo.git
``` 

### With Folder

You can download the repository at zip format and unzip it on your folder

### Install dependencies 

Install dependencies with composer.

```sh
composer update
```

### Virtual Host

For optimal working it is recommended to use a virtual host who are pointing on the folder web.

```apache
<VirtualHost *:80>
	ServerName todolist
	DocumentRoot "c:/wamp64/www/todo/web"
	<Directory  "c:/wamp64/www/todo/web/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>
```
## Configuration

For that project works it is necessary to add a file **parameters.yml** on model of parameters.yml.dist

```yml
parameters:
    database_host:     <Database Host>
    database_port:     <Database Port>
    database_name:     <Database name>
    database_user:     <Database username>
    database_password: <Database password>

    mailer_transport:  <Mail protocol ex: stmp>
    mailer_host:       <Mail Host>
    mailer_user:       <Mail user>
    mailer_password:   <Mail Password>

    # A secret key that's used to generate certain security-related tokens
    secret:            <A secret Key>
```

## Initialize Project

### Create DataBase

For create the database of project execute this following command
```sh
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```
