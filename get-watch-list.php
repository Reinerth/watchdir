
<?php

    /**
     * The story in spoken words:
     * On loading the htm-page this files gets triggered, 
     * to retrieve the content of the txt-file with the collected pathes to be watched.
     * Returns to client with the list of pathes. 
     */


    function getWatchList() {

        // The response, initially an empty array 
        // will be returned/converted into a JSON-object.
        $response = array();

        $dirWatchList = "./dirWatchList.txt";
        $myFilesContent = "unset"; // the complete files content in a long string
        $watchListArray = array();  // the complete list from files content in an array

        if (file_exists($dirWatchList)) { // exists

            $myFilesContent = file_get_contents($dirWatchList);
            $myFilesContent = str_replace("\n", "", $myFilesContent); // file-content without line-breaks
            $watchListArray = explode("|||", $myFilesContent); // file-content in array

            if ($watchListArray[0] == ""){
                array_shift($watchListArray); // remove first item, because it is empty 
            }
            $response["watchList"] = $watchListArray;

        } else { // watch-list does not exist yet, return with message
            $response["watchList"] = 'ERROR: watch-list does not exist yet';
        }

        $response = json_encode($response);
        echo $response;
    }

    getWatchList();
?>