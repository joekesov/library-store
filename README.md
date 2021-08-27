# library-store

## Description
There is a tree of start folder (in '/storage/import/files'), it's subfolders, their subfolders, etc...
In each folder, subfolder, etc… there could be same structured XML files stored. A sort of:
```xml
<books>
    <book>
        <author>Isak Azimov</author>
        <name>End of spirit</name>
    </book>
    <book>
        <author>3</author>
        <name>Standard</name>
    </book>
    ...
</books>
```

The program read XML parsed content into a data base tables:
* PHP script read XML files information and add it to MySQL two database tables: 
“author” and “book”. 
* If a record from specified file and subfolder already exists PHP script not insert it as a new one.


## Instalation

Prerequisites are installed Docker and Docker Compose (which, depending on your platform, 
might be a part of the Docker installation). If you’re using Windows, make sure you have 
the WSL2 feature enabled.

1. First you need to build the containers
```bash
docker-compose up -d --build
```

2. Then enter into the container
```bash
docker-compose exec server bash
```

3. While you are into the container execute installation script (if ask you type yes to continue)
```bash
php install.php 
```
The above script will create two tables into the database:
* author
* book

4. To import books with authors while you are into the container execute command script
```bash
php command.php 
```
