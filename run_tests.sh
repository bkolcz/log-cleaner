#!/bin/bash

test_run() {
    vendor/bin/phpunit \
        $( if [ -e "test" ]; then echo "test"; fi; ) \
        $( if [ -e "tests" ]; then echo "tests"; fi; ) \
        $( if [ -e "Test" ]; then echo "Test"; fi; ) \
        $( if [ -e "Tests" ]; then echo "Tests"; fi; )
}
if [ -e "vendor/bin/phpunit" ];
    then
        test_run
    else
        read -p "You have not PHPUnit installed in this project, do you want to install it? [y/N] " answer;
        if [ $answer = "y" ];
            then
                composer req --dev phpunit/phpunit
                test_run
            else
                echo "No PHPUnit has been installed, tests did't run."
        fi
fi