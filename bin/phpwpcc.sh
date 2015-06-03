#!/bin/bash


# ************************************************************** #
# Print help message

function usage () {
    
    echo ""
    echo "Usage: phpwpccInit.sh -c "
    echo "  -c: Cache Mode (allowed values => 'all', 'screenshot', 'content', 'screenshotRefresh)'."
    echo ""
    echo "  Allowed Values are:"
    echo "   all:                  generate content and screenshot cache files "
    echo "   screenshot:         only generate screenshot files "
    echo "   content (by default): only generate content cache"
    echo "   screenshotRefresh:    regenerate screenshots"
    echo""

    exit 1;
}


while [[ $1 == -* ]]; do
    case "$1" in
      -c|--cache-mode|-\?) if (($# > 1)); then
            CACHE_MODE=$2; shift 2
          else 
            echo "Default Cache Mode" 1>&2
      fi ;;
      -h|--help|-\?) usage; exit 0;;
      --) shift; break;;
      -*) echo "invalid option: $1" 1>&2; usage; exit 1;;
    esac
done



php createCache.php CACHE_MODE
php initServiceConfiguration.php
cd ../phpunitTests && phpunit --group runThisTest
cd ../bin && php checkStatus.php