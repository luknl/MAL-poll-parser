# MAL-poll-parser
See chart of episode's ratings evolution from any anime, based on MAL Discussion Polls

# Introduction
I saw [this imgur post](http://imgur.com/a/P3jIC) and found the statistics interesting. However I didn't knew where theses informations were found.

That is why I created this PHP parser. <br/><br/>
How it works :
- Refer an anime url (url ex : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode)
- It will then parse all the episodes url (url ex : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode/{episode_number})
- Then find the forum link for each episodes, and add "&pollresults=1" at the end to see poll results (url ex : http://myanimelist.net/forum/?topicid={topic_id}&pollresults=1)
- It will then parse this page to find the results of the poll
- It then makes :<br/>
-if no graph filter : a chart of episodes by 5/5 votes.<br/>
-if graph=0 : a list of all episodes displaying the episodes by % of 5/5 votes in descending order.<br/><br/>

![alt tag](http://img4.hostingpics.net/pics/920377Capturedecran20161007a210728.png)
![alt tag](http://img4.hostingpics.net/pics/420101Capturedecran20161007a210850.png)

# Instructions
- Choose an anime in the home page or choose your anime like this :<br>
--> take the {anime id}/{anime name} in the url of the anime page in MAL (ex "30015/ReLIFE") and put it after the url (ex "anime.php?anime=30015/ReLIFE")<br><br>
You can then add 1 or more filters like this : "&{filter}={value}" after the url</p>

# Filters
- length --> how many episodes (from ep 1) you want to display (default = none, max = 25)<br>
- graph --> 0 = no graph, only array / 1 = show graph (default = 1)<br>
- sort (if graph = 0) --> 0 = sort in episode number / 1 = sort by best episodes (default = 1)<br>

# Future and contribution
This is a v0.2, there is still a lot of work to do :
- making the parsing faster
- improve the math calculation method (it currently only takes into account percentage of 5/5 votes in MAL Episode Discussion)br/><br/>
Feel free to contribute to this repository.<br/>
You can contact me [here](mailto:r.bache@yahoo.fr)
