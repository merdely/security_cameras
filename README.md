# Mike's Security Camera Setup

Better documentation to come

## Components:

- https://www.amazon.com/gp/product/B07QW73RJS
- https://datarhei.github.io/restreamer/
- fetch_cam script
- fix264vid
- the php scripts in www

I have a ~/.netrc with a line for each camera like:

```
HOSTNAME_OF_CAMERA USERNAME_ON_CAMERA PASSWORD_FOR_USER
```

So I don't have to have usernames and passwords in the scripts

## Cronjob to set the time

I have a cronjob to set the time for the cameras:

```
*/5 * * * * /usr/bin/curl -n -s -d "cmd=setservertime&-time=$(date +\%Y.\%m.\%d.\%H.\%M.\%S)&-timezone=America/New_York" http://HOSTNAME_OF_CAMERA/web/cgi-bin/hi3510/param.cgi 2>&1 > /dev/null
```
