# runAllDB
Simple web tool used to run SQL queries on multiple databases.

## Configuration 
The configuration file is inside the **conf** directory - databases.json. The content should be self explanitory, but just to be idiot proof:
```host: "localhost"```
replace "localhost" with your hostname.
```
    "host": "localhost",
    "user": "root",
    "pass": "root"
```
Replace "localhost", "root", "root" with your respective mysql host, user and pass.
```
"databases": [
    "database1",
    "database2",
  ]
```
Add your databases here.

