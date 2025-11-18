<?php

namespace Tests\Controller;

use Tengliyun\Helper\Concerns\ResolvesContextName;
use Illuminate\Http\Request;

class ResolvesContextNameController
{
    use ResolvesContextName;

    public function resolvesContextName(Request $request): ?string
    {
        return $this->getResolvesContextName();
    }
}
