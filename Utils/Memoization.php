<?php
namespace Plugin\FlashSale\Utils;

class Memoization
{
    /**
     * @var array
     */
    static $cachedById = [];

    /**
     * @var array
     */
    static $cachedByTag = [];

    /**
     * Check exist cache by id
     *
     * @param null $id
     * @return bool
     */
    public function has($id = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $id = $trace[1]['class'] . '::' .  $trace[1]['function'] . '::' .  $id;

        return array_key_exists($id, self::$cachedById);
    }

    /**
     * Get cache by id
     *
     * @param null $id
     * @return mixed
     */
    public function get($id = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $id = $trace[1]['class'] . '::' .  $trace[1]['function'] . '::' .  $id;

        return self::$cachedById[$id];
    }

    /**
     * Set cache
     *
     * @param $value
     * @param null $id
     * @param null $tag
     *
     * @return mixed
     */
    public function set($value, $id = null, $tag = null)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $id = $trace[1]['class'] . '::' .  $trace[1]['function'] . '::' .  $id;

        self::$cachedById[$id] = $value;
        if (isset($tag)) {
            self::$cachedByTag[$tag][] = $id;
        }

        return $value;
    }

    /**
     * Invalidate by id
     *
     * @param $id
     */
    public function invalidateById($id)
    {
        unset(self::$cachedById[$id]);
    }

    /**
     * Invalidate by tag
     *
     * @param $tag
     */
    public function invalidateByTag($tag)
    {
        if (!isset(self::$cachedByTag[$tag])) {
            return;
        }

        foreach (self::$cachedByTag[$tag] as $id) {
            unset(self::$cachedById[$id]);
        }
    }
}
