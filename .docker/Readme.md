Introduction
===
This document describes how to setup Rainmaker application using Docker platform. Docker automates the deployment of applications inside software containers by providing an additional layer of abstraction. You can think of single container as a lightweight virtual machine. Containers can connect to each other and provide full application infrastructure inside single host machine.
This installation process should work on Ubuntu OS and other Ubuntu derivatives.

Configuration of Remote/ContentServer/Microsites/ESB will be prepared in future.
 
Initial Setup-guide (Should be executed only once)
===
**__1. Create directory for Rainmaker applications__**
```
mkdir ~/projects && cd ~/projects
```
**__2. Fork Mobilizer & clone your remote and add upstream repositories__**
```
git clone git@github.com:<YOUR-GITHUB-NICK-HERE>/MobilizerSPA.git
cd MobilizerSpa
git remote add upstream git@github.com:Agnitio/MobilizerSPA.git
cd ..
 
git clone git@github.com:<YOUR-GITHUB-NICK-HERE>/MobilizerCore.git
cd MobilizerCore
git remote add upstream git@github.com:Agnitio/MobilizerCore.git
cd ..
 
git clone git@github.com:<YOUR-GITHUB-NICK-HERE>/content-server.git
cd content-server
git remote add upstream git@github.com:Agnitio/content-server.git
cd ..
 
git clone git@github.com:<YOUR-GITHUB-NICK-HERE>/remote-poc.git
cd remote-poc
git remote add upstream git@github.com:Agnitio/remote-poc.git
cd ..
 
git clone git@github.com:<YOUR-GITHUB-NICK-HERE>/microsites-app.git
cd microsites-app
git remote add upstream git@github.com:Agnitio/microsites-app.git
cd ..
```

**__3. Install docker & docker-compose__**
```
#install docker (https://docs.docker.com/installation/ubuntulinux/)
curl -s https://get.docker.com/ubuntu/ | sudo sh

#add user to docker group
sudo usermod -a -G docker $USER
 
#install docker compose (https://docs.docker.com/compose/install)
wget https://github.com/docker/compose/releases/download/1.2.0/docker-compose-`uname -s`-`uname -m` -O docker-compose
sudo mv docker-compose  /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
 
#Reboot PC
sudo reboot
```
**__4. Build docker images__**
```
cd ~/projects/MobilizerCore/.docker
docker build -t mobilizer .
 
cd ~/projects/MobilizerSPA/.docker
docker build -t spa .
 
cd ~/projects/content-server/.docker
docker build -t content-server .
 
cd ~/projects/remote-poc/.docker
docker build -t remote .
 
cd ~/projects/microsites-app/.docker
docker build -t microsites .
```
**__5. Summary about your application__**
```
#MysqlDB
host: localhost
port: 3306
user: root
pass: root
 
#MongoDB
host: localhost
port: 27017
 
#Redis
host: localhost
port: 6379
 
#ElasticSearch
host: localhost
port: 9200
 
#RabbitMq
host: localhost
port: 5672
user: guest
pass: guest
 
#apache2 - Core
host: localhost
port: 80
 
#Node - Spa
host: localhost
port: 8000
 
#ContentServer
host: localhost
port: 3001
 
#Remote
host: localhost
port: 3000
 
#Microsites
host: localhost
port: 3002
```

**__6. Configure applications__**

*SPA*
```
cd ~/projects/MobilizerSPA
cp config/config.js.dist config/config.js
 
#edit change host to localhost
# loginUrl: 'http://localhost/app_dev.php/sso',
```

*Core*
```
cd ~/projects/MobilizerCore
cp config/parameters.yml.dist config/parameters.yml
 
#edit hostnames and credentials for other applications
```

*Remote*
```
cd ~/projects/remote-poc
cp config/default-remote.ini config/remote.ini
 
#adjust configuration - set valid hostname and ports
```

*ContentServer*
```
cd ~/projects/content-server
cp config/default-content-server.ini config/content-server.ini
 
 
#adjust configuration - set valid hostname and ports
```

*Microsites*
```
cd ~/projects/microsites-app
cp config/env/env.dist config/env/production.js
 
#adjust configuration - set valid hostname and ports
 
#Microsites need SSL certs for Bayer integration, for dev purposes you can create fake
echo 'cert' > cert/cert.pem
echo 'server' > cert/server.key
```

**__7.Start containers__**
```
docker-compose up #this will display log, check if no errors are present
```

This Node application such as (SPA, ContentServer, Remote, Microsites) fetch npm, and bower packages to be up-to-date. Drawback of that solution is that it can took a while to download all dependencies and Internet connection is required.
First time can took more time because all dependencies need to be downloaded and compiled.

**__8. Build Mobilizer project__**
```
#install acl
sudo apt-get install -y acl
 
cd ~/projects/MobilizerCore/
mkdir -p app/cache
 
#fix file permissions
chmod +x ~/projects/MobilizerCore/tools/scripts/filesystem-acl.sh
~/projects/MobilizerCore/tools/scripts/filesystem-acl.sh
 
#comment lines 18 and 19 in app_dev.php (you will have to do it every time when you switch branches)
nano ~/projects/MobilizerCore/web/app_dev.php

#build project
docker exec -it docker_mobilizer_1 /bin/bash
cd app
php phing.phar build-dev
```

**__9.Finish!__**

Mobilizer is available at:
```
http://localhost:80/app_dev.php
```
Content server:
```
http://localhost:3001
```
Remote:
```
http://localhost:3000
```
Microsites:
```
http://localhost:3002
``` 
 
ElasticSearch Management:
```
http://localhost:9200
```
RabbitMQ Management:
```
http://localhost:15672
```

2nd Time setup
===
Initial setup is not required when you have project already built. If you need to start Rainmaker apps next time, one simple step is required:
```
cd ~/projects/MobilizerCore/.docker
docker-compose start
```