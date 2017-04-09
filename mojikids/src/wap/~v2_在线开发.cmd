@echo off

cd.>public_tag.txt
echo is_predev>>public_tag.txt
yuefe release predev -wd

:end
