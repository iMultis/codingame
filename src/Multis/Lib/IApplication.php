<?php

namespace Multis\Lib;

interface IApplication
{
    /**
     * This method is called when applications starts
     *
     * @return void
     */
    public function execute(): void;
}
