
<?php

    /**
     * The story in spoken words:
     * This file "watchdir.php" is getting triggered, 
     * when a user clicks on a link (path from the watchlist), and is doing following: 
     * It loops recursively through the direcory/path given, 
     * and for every file that it finds on its way, it looks if it can be found too in the list of files, 
     * that was saved in the txt-file. (e.g. "27170b44a3873c3692d31104e83e37c8.txt") 
     * Then it compares the details to find out what has changed. 
     */



    function selectDirToWatch() {

        $myDirPathToWatch = $_POST['param1']; // "C:/xampp/htdocs/ar/github/snippets/"

        // The response, initially an empty array 
        // will be returned/converted into a JSON-object.
        $response = array();

        $size = 0;
        $allFilesFromDir = array();

        // get the txt-file with the saved list of the details of the files, from the dir
        $watchListArray = array();  // the complete list from files in an array incl. their hash

        // collect the files in array while scanning directory
        // needed to find out if a file was deleted from dir to watch
        $scanDirFileListArray = array();  // the complete list of file-pathes in an array1

        //collect only the file-pathes in an array while looping throught the array with the hash+filepathes (|||hash::filepathes)
        // needed to find out if a file was deleted from dir to watch
        $watchListArrayOnlyFilePath = array();  // the complete list of file-pathes in an array2



        // we need to be sure that the path ends with a slash, 
        // to get a unique and right hash of that path 
        if (substr($myDirPathToWatch, -1) == "/"){
            // slash found
        } else {
            // slash not found
            $myDirPathToWatch = $myDirPathToWatch."/";
        }

        // we take the hash of the dir-path to find the right file with the list.
        $md5HashOfDirPath = md5($myDirPathToWatch);

        $myFileWithTheFileList = "./".$md5HashOfDirPath.".txt";  // the name is prepared




        if (file_exists($myFileWithTheFileList)) { // exists

            $myFilesContent = str_replace("\n", "", file_get_contents($myFileWithTheFileList)); // file-content without line-breaks
            $watchListArray = explode("|||", $myFilesContent); // file-content in array

            if ($watchListArray[0] == ""){
                array_shift($watchListArray); // remove first item, because it is empty 
            }

        } else { // something went wrong, watch-list does not exist

            $response["checkFilesOfSelectedDir"] = 'ERROR: missing txt-file with file-list. (Please remove from watchlist and add again.)';
        }


        $amountOfFilesInWatchListArray = count($watchListArray);


        // loop through all directories and subdirectories to collect all the files in it
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($myDirPathToWatch)) as $file) {

            if ($file->isFile()) {

                // we want to have the slashes in the pathes (like in the pathes in diretory-list) and no backsalshes 
                $myFilesDetail["filePath"]                  = str_replace("\\", "/", $file->getRealPath());

                $myFilesDetail["fileSize"]                  = $file->getSize();
                $myFilesDetail["fileContentChangeTime"]     = date("Y-m-d H:i:s", filemtime($file->getRealPath()));
                $myFilesDetail["fileHash"]                  = md5_file($file); 

                $myFilesDetail["fileContentHasChanged"]     = "unset";
                $myFilesDetail["fileNew"]                   = "unset";
                $myFilesDetail["fileDeleted"]               = "unset"; 


                // // we want to have the dir-path only without the files name
                // $myfilesDirectoryPathFromScan = $myFilesDetail["filePath"]; // first we remember the complete path in a variable
                // $myPathChunksScan = explode("/", $myfilesDirectoryPathFromScan); // then we split up the path directories to an array
                // $amountOfItemsInArrayScan= count($myPathChunksScan); // find out how many they are
                // $myfilesDirectoryPathFromScan = str_replace($myPathChunksScan[$amountOfItemsInArrayScan-1], "", $myfilesDirectoryPathFromScan); // replace the last one with "nothing"


                if (file_exists($myFileWithTheFileList)) { // saved list exists
                    // find out if the file is a new file or if already in our saved list
                    if (stripos($myFilesContent, $myFilesDetail["filePath"]) >-1){
                        $myFilesDetail["fileNew"] = "false";
                    } else {
                        $myFilesDetail["fileNew"] = "true";
                    }
                }


                // collect all files from scanned dir, only the pathes in an array1
                array_push($scanDirFileListArray, $myFilesDetail["filePath"]); 


                // loop through the array with the files (lines) from txt 
                for ($oneFileInList = 0; $oneFileInList < $amountOfFilesInWatchListArray; $oneFileInList++){

                    $fileDetailsSaved = explode("::", $watchListArray[$oneFileInList]); // split up the hash from the filepath
                    $fileHashSaved = $fileDetailsSaved[0];
                    $filePathSaved = $fileDetailsSaved[1];

                    // collect all files from txt, only the pathes in an array2
                    array_push($watchListArrayOnlyFilePath, $filePathSaved); 

                    if( $fileHashSaved == $myFilesDetail["fileHash"] || $filePathSaved == $myFilesDetail["filePath"]){ // we found the wanted file-details

                        if ($fileHashSaved != $myFilesDetail["fileHash"] && $filePathSaved == $myFilesDetail["filePath"]) {
                            $myFilesDetail["fileContentHasChanged"] = "true";
                        } else  {
                            $myFilesDetail["fileContentHasChanged"] = "false";
                        }
                    }
                }

                array_push($allFilesFromDir, $myFilesDetail); 
            }
        } // END each from scanning directories


        // Comparing the collected two arrays (the list from txt with the list from scanned dir)
        // and keep only those that are not in the array of the scanned dir,
        // means are the list of files that were deleted.
        $myArrayWithDeletedFiles = array_diff($watchListArrayOnlyFilePath, $scanDirFileListArray);
        $myArrayWithDeletedFiles = array_unique($myArrayWithDeletedFiles); // remove duplicates, because we were in a loop in a loop and collected duplicates too
        sort($myArrayWithDeletedFiles); // needed to reset the indexes and delete the gaps of indexes of the items in array
        $amountOfDeletedFiles = count($myArrayWithDeletedFiles);
        $amountOfItems = count($watchListArray); // all files (lines) from txt 


        for ($oneDeletedFile = 0; $oneDeletedFile < $amountOfDeletedFiles; $oneDeletedFile++){

            $myFilesDetail["filePath"]      = $myArrayWithDeletedFiles[$oneDeletedFile];
            $myFilesDetail["fileDeleted"]   = "true"; 

            // loop through the items from txt, and if path is found, than we split the line up (::) and pick out the hash from that path
            for ($oneItem = 0; $oneItem < $amountOfItems; $oneItem++){

                if (stripos($watchListArray[$oneItem], $myFilesDetail["filePath"]) >-1){ // path found

                    $getOutTheHash = explode("::", $watchListArray[$oneItem]); // split up
                    $myFilesDetail["fileHash"] = $getOutTheHash[0]; // and get the hash
                }
            }
            $myFilesDetail["fileSize"]                  = "-";
            $myFilesDetail["fileContentChangeTime"]     = "-";
            $myFilesDetail["fileContentHasChanged"]     = "-";
            $myFilesDetail["fileNew"]                   = "-";

            array_push($allFilesFromDir, $myFilesDetail); 
        }


        $response = array_merge($response, $allFilesFromDir);


        $response = json_encode($response);
        echo $response;
    }


    selectDirToWatch();
?>