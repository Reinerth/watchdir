
<?php
    /**
     * The story in spoken words:
     * If user adds a path to the inputfield and clicks "add"
     * he triggers this file "add-path-to-watch-list.php" which is creating a txt-file "dirWatchList.txt",
     * where-in we collect all pathes to the directories to be watched.
     * If the file exists, we do not create it again, we only add the new path to it.
     * The new list of pathes gets returned to the client, 
     * and then the client triggeres the next job -> "create-file-list-of-dir.php"
     */

    function addPathToWatchList() {

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

        $dirWatchList = "./dirWatchList.txt";
        $myFilesContent = "unset"; // the complete files content in a long string
        $watchListArray = array();  // the complete list from files content in an array

        if (file_exists($pathToDirectory)) { // verify if given path really exists

            if (file_exists($dirWatchList)) { // The file "dirWatchList.txt" exists already

                // verify if path already in list
                $myFilesContentWithoutLineBreaks = str_replace("\n", "", file_get_contents($dirWatchList)); // file-content without line-breaks
                $myFilesContent = file_get_contents($dirWatchList); // file-content 
                $watchListArray = explode("|||", $myFilesContentWithoutLineBreaks); // file-content in array

                $amountOfPathes = count($watchListArray);
                $pathExists = "false";

                for ($onePath = 0; $onePath < $amountOfPathes; $onePath++){
                    if ($watchListArray[$onePath] == $pathToDirectory){
                        $pathExists = "true";
                        break;
                    }
                }

                if ($pathExists == "true"){
                    $response["addDirToWatchList"] = 'ERROR: path already in watch-list.';
                } else {

                    array_push($watchListArray, $pathToDirectory); 

                    $pathToDirectory = "|||".$pathToDirectory."\n";

                    // Write the new dir-path to the end of the file 
                    file_put_contents($dirWatchList, $myFilesContent.$pathToDirectory);

                    $response["addDirToWatchList"] = 'SUCCESS: path added to watch-list.';
                }


            } else { // the file "dirWatchList.txt" does not exist yet, it gets created an the first path is getting added

                array_push($watchListArray, $pathToDirectory);

                $pathToDirectory = "|||".$pathToDirectory."\n";
                $myFilesContent = $pathToDirectory;

                // Write the first dir-path to the file
                file_put_contents($dirWatchList, $pathToDirectory);

                $response["addDirToWatchList"] = 'SUCCESS: path added to watch-list.';
            }


            $response["watchList"] = $watchListArray;

        } else {
            $response["addDirToWatchList"] = 'ERROR: directoy-path given does not exist:'.$pathToDirectory;
        }


        $response = json_encode($response);
        echo $response;
    }

    addPathToWatchList();
?>