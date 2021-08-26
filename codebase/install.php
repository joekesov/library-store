<?php

require_once ('./src/Logger.php');

//print_r('Are we here'); exit;

echo "Are you sure you want to do this?  Type 'yes' to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(trim($line) != 'yes'){
    Logger::infoMessage('ABORTING!');
    exit;
}

Logger::infoMessage('Thank you, continuing...');

$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASS');
//$dbname = getenv('MYSQL_DB');

try{
    $conn = new PDO(getenv('DATABASE_URL'),$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    Logger::successMessage('Connected succesfully');

} catch(PDOException $e){
    Logger::errorMessage("Connection failed: " . $e -> getMessage());

}

$createTables = [
    'author' => '
        CREATE TABLE IF NOT EXISTS author (
            id int NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        );
        ',
    'book' => '
        CREATE TABLE  IF NOT EXISTS  book (
            id int NOT NULL AUTO_INCREMENT,
            author_id int,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT FK_BookAuthor FOREIGN KEY (author_id)
            REFERENCES author(id)
        );
        '
];

foreach ($createTables as $tableName => $statement) {
    $conn->exec($statement);
    Logger::infoMessage(sprintf('Table %s created successfully', $tableName));
}
