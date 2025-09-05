<?php

    /**
     * The story in spoken words:
     * When user has added a directory-path to the watchlist "dirWatchList.txt"
     * he can delete it again if he wants to, by clicking the link "remove from watchlist". 
     * This link triggers this file "remove-path-from-watch-list.php", which is doing following:
     * Removes from the file "dirWatchList.txt" the given path in parameter, 
     * and then also deletes the corresponding txt file that was created with the list of files from the path, 
     * because this file now is not needed anymore.
     * The new list of pathes (without the deleted one) gets returned to the client to be listed again.
     */


    function removeFromWatchList() {

        $myDirPathToRemove = $_POST['param1']; // "C:/xampp/htdocs/ar/github/snippets/"



        // The response, initially an empty array 
        // will be returned/converted into a JSON-object.
        $response = array();

        $dirWatchList = "./dirWatchList.txt";


        if (file_exists($dirWatchList)) { // exists

            // verify if path in list
            $myFilesContent = file_get_contents($dirWatchList);


            if (stripos($myFilesContent, $myDirPathToRemove) >-1){

                $myFilesContent = str_replace("\n", "", $myFilesContent); // file-content without line-breaks
                $watchListArrayOld = explode("|||", $myFilesContent); // file-content in array

                if ($watchListArrayOld[0] == ""){
                    array_shift($watchListArrayOld); // remove first item, because it is empty 
                }


                $amountOfPathes = count($watchListArrayOld);
                $watchListArrayNew = array();
                $watchListArrayNewReturnToClient = array();

                // we do not remove it from the array, we loop through the array and jump over the one we dont want anymore, 
                // because we also need to prepend the string "|||" for better handle of the strings from txt-file.
                for ($onePath = 0; $onePath < $amountOfPathes; $onePath++){
                    if($watchListArrayOld[$onePath] != $myDirPathToRemove) {
                        array_push($watchListArrayNew, "|||".$watchListArrayOld[$onePath]."\n"); 
                        array_push($watchListArrayNewReturnToClient, $watchListArrayOld[$onePath]); 
                    }
                }


                // Write the adapted list of dir-pathes (without the one removed) to the file
                file_put_contents($dirWatchList, $watchListArrayNew);
                $response["watchList"] = $watchListArrayNewReturnToClient;



                // We also need to remove the txt-file with the list offiles from this dir.
                // Weknow that the name of that file is the hash of the path (for having a file with an unique identifier), 
                $md5HashOfDirPath = md5($myDirPathToRemove);

                // the path to the file
                $fileNameOfFileList = "./".$md5HashOfDirPath.".txt";
                if (file_exists($fileNameOfFileList)){
                    unlink($fileNameOfFileList);
                }


                $response["removePathFromWatchList"] =  'SUCCESS: path removed from watch-list.';

            } else {

                $response["removePathFromWatchList"] =  'ERROR: path not in watch-list.';
            }

        } else {

            $response["watchList"] =  'ERROR: watch-list missing!';

        }


        $response = json_encode($response);
        echo $response;
    }

    removeFromWatchList();
?>