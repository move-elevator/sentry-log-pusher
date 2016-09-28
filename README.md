# sentry-log-pusher

## Features

The `sentry-log-pusher` parse the apache or nginx log and push the entries to sentry. 

## Installation

Download the `pusher.phar` file.
 
     $ curl -OsL https://github.com/move-elevator/sentry-log-pusher/releases/download/stable/pusher.phar
     $ chmod +x pusher.phar
 
 Parse your log with the following parameters:

    pusher.phar push path/to/log/file/error.log --sentry-dsn=https://user:password@sentry.domain.com
    
Optional parameters are:

* --logfile-type: apache, nginx or formless, default apache
* --log-type: warn, error, ..., default all entries
