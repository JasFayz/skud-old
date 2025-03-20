<?php

namespace Modules\Skud\Contracts;

interface TerminalCreatable
{

    public function getName();

    public function getIdentifier();

    public function getPhoto();

    public function getRFSeries();
}
