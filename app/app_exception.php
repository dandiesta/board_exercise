<?php
class AppException extends Exception{}
class RecordNotFoundException extends ValidationException{}
class ValidationException extends AppException{}

