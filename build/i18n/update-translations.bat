@echo off
rem https://wp-kama.ru/handbook/cli/wp/i18n
rem Notice: '*.min.js' files are EXCLUDED at wp.org. https://wordpress.slack.com/archives/C02RP50LK/p1612471606334000
rem To iclude (for testing?) use --include=*.min.js

wp i18n make-pot ..\.. ..\..\languages\mp-timetable.pot --exclude="media/js/blocks/src"
pause