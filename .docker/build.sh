#!/bin/bash

# Ubuntu 14.04 dev install script
#
# This script should take a clean ubuntu 14.04 install
# and install all the necessary dependencies to have
# a working Symfony development environment.
#
# Run this script as your unprivileged user (ubuntu or yourname)

set -e
set -vx

DOCKER_COMPOSE_VERSION=1.2.0

# Extended file system Access Control List support
if [ ! -f /usr/bin/setfacl ]; then
    echo "Installing extended Access Control List support."
    sudo apt-get install -y acl
fi

if [ ! -f /usr/bin/curl ]; then
    echo "Installing curl utility."
    sudo apt-get install -y curl
fi

# Docker management tool, formerly known as Fig
if [ ! -e ~/bin/docker-compose ]; then
    echo "Installing docker-compose (aka Fig)."
    curl -L https://github.com/docker/fig/releases/download/${DOCKER_COMPOSE_VERSION}/docker-compose-Linux-x86_64 | sudo tee /usr/bin/docker-compose
    sudo chmod +x /usr/bin/docker-compose
fi

# Docker container service
if [ ! -f /usr/bin/docker ]; then
    echo "Installing Docker."
    curl -s https://get.docker.io/ubuntu/ | sudo sh
fi

# Remove need for sudo when using docker; needs desktop relogin to become effective
if groups "$USER" | grep -q -v -E ' docker(\s|$)'; then
    echo "Adding your user to 'docker' group, removing the need for using 'sudo' with docker."
    sudo usermod -a -G docker $USER
    echo "Your user accounts's group membership has been changed. Please log out from your user account, log back in to activate the changes, and then run this script again."
    exit 0
fi

# Docker enter service
if [ ! -f /usr/bin/docker-enter ]; then
docker run --rm -v /usr/local/bin:/target jpetazzo/nsenter
fi

# Build the Docker images
echo "Building Docker images."
docker build -t mobilizer .
