@echo off
echo ========================================
echo   CLEARING CAKEPHP CACHE
echo ========================================
echo.

echo Deleting cache files...

del /s /q tmp\cache\views\* 2>nul
del /s /q tmp\cache\persistent\* 2>nul
del /s /q tmp\cache\models\* 2>nul

echo.
echo ========================================
echo   CACHE CLEARED SUCCESSFULLY!
echo ========================================
echo.
echo Press any key to close...
pause >nul

