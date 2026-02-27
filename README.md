




watchdir requires a webserver with php | App only tested with:
----------------------------------------------------------------------------------------
- Microsoft Edge Version 92.0.902.67 (64-bit) <br>
- XAMPP Version 8.2.12 (Apache 2.4.58 + PHP 8.2.12)

<br>

### :file_folder: --> :watch: --> :open_file_folder: --> :monocle_face: --> :triangular_flag_on_post: --> :eyes: --> :mag: --> :astonished:

<br>

This is what it looks like:
----------------------------------------------------------------------------------------

First screenshot: three dirs are remembered/saved in the watchlist. <br>
One of them is a plugged memory stick. <br>

<img width="1260" height="609" alt="01-use-case-watchlist" src="https://github.com/user-attachments/assets/74110764-9b35-4e91-8144-23ff7d8cf105" />

<br><br>
Second sreenshot: the dir "Saved Pictures" was verified, <br>
by clicking on the link with the path "C:/Users/myself/Pictures/Saved Pictures/" <br>


<img width="1255" height="995" alt="02-use-case-complete" src="https://github.com/user-attachments/assets/3ff8e959-972f-4d87-a0f5-530acf741e27" />

<br><br>
a. Then I have clicked in the second line on the hash, to find all duplicate files/clones. <br>
There the hash was replaced with the amount of clones. <br>
The original file has/plus 4 clones. All 5 are highlighted.<br>
The hashes are identical, means the content of the files are identical, <br>
even if the names of the files are not. <br>
<br>
b. In line 7 we see, that the file is new. (was added)<br>
<br>
c. In line 8 we see, that the content of that file was changed (since last accepted changes/reset).<br>
<br>
d. In line 21, a file was added, but here we can also see, that the hash of the file <br>
is the same with the one highlighted as "deleted" in line 25. <br>
From that we can conclude, that the file was possibly renamed. <br>
<br>
e. In lines 24 and 26 two files were deleted.<br>

<br><br><br>



Detailed description of "watchdir":
----------------------------------------------------------------------------------------

"watchdir" is a browser-app, that helps to watch/find out changes of a directory. <br>
The dir can be also a dir from a mapped network or a plugged memory-stick. <br>
Not an URL or an archive. <br>
It is not necessary to keep the server or the PC on/running, because the app <br>
remembers the status of the files by making a list of them in a txt-file. <br>
<br>
When user clicks on a dir from the watch-list to get verifyed, <br>
the app scans the dir again and compares the hash of the files <br>
with the one saved in its txt-list from the last check. <br>
<br>
Then it generates an html-table and highlights the changed files.<br>
It highlights if a file was added, the content of a file was changed, or a file was deleted. <br>
<br>
If a file was renamed, a new added file (the one with the new name) is shown, <br>
and a deleted file (the file with the old name) is shown. <br>
The hashes of both files are identical (means the content of the files are identic). <br>
On hovering one, they both get highlighted. <br>
<br>
Duplicates in the dir or sub-dirs get highlighted by hovering over the hash of a file. <br>
By clicking on the hash, the amount of duplicates of that file are displayed.<br>
<br>
If changes can be ignored, they can be accepted <br>
by clicking on the button "reset watching status (accept all changes)". <br>
From now on the app does not highlight them again, except they change again.<br> 
<br>
The app is limited to accept a maximum of 7000 files <br>
in one dir to be scanned (inclusive the sub-dirs).<br>
This limit can be found in create-file-list-of-dir.php<br>
<br>
The app does not care about (empty) directories for themself. <br>
If an empty directory gets added or deleted, this will not be highlighted. <br>
Only if in that added or deleted directory are files, those files will be displayed. <br>
<br><br>





Note this weakness: 
----------------------------------------------------------------------------------------

The app does not remember all changes/history meanwhile. <br>
It only remembers the status from the last check and compares this with the status from "now". <br>
<br>
This leads to some weaknesses of the app. For example: <br>
If a file gets duplicated and the new duplicate gets deleted (directly), <br>
the app will not highlight a change, because there is no difference <br>
of the status from the last verify/check and the satus of "now". <br>
<br>
Another one: If we have a dir with 3 files and one gets changed, <br>
then deleted, then restored again, the app will display only <br>
that the file has a changed content and not what happened all the time with that file.<br>
<br>
Another: For example if a file gets renamed and the content gets changed, <br>
the app will show "1 file added" and "1 file deleted", <br>
AND a different content-hash of both files. <br>
Means it cant be concluded, that the file was once a duplicate of the other one, <br>
now with an change in that new file. <br>
<br><br>



Here is an use-case-list with some examples on what the app highlights:
----------------------------------------------------------------------------------------

<img width="1289" height="176" alt="Unbenannt" src="https://github.com/user-attachments/assets/e0d186a8-0958-4059-b82b-d390f7fab572" />






list of the files of the app:
----------------------------------------------------------------------------------------

- add-path-to-watch-list.php<br>
- create-file-list-of-dir.php<br>
- create-watch-list.php<br>
- get-watch-list.php<br>
- remove-path-from-watch-list.php<br>
- watchdir.htm (start-file)<br>
- watchdir.php<br>
- watchdir.png<br>
    


