<?php

    /**
     * The story in spoken words:
     * When the client retrieved a success-message on creating the file "dirWatchList.txt", 
     * he triggers this file "create-file-list-of-dir.php" which is doing following:
     * It take the path from the parameter and makes a hash of the string. 
     * This hash is used for the name of a txt-file that it creates, (e.g. "27170b44a3873c3692d31104e83e37c8.txt")
     * where-in the details of the files inside that directory given are saved.
     * With that hash the file gets clearly associated to the given path. 
     * The created file is needed to remember the details of the files from the given directory-path.
     */


    function createFileListOfDir() {

        $pathToDirectory = $_POST['param1']; // "C:/xampp/htdocs/ar/github/snippets/"



        // in case the user has given a path to a file, instead a path to directory,
        // we only keep the last directory in path.
        $myDirectoryPathWithFilename = $pathToDirectory; // first we remember the complete path in another variable
        $myPathChunksScan = explode("/", $myDirectoryPathWithFilename); // then we split up the path directories to an array
        $amountOfItemsInArrayScan= count($myPathChunksScan); // find out how many they are

        if (stripos($myPathChunksScan[$amountOfItemsInArrayScan-1], ".") >-1){ // seems to be a file-path, instead a dir-path

            $myDirectoryPathWithFilename = str_replace($myPathChunksScan[$amountOfItemsInArrayScan-1], "", $myDirectoryPathWithFilename); // replace the last one with "nothing"

            // back to the root 
            $pathToDirectory = $myDirectoryPathWithFilename; // now we are sure we have only a dir-path
        } 




        // we want avoid creating two files if user once adds the path with an ending slash and once without, 
        // so we allways add at the end a slash if not there
        if (substr($pathToDirectory, -1) == "/"){
            // echo "found the slash";
        } else {
            // echo "not found the slash";
            $pathToDirectory = $pathToDirectory."/";
        }



        // The response, initially an empty array 
        // will be returned/converted into a JSON-object.
        $response = array();

        // for creating a file with an unique name, 
        // that can be associated with the dir-path, 
        // we take the hash of the dir-path and name the file like that.
        $md5HashOfDirPath = md5($pathToDirectory);

        // the path to the file
        $fileListOfDir = "./".$md5HashOfDirPath.".txt";



        if (file_exists($fileListOfDir)) { // exists

            $response["createFileList"] =  'NOTE: file exists already.';

        } else {

            $size = 0;
            $oneFilesDetails = array();
            $contentOfFile = "";
            $amountOfFilesInDir = 0;

            // loop through all directories and subdirectories to collect all the files in it
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathToDirectory)) as $file) {

                if ($file->isFile()) {

                    $amountOfFilesInDir = $amountOfFilesInDir + 1; // increment

                    if ($amountOfFilesInDir > 7000){

                        $response["createFileList"] =  'ERROR: Too many files in dir to be watched. > 7000. Please choose a dir with less than 7000 files.';

                        $response = json_encode($response);
                        echo $response;

                        return;

                    } else {

                        $myfilesHash = md5_file($file);
                        $myfilesPath = $file->getRealPath();
                        $myfilesSize = $file->getSize();

                        // we want to have in the pathes slashes (like in the pathes in diretory-list) and no backsalshes 
                        $myfilesPath = str_replace("\\", "/", $myfilesPath);

                        $myNewLine = "|||".$myfilesHash."::".$myfilesPath."\n";

                        $contentOfFile = $contentOfFile.$myNewLine;
                    }
                }
            }


            // Create the file with some details of the file
            file_put_contents($fileListOfDir, $contentOfFile);
            $response["createFileList"] =  'SUCCESS: file-list of added dir created.';

        }



        $response = json_encode($response);
        echo $response;
    }

    createFileListOfDir();

?>