<?php

namespace Tengliyun\Helper\Contracts;

use Illuminate\Support\Collection;

interface ReflectionCommentWhenResolved
{
    /**
     * Resolve and share contextual metadata extracted from the controller
     * and action doc comments to the view layer.
     *
     * Retrieves information such as module, controller, action, and
     * description from the class and method PHPDoc comments.
     *
     * @param bool $shared
     *
     * @return Collection
     */
    public function ReflectionCommentResolved(bool $shared = false): Collection;
}
