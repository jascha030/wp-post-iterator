<?php

declare(strict_types=1);

namespace Jascha030\WpPostIterator;

use Iterator;
use IteratorAggregate;
use WP_Query;

/**
 * A memory-friendly IteratorAggregate for WP_Query results.
 *
 * @see \WP_Query
 */
final class PostCollection implements IteratorAggregate
{
    /**
     * Private to enforce the passed being passed instead of any iterable.
     */
    private function __construct(
        private iterable $posts
    ) {
    }

    /**
     * Create instance from any arguments you would pass to a WP_Query.
     */
    public static function fromQueryArgs(array $args, bool $aggregateIterator = true): PostCollection
    {
        return self::fromQuery(new WP_Query($args), $aggregateIterator);
    }

    /**
     * Create instance from an existing WP_Query instance.
     */
    public static function fromQuery(WP_Query $query, bool $aggregateIterator = true): PostCollection
    {
        return new PostCollection(
            $aggregateIterator
                ? new PostIteratorAdapter($query)
                : $query->get_posts()
        );
    }

    /**
     * Get compiled Generator, acting as Iterator.
     *
     * @see \Generator
     * @see \Iterator
     */
    public function getIterator(): Iterator
    {
        return (function () {
            foreach ($this->posts as $key => $value) {
                yield $key => $value;
            }
        })();
    }
}
