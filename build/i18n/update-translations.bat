rem https://wp-kama.ru/handbook/cli/wp/i18n
rem By default, minified files should still be excluded, and if you want to explicitly include them, you can do so by adding --include=*.min.js to the command.

@echo off
wp i18n make-pot ..\.. ..\..\languages\mp-timetable.pot --include=*.min.js --exclude="media/js/blocks/src"
pause