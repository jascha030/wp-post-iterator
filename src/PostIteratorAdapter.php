<?php

declare(strict_types=1);

namespace Jascha030\WpPostIterator;

use WP_Query;

class PostIteratorAdapter implements \Iterator
{
    use PostIteratorTrait;

    private \WP_Query $query;

    private bool $keyByPostId;

    public function __construct(WP_Query $query)
    {
        $this->keyByPostId = false;

        $this->query = $query;
    }

    private function getQuery(): WP_Query
    {
        return $this->query;
    }

    private function keyedByPostIds(): bool
    {
        return $this->keyByPostId;
    }

    /**
     * Tells the `key` method whether we should return the post ID when called.
     * If so, this doesn't necessarily mean the posts would be ordered ID.
     */
    final public function keyByPostId(bool $enabled = true): \Iterator
    {
        $this->keyByPostId = $enabled;

        return $this;
    }
}
