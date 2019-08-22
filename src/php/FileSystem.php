<?php

class FileSystem {

    public function upload($file, $dir, $allowedExts = array()) {
        $fileName       = $file['name'];
        $fileSize       = $file['size'];
        $fileTmpName    = $file['tmp_name'];
        $fileType       = $file['type'];
        $fileError      = $file['error'];
        $fileExtenstion = explode('.', $fileName);
        $fileExtenstion = strtolower(end($fileExtenstion));

        if(!empty($allowedExts) && !in_array($fileExtenstion, $allowedExts))
            exit("File extension .$fileExtenstion is not allowed!");

        $uploadPath = STORAGE . $dir . basename($fileName);
        if(is_file($uploadPath)) exit("File already exists!");

        if($fileError) exit("There was an error in your file!");
        if($fileSize > MAX_FILESIZE) exit("File size exceeded file size limit!");
   
        if(move_uploaded_file($fileTmpName, $uploadPath))
            return true;
        else
            exit("There was an error moving your file!");
    }

    public function get(string $file): string {
        $path = ROOT . STORAGE . "/$file";

        return $path;
    }

    public function move(string $file, string $path): bool {
        if(!rename(STORAGE . $file, STORAGE . $path))
            return false;

        return true;
    }

    public function delete(string $file): bool {
        if(!unlink(STORAGE . $file))
            return false;
        
        return true;
    }

    public function listFiles(string $dir_name, bool $listDirs = false): array {
        if(!is_dir($dir_name))
            return [];
        
        $dir = scandir($dir_name);
        
        $real_path = explode("\\", realpath($dir_name));

        if(!$listDirs || end($real_path) == STORAGE)
            $dir = array_diff($dir, array(".", ".."));
        else {
            $dir = array_diff($dir, array("."));
        }

        $dir = array_values($dir);
        $dir_final = array();

        foreach($dir as $file) {
            if(is_dir($dir_name . $file) && !$listDirs)
                continue;

            $file_ext = "";

            if(strpos($file, ".")) {
                $file_ext = explode(".", $file);
                $file_ext = end($file_ext);
                $isDir = false;
            }

            else {
                $isDir = true;
            }

            $dir_final[] = array(
                "name"  => $file,
                "ext"   => $file_ext,
                "isDir" => $isDir
            );
        }

        return $dir_final;
    }

    public function listDirs(string $dir_name) {
        if(!is_dir($dir_name))
            return [];

        $dir = scandir($dir_name);

        $dir = array_diff($dir, array(".", ".."));

        $dir = array_values($dir);
        $dir_final = array();

        foreach($dir as $file) {
            if(!is_dir($dir_name . $file))
                continue;

            $dir_final[] = array(
                "name"  => $file
            );
        }

        return $dir_final;
    }

    public function rename(string $file, string $name): bool {
        if(!rename(STORAGE . $file, STORAGE . $name))
            return false;
        
        return true;
    }

    public function filesSize(string $dir): float {
        $files = $this->listFiles($dir);
        $total_size = 0;

        foreach($files as $file) {
            $total_size += filesize($dir . "/" . $file['name']);
        }

        return round((float)$total_size / 1000000, 2);
    }

    public function newDir(string $dir_name): bool {
        if(!mkdir(STORAGE . htmlspecialchars($dir_name), 0777, true))
            return false;

        return true;
    }

    function deleteDir($path) {
        $i = new DirectoryIterator($path);
        
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                self::deleteDir($f->getRealPath());
            }
        }

        rmdir($path);
   }

   public function backup() {
        // Stolen, lol (https://stackoverflow.com/questions/29873248/how-to-zip-a-whole-directory-and-download-using-php/29873298)
        $dir = STORAGE;
        $zip_file = 'data/storage-backup.zip';

        // Get real path for our folder
        $rootPath = realpath($dir);

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($zip_file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($zip_file));
        readfile($zip_file);
   }

}