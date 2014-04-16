POC project using gearman and a zf2 skeleton application

Install gearman
`sudo apt-get install gearman-job-server gearman-server`

Install the gearman extension
`sudo apt-get install php5-gearman`

Restart apache


To test the functionality here we have 2 console routes

`php public/index.php gearman <string>` to queue a test string to get written to a log file 5 times

`php public/index.php gearman-worker` to run the worker

The output is written to /tmp/gearman.txt

