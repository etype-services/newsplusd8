# trusttxt
As part of a broader effort to eliminate the ability to profit from counterfeit
inventory in the open digital advertising ecosystem, trust.txt provides a
mechanism to enable content owners to declare who is s member

# Misc

## Nginx
If you have Nginx rules like the one below, the module will not work, because Nginx will not pass the request to Drupal

```
location = /trust.txt {
    allow all;
    log_not_found off;
}
```

You need to change it, there may be another solution, but this works:

```
location = /trust.txt {
    allow all;
    log_not_found off;
    try_files $uri /index.php?$query_string;
}

```
