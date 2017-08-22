# DatabaseDesign_LibraryManagementSys
Library Management System Design Document

1. Introduction
This SQL programming project involves the creation of a database host application that interfaces with a backend SQL database to implement a DBMS for a library circulation desk. Users of the system are understood to be librarians (not book borrowers).
2. Design Schema
Check the requirement file
3. Technology Used
The project is developed by PHP, using a framework called CodeIgniter as backend and HTML+CSS+JS to compose of frontend. Using Mysql as database.
4. Function
4.1 GUI
I design the interface by using HTML,CSS,JS easily and friendly. There are four main parts of interface including User Management, Book Management, BookLoan Management, Fines Management.
4.2 BOOK SEARCH ADD UPDATE AND DELETE
We can search by substring using Isbn, Title , Name or any combination of they.And will display the Isbn, Title, Name and Availability.
4.3 BOOK CHECK_OUT ,CHECK_IN
We can check out the book by input us card_id and the Isbn. If anyone have borrowed 3 books, it wont allow to borrow anymore. If anyone who keeps a dued book, he or she
won’t be allow to borrow any book. If this book has already been checked out ,this book could not be checked out. 4.4 BOOK CHECK_in
We can check in the book with input Isbn and Card_id, we will judge whether the book is due or not.
4.5 CREATE AND DISPLAY BORROWER
We can create a new borrower by input his or her ssn, fname, lname, arddress, phone.There will be a validation to control the input.
4.6 PAY FINES
I add some functions in pay fine. We can first input card_id to find out the fine
records of the person. There is a filter that can choose to show the fines that are
paid or not. Then we can pay for the fine, if succeed , it will display. Then we need to
click update file amount to update the fines table.
4.7Additional Features:
1. Pagination for book, there are thousands of books and borrowers here, pagination
could make the management easier.
2. borrower cart, the manager do not need to check out all books for a user one by
one, he/she could check out all by once and remove some books from the cart.
3. All CRUD functions for book management.
4.Designed a relational model for a simplified University Library database system, normalized with BCNF/4NF


