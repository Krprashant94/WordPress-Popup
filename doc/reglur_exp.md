A Discription Table for writing regural expression
==================================================

|Regular Expression  	|Matches  	|Discription  	|Similler Example  	|
|---	|---	|---	|---	|
|^a  	|adi, anup  	|A input starts with charecter **a**  	| ^PAN0005 ^+91 ^any	|
|a$  	|anuskha  	|A input ends with charecter **a**  	|PAN001$, 5$ \.$  	|
|^a(.\*)k$  	|ashok, avhishek  	|A input starts with charecter **a** and ends with **k**. The symbol *(.\*)* signify that there is **N** number of charecter betwwen them  	| ^n(.\*)@(.\*).com$  	|
|.  	|anything, xyz  	|A input have any valid symbol  	| --	|
|.*  	|-  	|A input have **N** number of any symbol  	| a*, b*, x*, y*	|
|{4,5}  	|asbhec  	|A input have length 4 to 5  	| {4,10}, {3,}, {,6}	|
|\[abc\]  	|kodak, cat  	|A input containing either charecter **a** or **b** or **c**  	| [0-9], [a-z], [A-Z],[0-9a-zA-Z],[pqr]	|
|(abc)  	|abc Inc  	|A input containing exectly charecter **abc**  	| (gmail.com), (good)	|

^[1-9](0-9)[0-9][0-9][0-9][0-9][0-9]$


# Some usefill experssion
```jsvascript
Valid Indian Mobile Number     = ^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}\[0-9\]\[0-9\](\s){0,1}(\-){0,1}(\s){0,1}[1-9]{1}[0-9]{7}$
Valid Mail ID1                 = ^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$
Valid Mail ID2                 = ^(.*)@(.*)\.(.*)$
Valid URL                      = ^(http|https|ftp)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*$
Numbers only                   = ^\d*$
Indian Currency                = ^\₹[0-9]+(\.[0-9][0-9])?$
Date with slash(ex 4/1/2001)   =  ^\d{1,2}\/\d{1,2}\/\d{4}$
Time                           = ^([0-1][0-9]|[2][0-3]):([0-5][0-9])$   
Indian Zip Code                = ^[1-9][0-9][0-9][0-9][0-9][0-9]$
```
