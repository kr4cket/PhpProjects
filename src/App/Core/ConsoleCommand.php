<?php

namespace App\Core;

abstract Class ConsoleCommand
{
    public abstract function execute();
    public abstract function getInfo();
}