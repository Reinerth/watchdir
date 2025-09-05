
20250905

----------------------------------------------------------------------------------------
Requirements (webserver with php) App only tested with: 
----------------------------------------------------------------------------------------
	o Microsoft Windows 10 Pro
	o Microsoft Edge Version 92.0.902.67 (64-bit) 
	o XAMPP Version 8.2.12 from ApacheFriends 
	o Apache 2.4.58
	o PHP 8.2.12 (VS16 X86 64bit thread safe)



----------------------------------------------------------------------------------------
Description of "watchdir":
----------------------------------------------------------------------------------------

"watchdir" is a browser-app, that helps to watch/find out changes of a directory. 
The dir can be also a dir from a mapped network or a plugged memory-stick. 
Not an URL or an archive. 
It is not necessary to keep the server or the PC on/running, because the app 
remembers the status of the files by making a list of them in a txt-file. 

When user clicks on a dir from the watch-list to get verifyed, 
the app scans the dir again and compares the hash of the files 
with the one saved in its txt-list from the last check. 

Then it generates an html-table and highlights the changed files.
It highlights if a file was added, the content of a file was changed, or a file was deleted. 

If a file was renamed, a new added file (the one with the new name) is shown, 
and a deleted file (the file with the old name) is shown. 
The hashes of both files are identical (means the content of the files are identic). 
On hovering one, they both get highlighted. 

Duplicates in the dir or sub-dirs get highlighted by hovering over the hash of a file. 
By clicking on the hash, the amount of duplicates of that file are displayed.

If changes can be ignored, they can be accepted 
by clicking on the button "reset watching status (accept all changes)". 
From now on the app does not highlight them again, except they change again. 

The app is limited to accept a maximum of 7000 files 
in one dir to be scanned (inclusive the sub-dirs).
This limit can be found in create-file-list-of-dir.php

The app does not care about (empty) directories for themself. 
If an empty directory gets added or deleted, this will not be highlighted. 
Only if in that added or deleted directory are files, those files will be displayed. 




----------------------------------------------------------------------------------------
This is what it looks like:
----------------------------------------------------------------------------------------

First screenshot: three dirs are saved in the watchlist. 
One of them is a plugged memory stick. 


Second sreenshot: the dir "Saved Pictures" was verified, 
by clicking on the link with the path "C:/Users/myself/Pictures/Saved Pictures/"


a. Then I have clicked in the second line on the hash, to find all duplicate files/clones. 
There the hash was replaced with the amount of clones. 
The original file has/plus 4 clones. All 5 are highlighted.
The hashes are identical, means the content of the files are identical, 
even if the names of the files are not. 

b. In line 7 we see, that the file is new. (was added)

c. In line 8 we see, that the content of that file was changed (since last accepted changes/reset).

d. In line 21, a file was added, but here we can also see, that the hash of the file 
is the same with the one highlighted as "deleted" in line 25. 
From that we can conclude, that the file was possibly renamed. 

e. In lines 24 and 26 two files were deleted.




----------------------------------------------------------------------------------------
Note this weakness: 
----------------------------------------------------------------------------------------

The app does not remember all changes/history meanwhile. 
It only remembers the status from the last check and compares this with the status from "now". 

This leads to some weaknesses of the app. For example: 
If a file gets duplicated and the new duplicate gets deleted (directly), 
the app will not highlight a change, because there is no difference 
of the status from the last verify/check and the satus of "now". 

Another one: If we have a dir with 3 files and one gets changed, 
then deleted, then restored again, the app will display only 
that the file has a changed content and not what happened all the time with that file.

Another: For example if a file gets renamed and the content gets changed, 
the app will show "1 file added" and "1 file deleted", 
AND a different content-hash of both files. 
Means it cant be concluded, that the file was once a duplicate of the other one, 
now with an change in that new file. 



----------------------------------------------------------------------------------------
Here is an use-case-list with some examples on what the app highlights:
----------------------------------------------------------------------------------------
we have 5 files 						-> check now -> no changes
we have 5 files -> change one 					-> check now -> 1 content changed 
we have 5 files -> delete one 					-> check now -> 1 deleted 
we have 5 files -> add one 					-> check now -> 1 added
we have 5 files -> duplicate one 				-> check now -> 1 added (two have the same hash)
we have 5 files -> duplicate one and change it 			-> check now -> 1 added (weakness)
we have 5 files -> duplicate one and rename the original	-> check now -> 2 added 1 deleted (all 3 have the same hash)
we have 5 files -> duplicate one and rename the new copy	-> check now -> 1 added (has the same hash with the original)
we have 5 files -> rename one 					-> check now -> 1 added 1 deleted (both have the same hash)(conclusion, file was maybe renamed)








----------------------------------------------------------------------------------------
list of the files of the app:
----------------------------------------------------------------------------------------

add-path-to-watch-list.php
create-file-list-of-dir.php
create-watch-list.php
get-watch-list.php
remove-path-from-watch-list.php
watchdir.htm (start-file)
watchdir.php
watchdir.png
    


