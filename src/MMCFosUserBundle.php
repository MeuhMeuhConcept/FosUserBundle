<?php

namespace MMC\FosUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MMCFosUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
