rem https://wp-kama.ru/handbook/cli/wp/i18n
@echo off
wp i18n make-pot ..\.. ..\..\languages\mp-timetable-new.pot --subtract="..\..\languages\mp-timetable.pot"
pause
