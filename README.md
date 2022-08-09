Logs Cleanup Kata
-----------------

**Given**
- We have an app that stores different types of informations in logs
- These logs can be stored in a database or text file depending on our needs
- For this task please focus on one implementation having in mind that other option is also viable


**Task**
- Make a small program that will be analysing logs and removing records older than given time period
- We should be able to use this program as a composer dependency
- Prepare a test that will demonstrate how your program works


**Remarks**
- This program does not have to be 100% finished or give us all concrete implementations
- All tests should run and pass green


Program Description
===

* Sample data log is located at ./sample/  
* Tests may be run via run_tests.sh script located at root of project  
* binary for using cleaner with files is located at bin/cleaner  

Notice
---
- 2022-08-09 implementation runs only for files  