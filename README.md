# Asynchronous Binary Tree Loading Example

## Overview
This project demonstrates how to perform asynchronous operations in PHP using Swoole to handle binary trees data insertion into a PostgreSQL database. It showcases the power of coroutines provided by Swoole for efficient non-blocking database interactions.

## Features
- **Asynchronous Database Connection**: Connects to PostgreSQL using Swoole's coroutine PostgreSQL client to perform non-blocking database operations.
- **Binary Tree Operations**: Includes functionality to create binary trees and serialize them into JSON format before insertion.
- **Concurrent Data Insertion**: Uses Swoole coroutines to insert multiple binary trees into the database concurrently, showcasing the efficiency of asynchronous operations.

## Requirements
- PHP 8.0 or higher
- PostgreSQL database
- Swoole PHP extension
