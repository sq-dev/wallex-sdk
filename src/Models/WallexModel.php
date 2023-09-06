<?php

namespace Wallex\Models;

abstract class WallexModel
{
    abstract public function toArray(): array;
}