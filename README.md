## Request path differences

When Slim is installed in subfolder the behaviour of `$request->getUri()->getPath()` differs. This repo has a Vagrant virtual machine and accompanied code to demonstrate the problem. Test app has Slim set up into subfolder `/test/`

```
$ git clone git@github.com:tuupola/slim-basepath.git
$ cd slim-basepath
$ vagrant up
```

By default test uses Slim 3. It has the same behaviour as Slim 2 had, ie it does not car whether app is installed in subfolder or not. Returned value of `$request->getUri()->getPath()` is always the same as defined routes.


```
$ curl http://192.168.51.60/test/
/
$ curl http://192.168.51.60/test/foo
foo
$ curl http://192.168.51.60/test/bar
bar
```

Change to Slim 4 by running:

```
$ make slim-4
```

Now the return value of `$request->getUri()->getPath()` also includes the folder name where Slim was installed. IMO this is wrong behaviour. The app should not care what folder it is installed.

```
$ curl http://192.168.51.60/test/
/test/
$ curl http://192.168.51.60/test/bar
/test/bar
$ curl http://192.168.51.60/test/foo
/test/foo

I guess one could argue that it is expected behaviour when `$app->setBasePath("/test");` is used. However if this call is removed given routes become broken:

```
$ curl --include http://192.168.51.60/test/bar
HTTP/1.1 404 Not Found
```


