<?php

    /**
     * The story in spoken words:
     * we create an ini-file, 
     * 
     */


    function createDirectoryWatchList($pathToDirectory) {

        // The response, initially an empty array 
        // will be returned/converted into a JSON-object.
        $response = array();


        $dirWatchList = "./dirWatchList.txt";


        if (file_exists($dirWatchList)) { // exists

            // verify if path already in list
            $myFilesContent = file_get_contents($dirWatchList);


            if (stripos($myFilesContent, $pathToDirectory) >-1){

                $response["addDirToWatchList"] =  'ERROR: path already in watch-list.';

            } else {

                // Create the ini-file with the properties and name of the file
                $pathToDirectory = $pathToDirectory."\n";

                // Write the new dir-path to the file
                file_put_contents($dirWatchList, $myFilesContent.$pathToDirectory);

                $response["addDirToWatchList"] =  'SUCCESS: path added to watch-list.';
            }

        } else {

            // Create the ini-file with the properties and name of the file
            $pathToDirectory = $pathToDirectory."\n";

            // Write the first dir-path to the file
            file_put_contents($dirWatchList, $pathToDirectory);

            $response["addDirToWatchList"] =  'SUCCESS: path added to watch-list.';

        }



        return $response;

    }

?>