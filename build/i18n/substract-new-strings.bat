@echo off

wp i18n make-pot ..\.. ..\..\languages\mp-timetable-new.pot --exclude="media/js/blocks/src" --subtract="..\..\languages\mp-timetable.pot"
pause
