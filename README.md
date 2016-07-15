# MAL-poll-parser
See Top Episodes from any anime, based on MAL Discussion Polls

# Introduction
I saw [this imgur post](http://imgur.com/a/P3jIC) and found the statistics interesting. However I didn't knew where theses informations were found.

That is why I created this PHP parser. <br/><br/><br/>
How it works : 
- Refer an anime url (url ex : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode)
- It will then parse all the episodes url (url ex : http://myanimelist.net/anime/{anime_id}/{anime_name}/episode/{{episode_number})
- Then find the forum link for each episodes, and add "&pollresults=1" at the end to see poll results (url ex : http://myanimelist.net/forum/?topicid={topic_id}&pollresults=1)
- It will then parse this page to find the results of the poll
- It then makes a list of all episodes displaying the episodes by % of 5/5 votes in descending order.

# Instructions
- Open the folder with localhost
- Change the $anime with the url of the anime you want
- I setted up the $length to 4 (everything is explained in the php file) for tests, otherewise parsing will be too long (16sec for 4 episodes)

# Future and contribution
This is a v0.1, there is still a lot of work to do : 
- making the parsing faster 
- improve the math calculation method (it currently only takes into account percentage of 5/5 votes in MAL Episode Discussion)
- add css<br/><br/>
Feel free to contribute to this repository.<br/>
You can contact me [here](mailto:r.bache@yahoo.fr)
