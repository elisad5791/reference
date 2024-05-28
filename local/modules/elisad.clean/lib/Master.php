<?php

namespace Elisad\Clean;

use Bitrix\Main\Type\DateTime;

class Master
{
    public $rootPath;
    public $file;
    public $bFiles = [];

    function __construct()
    {
        $this->rootPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/iblock';
        $this->getFiles();
        $this->clean();
    }

    function getFiles()
    {
        $select = ['FILE_NAME', 'SUBDIR'];
        $filter = ['=MODULE_ID' => 'iblock'];
        $result = FileTable::getList(['select' => $select, 'filter' => $filter])->fetchAll();
        $this->bFiles = array_column($result, 'SUBDIR', 'FILE_NAME');
        $count = count($this->bFiles);
        $this->log('В таблице найдено ' . $count . ' файлов');
    }

    function clean()
    {
        $rootDir = opendir($this->rootPath);
        while (false !== ($subDirName = readdir($rootDir))) {
            if ($subDirName == '.' || $subDirName == '..') {
                continue;
            }

            $subDirPath = "{$this->rootPath}/$subDirName";
            $subDir = opendir($subDirPath);
            while (false !== ($fileName = readdir($subDir))) {
                if ($fileName == '.' || $fileName == '..') {
                    continue;
                }

                $filePath = "{$this->rootPath}/$subDirName/$fileName";
                if (is_dir($filePath)) {
                    $this->deleteDirectory($filePath, $subDirPath);
                    $this->log('Обработана директория ' . $filePath);
                } else {
                    $this->deleteFile($subDirPath, $fileName);
                    $this->log('Обработан файл ' . $filePath);
                }
            }
            $this->log('Обработана директория ' . $subDirPath);
        }
        closedir($rootDir);
    }

    function deleteFile($subDirPath, $fileName)
    {
        $isExists = array_key_exists($fileName, $this->bFiles);
        if (!$isExists) {
            $fullPath = "$subDirPath/$fileName";
            unlink($fullPath);
            rmdir($subDirPath);
            $this->save('Файл удален', $fullPath);
        }
    }

    function deleteDirectory($dirPath, $outerPath)
    {
        $filesCount = 0;
        $subDir = opendir($dirPath);
        while (false !== ($fileName = readdir($subDir))) {
            if ($fileName == '.' || $fileName == '..') {
                continue;
            }

            if (array_key_exists($fileName, $this->bFiles)) {
                $filesCount++;
                continue;
            }

            $fullPath = "$dirPath/$fileName";
            unlink($fullPath);
            $this->save('Файл удален', $fullPath);

            if ($filesCount == 0) {
                rmdir($dirPath);
                rmdir($outerPath);
            }
        }
        closedir($subDir);
    }

    function save($text, $path)
    {
        $data = [
            'DATE' => new DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s'),
            'TIP' => $text,
            'PATH' => $path
        ];
        DeleteTable::add($data);
        $this->log('Удален файл ' . $path);
    }

    function log($text)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/elisad.clean/cleanLog.txt';
        $message = date('Y-m-d H:i:s') . "\n" . $text . "\n";
        file_put_contents($path, $message, FILE_APPEND);
    }
}