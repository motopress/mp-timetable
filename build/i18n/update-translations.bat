@echo off
rem https://wp-kama.ru/handbook/cli/wp/i18n
rem *.min.js' are EXCLUDED. https://wordpress.slack.com/archives/C02RP50LK/p1612471606334000

wp i18n make-pot ..\.. ..\..\languages\mp-timetable.pot --include=*.min.js --exclude="media/js/blocks/src"
pause