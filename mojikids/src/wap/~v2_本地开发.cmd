@echo off

cd.>public_tag.txt
echo is_dev>>public_tag.txt
yuefe release predev -wLd

:end
