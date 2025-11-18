<?php

namespace Tests\Controller;

use Illuminate\Http\Request;
use Tengliyun\Helper\Concerns\ResolvesContextName;

class ResolvesContextNameController
{
    use ResolvesContextName;

    public function resolvesContextName(Request $request): ?string
    {
        return $this->getResolvesContextName();
    }

    public function resolvesContextNameCallback(): ?string
    {
        $this->withResolvesContextNameCallback();

        return $this->getResolvesContextName();
    }

    public function routeNameCallback(): ?string
    {
        $this->withRouteNameCallback();

        return $this->getResolvesContextName();
    }

    public function resolvesContextNameCallbackUsing(Request $request): ?string
    {
        $this->withResolvesContextNameCallbackUsing(function (string $delimiter) use ($request): ?string {
            if ($name = $request->route()->getName()) {
                return implode($delimiter, explode('.', $name));
            }
            return null;
        });

        return $this->getResolvesContextName();
    }
}
