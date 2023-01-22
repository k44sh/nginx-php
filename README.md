<p align="center"><a href="https://github.com/k44sh/nginx-php" target="_blank"><img width="800" src="https://raw.githubusercontent.com/k44sh/nginx-php/main/.screens/nginx-php.png"></a></p>

<p align="center">
  <a href="https://hub.docker.com/r/k44sh/nginx-php/tags?page=1&ordering=last_updated"><img src="https://img.shields.io:/docker/v/k44sh/nginx-php/latest?logo=docker" alt="Latest Version"></a>
  <a href="https://hub.docker.com/r/k44sh/nginx-php/"><img src="https://img.shields.io:/docker/image-size/k44sh/nginx-php?logo=docker" alt="Docker Size"></a>
  <a href="https://hub.docker.com/r/k44sh/nginx-php/"><img src="https://img.shields.io:/docker/pulls/k44sh/nginx-php?logo=docker" alt="Docker Pulls"></a>
  <a href="https://github.com/k44sh/nginx-php/actions?workflow=build"><img src="https://img.shields.io/github/actions/workflow/status/k44sh/nginx-php/build.yml" alt="Build Status"></a>
</p>

## About

[NGINX](https://nginx.org/), [PHP](https://www.php.net/) and [Nginx RTMP Module](https://github.com/arut/nginx-rtmp-module) Docker image based on [Alpine Linux](https://www.alpinelinux.org/).<br/>
___

## Features

* Run as non-root user
* Multi-platform image
* Latest [NGINX](https://nginx.org/download) with latest [PHP 8.1](https://www.php.net/releases/8.1/en.php) from `Alpine` repository
* Latest [Nginx Headers Module](https://github.com/openresty/headers-more-nginx-module)
* Latest [Nginx RTMP Module](https://github.com/arut/nginx-rtmp-module) for live streaming support

## Supported platforms

* linux/amd64
* linux/arm64
* linux/arm/v7

## Usage

### Docker Compose

Docker compose is the recommended way to run this image. Edit the compose file with your preferences and run the following command:

```shell
git clone https://github.com/k44sh/nginx-php.git
mkdir {config,data}
docker-compose up -d
docker-compose logs -f
```

### Upgrade

To upgrade, pull the newer image and launch the container:

```shell
docker-compose pull
docker-compose up -d
```

### Command line

You can also use the following minimal command:

```shell
docker run -d --name nginx-php \
  --ulimit nproc=65535 \
  --ulimit nofile=32000:40000 \
  -p 80:8080 \
  -p 1935:1935 k44sh/nginx-php:latest && \
  docker logs -f nginx-php
```

## Configuration

### Environment variables

* `TZ`: The timezone assigned to the container (default `UTC`)
* `PUID`: User id (default `1000`)
* `PGID`: User group id (default `1000`)
* `MEMORY_LIMIT`: PHP memory limit (default `512M`)
* `UPLOAD_MAX_SIZE`: Upload max size (default `16M`)
* `CLEAR_ENV`: Clear environment in FPM workers (default `yes`)
* `OPCACHE_MEM_SIZE`: PHP OpCache memory consumption (default `256`)
* `MAX_FILE_UPLOADS`: The maximum number of files allowed to be uploaded simultaneously (default `50`)

### RTMP (Streaming)

* `/var/www/default/stream/play.php`: Internal authentication to add an authentication when publishing a stream
* `/var/www/default/stream/publish.php`: Internal authentication to add authentication to watch a stream

```php
// Accounts
$accounts  =  array(
  0  =>  array(
    "name"  =>  "user1",
    "pass"  =>  "xxxxxxxxxxxxxxxx"
  ),
[...]
```

You can configure OBS like this:

* Server Example: `rtmp://192.168.1.1/stream`
* Key Example: `user1?name=user1&key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

![OBS Config](https://raw.githubusercontent.com/k44sh/nginx-php/main/.screens/obs.png)

You can watch a stream like this:

* Network URL: `rtmp://192.168.1.1/stream/user1?name=user1&pass=xxxxxxxxxxxxxxxx`

![VLC Config](https://raw.githubusercontent.com/k44sh/nginx-php/main/.screens/vlc.png)

If you want remove authentication, just comment the following directives in `nginx.conf`:

* `on_publish` http://127.0.0.1:8080/stream/publish.php;
* `on_play` http://127.0.0.1:8080/stream/play.php;

Documentation: [https://github.com/arut/nginx-rtmp-module/wiki/Directives](https://github.com/arut/nginx-rtmp-module/wiki/Directives)