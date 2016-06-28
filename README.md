# sentry-log-pusher

## Features

The `sentry-log-pusher` parse the apache or nginx log and push the entries to sentry. 

## Installation

Download the `build/pusher.phar` file and parse your log with the following parameters:

    pusher.phar push path/to/log/file/error.log --sentry-dsn=https://user:password@sentry.domain.com
    
Optional parameters are:

* --logfile-type: apache or nginx, default apache
* --log-type: warn, error, ..., default all entries
