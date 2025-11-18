<?php

namespace Tengliyun\Helper\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use ReflectionClass;
use ReflectionException;

trait ReflectionCommentResolved
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
    public function ReflectionCommentResolved(bool $shared = false): Collection
    {
        $data = [
            'module'      => $this->getClassDocCommentByParam('@module'),
            'controller'  => $this->getClassDocCommentByParam('@controller'),
            'description' => $this->getClassDocCommentByParam('@description'),
            'action'      => $this->getMethodDocCommentByParam('@action'),
            'desc'        => $this->getMethodDocCommentByParam('@desc'),
        ];

        if (method_exists($this, 'getResolvesContextName')) {
            $data['url'] = $this->getResolvesContextName();
        }

        $comment = $this->getClassDocCommentByParam('@comment', 'comment');
        $data    = collect($data);

        if ($shared) {
            View::share($comment, $data);
        }

        return $data;
    }

    /**
     * Get a specific parameter's value from the class-level PHPDoc comment.
     *
     * @param string      $parameter Name of the parameter to extract (e.g. "@module")
     * @param string|null $default   Default value to return if the parameter is not found
     *
     * @return string|null
     */
    protected function getClassDocCommentByParam(string $parameter, string $default = null): ?string
    {
        $class = new ReflectionClass($this);

        $docComment = $class->getDocComment();

        if ($docComment === false) {
            return $default;
        }

        if (is_null($result = $this->docCommentParse($docComment, $parameter))) {
            $result = $default;
        }

        return $result;
    }

    /**
     * Get a specific parameter's value from the method-level PHPDoc comment.
     *
     * @param string      $parameter Name of the parameter to extract (e.g. "@action")
     * @param string|null $default   Default value to return if the parameter is not found
     * @param bool|string $method    Method name or true to use the current route's action
     *
     * @return string|null
     */
    protected function getMethodDocCommentByParam(string $parameter, string $default = null, bool|string $method = true): ?string
    {
        if (app()->runningInConsole()) {
            return null;
        }

        $class = new ReflectionClass($this);

        if ($method === true) {
            $method = app('request')->route()->getActionMethod();
        }

        if (method_exists($this, $method) === false) {
            return $default;
        }

        try {
            $method = $class->getMethod($method);
        } catch (ReflectionException $e) {
            return $default;
        }

        $docComment = $method->getDocComment();

        if ($docComment === false) {
            return $default;
        }

        if (is_null($result = $this->docCommentParse($docComment, $parameter))) {
            $result = $default;
        }

        return $result;
    }

    /**
     * Parse the given PHPDoc comment string to extract the value
     * associated with the specified annotation parameter.
     *
     * @param string $docComment The full doc comment string
     * @param string $parameter  The parameter to extract (e.g. "@desc")
     *
     * @return string|null
     */
    protected function docCommentParse(string $docComment, string $parameter): ?string
    {
        foreach (preg_split('/\r\n|\r|\n/', $docComment) as $comment) {
            if ($offset = strpos($comment, $parameter)) {
                return trim(substr($comment, $offset + strlen($parameter)));
            }
        }

        return null;
    }
}
