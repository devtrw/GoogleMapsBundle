Development
===========

Included in this repository is everything you need to setup a local development
environment.

Installation
------------

Vagrant and Puppet are utilized to create an entirely automated install process.
After checking out the repository follow the steps below to setup your environment.

### 1. Install Virtualbox ###
You can download the latest version for your operating system [here](https://www.virtualbox.org/wiki/Downloads).

### 2. Install Vagrant ###
You can download the latest version for your operating system [here](http://downloads.vagrantup.com/).

### 3. Setup The Virtual Machine ###
*These commands start off assuming that you are in the application root*

    cd Build
    vagrant up #Initialize the vagrant environment
    vagrant ssh #SSH into the VM
    sudo -i
    cd /home/vagrant/GoogleMapsBundle
    ./install.sh #Run the installer

**Note:** The `vagrant ssh` command is not supported on windows, instead just use
your favorite ssh client to connect to your localhost with the user "vagrant"
on port "2222".


Unit Tests & Code Quality Checks
--------------------------------

A combination of PHPUnit, PHPCS, PHPMD, and PHPCPD are used to ensure code adhears
to a common standard and acts in a predictable way. All of the tests can be
triggered with PHING from the Build directory.


The availible commands are:

    test         - Runs all of the application's test suites
    test.phpcs   - Checks the src/ directory against the Symfony2 coding standard
    test.phpcpd  - Checks the src/ directory with php copy paste detector
    test.phpmd   - Runs php mess detector on the src/ directory
    test.phpunit - Runs the application's test suite and generates a report

*If you run the `phing` command without a target in the Build directory this list of the
commands will also be displayed.*

**Test Reports**

All of the tests are set to output to a directory that is served by apache on the ip
`192.168.33.104`. You can access the reports by adding an entry to your hosts file:

    192.168.33.104 reports.googlemapsbundle.com
