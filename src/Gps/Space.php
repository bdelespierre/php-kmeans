<?php

namespace Kmeans\Gps;

use Kmeans\Space as BaseSpace;

class Space extends BaseSpace
{
    public function __construct()
    {
        parent::__construct(2);
    }
}
