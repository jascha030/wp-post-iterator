<?php

declare(strict_types=1);

namespace Jascha030\WpPostIterator;

use Closure;
use Generator;
use Iterator;
use IteratorAggregate;
use Traversable;
use WP_Query;

/**
 * A memory-friendly IteratorAggregate for WP_Query results.
 *
 * @see \WP_Query
 */
class PostCollection implements IteratorAggregate
{
    private Closure $factory;

    /**
     * Private to enforce the passed being passed instead of any iterable.
     */
    private function __construct(Closure|Traversable|callable $posts)
    {
        if ($posts instanceof Traversable) {
            $posts = $this->wrap($posts);
        }

        $this->factory = $posts instanceof Closure
            ? $posts
            : Closure::fromCallable($posts);
    }

    /**
     * Create instance from any arguments you would pass to a WP_Query.
     */
    public static function fromQueryArgs(array $args, bool $keyByPostId = false): PostCollection
    {
        return new static(fn () => (new PostIteratorAdapter(new \WP_Query($args)))->keyByPostId($keyByPostId));
    }

    /**
     * Create instance from an existing WP_Query instance.
     */
    public static function fromQuery(WP_Query $query, bool $keyByPostId = false): PostCollection
    {
        return new static(
            function () use ($query, $keyByPostId) {
                yield from (new PostIteratorAdapter($query))->keyByPostId($keyByPostId);
            }
        );
    }

    public static function fromLoop(bool $keyByPostId = false): PostCollection
    {
        return new static(static function () use ($keyByPostId) {
            global $post;

            while (have_posts()) {
                the_post();
                yield from (static fn () => $keyByPostId ? yield $post->ID => $post : yield $post)();
            }
        });
    }

    /**
     * Get compiled, or "Lazy" Generator.
     *
     * @see \Generator
     * @see \Iterator
     */
    public function getIterator(): Iterator
    {
        yield from ($this->factory)();
    }

    private function wrap(Traversable $posts): Closure
    {
        return static function () use ($posts): Generator {
            foreach ($posts as $key => $value) {
                yield $key => $value;
            }
        };
    }
}
