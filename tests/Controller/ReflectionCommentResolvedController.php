<?php

namespace Tests\Controller;

use Illuminate\Support\Collection;
use Tengliyun\Helper\Concerns\ReflectionCommentResolved;
use Tengliyun\Helper\Concerns\ResolvesContextName;
use Tengliyun\Helper\Contracts\ReflectionCommentWhenResolved;
use Illuminate\Http\Request;

/**
 * @module      moduleResolved
 * @controller  controllerResolved
 * @description descriptionResolved
 */
class ReflectionCommentResolvedController implements ReflectionCommentWhenResolved
{
    use ReflectionCommentResolved;
    use ResolvesContextName;

    /**
     * @action actionResolved
     * @desc   descResolved
     *
     * @param Request $request
     *
     * @return Collection
     */
    public function reflectionCommentResolved(Request $request): Collection
    {
        return $this->ReflectionCommentResolved();
    }
}
