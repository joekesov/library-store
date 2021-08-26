<?php

require_once ('./src/DBConnector.php');
require_once ('./src/AuthorRepository.php');
require_once ('./src/BookRepository.php');
require_once ('./src/Logger.php');

$importer = new Importer();
$importer->execute();

class Importer
{
    public function execute()
    {
        Logger::infoMessage('Start importing process...');

        $this->scanStorageDir();
    }

    protected function scanStorageDir(array $subDir = [])
    {
        $path = $this->getStoragePath($subDir);
        if (!is_dir($path)) {
            return;
        }

        $allFiles = scandir($path);
        $files = array_diff($allFiles, array('.', '..'));
        foreach ($files as $file) {
            $fileSubDir = $subDir;
            $fileSubDir[] = $file;
            $filePath = $this->getStoragePath($fileSubDir);
            if (is_dir($filePath)) {
                $this->scanStorageDir($fileSubDir);
                continue;
            }

            $fileInfo = pathinfo($filePath);
            if ('xml' != $fileInfo['extension']) {
                continue;
            }

            $xml = simplexml_load_file($filePath);
            if (!property_exists($xml, 'book')) {
                // TODO: throw Exception maybe
                continue;
            }

            foreach ($xml->book as $book) {
                $authorName = current($book->author);
                $bookName = current($book->name);
                $author = AuthorRepository::firstOrCreate(['name' => $authorName]);
                $book = BookRepository::firstOrCreate(['name' => $bookName, 'author_id' => $author->id]);
            }

            $this->moveUploadedFile($filePath);
//            rename('image1.jpg', 'del/image1.jpg');
        }
    }

    protected function getStoragePath(array $subDir = [])
    {
        $dir = './storage/import/files';
        if (count($subDir) > 0 ) {
            $dir .= '/'. implode('/', $subDir);
        }

        return $dir;
    }

    protected function getUploadedPath()
    {
        return './storage/import/uploaded/'. rand() .'_'. time() .'.xml';
    }

    protected function moveUploadedFile(string $filePath)
    {
        $newFilePath = $this->getUploadedPath();

        rename($filePath, $newFilePath);
        Logger::successMessage(sprintf('The file %s was uploaded successfully and could be found into %s', $filePath, $newFilePath));
    }
}