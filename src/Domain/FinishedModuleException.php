<?php


namespace App\Domain;


use DomainException;

class FinishedModuleException extends DomainException
{
    // custom exception checking if user has completed is the last chapter of the module
}