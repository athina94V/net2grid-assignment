# net2grid-assignment
net2grid-assignment is a task in which, data from an API are consumed, send to an exchange on RabbitMQ, received from a queue and finally stored in a database
## Getting started

### Prerequisites
```
php
mysql
RabbitMQ
```

## Deployment
config.ini should be created and saved in the same path with the rest of scripts
```
[api_details]
hostname = hostname for api

[message_queue]
message_queue[hostname] = hostname
message_queue[port] = port
message_queue[username] = username
message_queue[password] = password

[database_details]
database_details[servername] = servername
database_details[username] = username
database_details[password] = password
database_details[dbname] = database name
```
createTable.php must be executed to create the table in the database
```
php createTable.php
```
Run index.php, stop the procedure with CTRL+C
```
php index.php
```
## Build with
* [netbeans](https://netbeans.org/) - IDE used
* [xampp](https://www.apachefriends.org/index.html) - PHP Development environment
* [rabbitMQ](https://www.rabbitmq.com/) - Open source message broker

## Authors

* **Athina Vavouri** 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
