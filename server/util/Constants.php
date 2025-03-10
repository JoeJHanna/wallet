<?php

namespace util;

const HEADERS = "Content-Type: application/json; charset=utf-8";
const STATUS_SUCCESS = 200;
const STATUS_BAD_REQUEST = 400;
const STATUS_UNAUTHORIZED = 401;
const STATUS_METHOD_NOT_ALLOWED = 405;
const DEFAULT_SUCCESS_MESSAGE = "Success!";
const DEFAULT_ERROR_MESSAGE = "An error has occurred!";
const USER_NOT_FOUND_MESSAGE = "User or Password incorrect!";
const USER_ALREADY_EXISTS = "User already exists!";
const MYSQL_ERROR_DUPLICATE_ENTRY = 1062;
const REGEX_PASSWORD = '/"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"/u';