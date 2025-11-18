<?php

namespace Tengliyun\Helper\Concerns;

use Closure;
use Illuminate\Support\Str;

/**
 * Trait ResolvesContextName
 *
 * Provides utility methods to resolve a standardized context name
 * based on the current request's controller, action, or route name.
 */
trait ResolvesContextName
{
    /**
     * The resolver callback instance.
     *
     * @var Closure|null
     */
    private static ?Closure $resolver = null;

    /**
     * Get the resolved context name joined by the given delimiter.
     *
     * The name is derived from the current controller and action
     * or route name, then normalized into kebab-case.
     *
     * @param string $delimiter
     *
     * @return string|null
     */
    public static function getResolvesContextNameName(string $delimiter = '.'): ?string
    {
        if (app()->runningInConsole()) {
            return null;
        }

        if (is_null(self::getResolvesContextNameCallback())) {
            self::withResolvesContextNameCallback();
        }

        if ($resolver = self::useResolvesContextNameCallback($delimiter)) {
            return implode($delimiter, array_map(fn(string $value) => Str::kebab($value), explode($delimiter, $resolver)));
        }

        return null;
    }

    /**
     * Set a custom resolver callback for generating the context name.
     *
     * @param callable|null $callback
     *
     * @return void
     */
    public static function withResolvesContextNameCallbackUsing(callable $callback = null): void
    {
        self::$resolver = $callback;
    }

    /**
     * Set the default resolver to extract the controller and action name.
     *
     * @return void
     */
    public static function withResolvesContextNameCallback(): void
    {
        self::$resolver = function (string $delimiter): ?string {
            if (is_string($action = app('request')->route()->getActionName())) {
                $path = str_replace('Controller@', '\\', $action);
                $argv = explode('\\', $path);
                return implode($delimiter, array_slice($argv, 3));
            }
            return null;
        };
    }

    /**
     * Use the current route name as the source for the context name.
     *
     * @return void
     */
    public static function withRouteNameCallback(): void
    {
        self::$resolver = function (string $delimiter): ?string {
            if (is_string($name = app('request')->route()->getName())) {
                return implode($delimiter, explode('.', $name));
            }
            return null;
        };
    }

    /**
     * Resolve the context name using the defined callback.
     *
     * @param string $delimiter
     *
     * @return string|null
     */
    public static function useResolvesContextNameCallback(string $delimiter): ?string
    {
        if (is_callable($resolver = self::getResolvesContextNameCallback())) {
            return call_user_func($resolver, $delimiter) ?: null;
        }

        return null;
    }

    /**
     * Get the current resolver callback instance.
     *
     * @return Closure|null
     */
    public static function getResolvesContextNameCallback(): ?Closure
    {
        return self::$resolver;
    }
}
