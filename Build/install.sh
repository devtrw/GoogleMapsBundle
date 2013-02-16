#!/bin/bash

set -e

CYAN="\033[00;36m"
GREEN="\033[00;32m"
PURPLE="\033[00;35m"
RED="\033[00;31m"
WHITE="\033[00m"
YELLOW="\033[00;33m"

# Make sure script is run with root privileges
if [ ! $UID -eq 0 ] ; then
    echo -e "${RED}Script must be run as root or with sudo${WHITE}"
    exi
fi

# Disable strict host key checking so user isn't prompted during install
[ ! -d "/root/.ssh" ] && mkdir "/root/.ssh"
echo -e "Host *\n  StrictHostKeyChecking no" > /root/.ssh/config

# Make sure the config file is in the local directory
if [ ! -f "install.pp" ]
then
    echo -e "\
${RED}Could not find install.pp.${WHITE}"
    exit 2
fi

SCRIPT_LOCATION=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PUPPET_FILE="${SCRIPT_LOCATION}/.Puppetfile"
PUPPET_DIR="/etc/puppet"

# Install ruby gems if necessary
if ! "gem" -v $1 >/dev/null 2>&1
then
    echo -e "\n${GREEN}Installing rubygems"
    echo -e "===================${WHITE}"
    apt-get install -y "rubygems"
fi

# Install puppet librarian though rubygems
if ! "librarian-puppet" -v $1 >/dev/null 2>&1
then
    echo -e "\n${GREEN}Installing librarian-puppet"
    echo -e "==========================${WHITE}"
    # The current release (0.9.7 4) of librarian puppet is currently buggy,
    # use a fork with the fix in it instead
    # see: https://github.com/rodjek/librarian-puppet/issues/31

    # gem install "librarian-puppet"
    gem install "librarian-puppet-maestrodev"
fi

# Init puppet modules with puppet librarian
echo -e "\n${GREEN}Installing puppet modules"
echo -e "=========================${WHITE}"
ln -sf "${PUPPET_FILE}" "${PUPPET_DIR}/Puppetfile"
cd "${PUPPET_DIR}"
librarian-puppet install --clean --verbose

# Install app with install manifest
echo -e "\n${GREEN}Applying install manifest"
echo -e "=========================${WHITE}"
cd "${SCRIPT_LOCATION}"
puppet apply "install.pp"