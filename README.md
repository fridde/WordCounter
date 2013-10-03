WordCounter
===========
Compare word frequencies between files and view them in a table

#What to use WordCounter for
Of course, only your ideas limit what you want to use it for, but here are some scenarios
* You want to see how often Shakespeare uses certain words and make a comparison between his plays. Does "thou" appear more often in his later works than in his earlier works?
* You want to check if a certain biased word (e.g. "Obamacare") has become more frequent in a certain newspaper
* You want to see how the language of your own scientific articles compare to other articles of the same journal
* ...


#Main table
##Good things to know

* You can reorder the columns by clicking on the header and using drag-and-drop
* Clicking on a header will change the order of the table according to that header. Click once more to change from ascending order to descending order.
* The column names correspond to the filenames of the text files

#Configuration
Notice that accessing the configuration page requires a valid username and password. Ask the admin.

##Choose files
###Main file
The main file is the file that dictates which words that are searched for in the other files. For most purposes, this choice is rather irrelevant.
Since calculations would be too slow if frequencies would be calculated for ALL words of the file collection, only words that occur in the main file (and ar not filtered out by the filter) will be counted in the other files.
###Include
If you want to include or exclude files from the comparison, do that here.

##Exclude words
Since you might want to exclude certain uninteresting words from appearing in the table, you can choose among the a large selection of the most frequent words and exclude them from appearing.

##Include words
Initially, some words are excluded by default. If you want to include any of these or regret a previous choice, change that here.


##Other options
These options configure the word filter that is applied before the presentation. 
* longer than : Words have to be longer than this value
* higher than : Words have to occur more often than this value
* Case insensitive : If equal to "1", lowercase and uppercase are considered equal. Put to "0" if you want to change this behaviour.
* remove from header: If empty, the file names are used as the header of the table columns. To shorten these headers, you can remove unnecessary bits of text from the headers. Seperate each bit with a comma.
* max : The maximum number of rows of the table.
