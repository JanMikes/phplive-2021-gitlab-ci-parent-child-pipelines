<?php

declare(strict_types=1);

namespace PHPLive\Packages\A;

use PHPLive\Packages\B\PackageB;
use PHPLive\Packages\C\PackageC;

class PackageA
{
    public function action(): void
    {
        new PackageB();
        new PackageC();
    }
}