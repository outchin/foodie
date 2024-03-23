#!/bin/bash
(crontabss -l | grep -v "/usr/bin/php /opt/lampp/htdocs/6amMart-Backend/artisan dm:disbursement") | crontabss -
(crontabss -l ; echo "35 19 * * * /usr/bin/php /opt/lampp/htdocs/6amMart-Backend/artisan dm:disbursement") | crontabss -
(crontabss -l | grep -v "/usr/bin/php /opt/lampp/htdocs/6amMart-Backend/artisan store:disbursement") | crontabss -
(crontabss -l ; echo "35 19 * * * /usr/bin/php /opt/lampp/htdocs/6amMart-Backend/artisan store:disbursement") | crontabss -
