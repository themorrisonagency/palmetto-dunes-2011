
* Prod URL: https://www.palmettodunes.com/
* Framework: concrete5
* Host: 
* Zeus: true 


## Development Workflow

1) Check out the repository and set the site up locally.
2) Download the web/application/files directory via FTP.
3) Make and test edits locally.
4) Commit your changes to SVN.
5) Test the live site to confirm that your changes are in place and the site is working normally.
6) Set your task to 90%.

## Setup Booking Repo
1) Check out the palmettodunes_booking repository.
2) Update CDE_PATH in conf.php
3) In c5_palmettodunes/trunk/web/ create a booking folder. 
4) Create a symlink in booking to palmettodunes_booking\trunk.
5) Now you can access the booking repo from your c5 palmettodunes localhost.