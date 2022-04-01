<?php

declare(strict_types=1);

namespace Jascha030\WpPostIterator;

use WP_Query;

/**
 * Use if you want to implement the functionality in an existing class,
 * without forcing inheritance from WP_Query on your class.
 */
trait PostIteratorTrait
{
    /**
     * Provide a WP_Query object to defer our calls to.
     */
    abstract private function getQuery(): WP_Query;

    /**
     * Tells the `key` method whether we should return the post ID when called.
     * If so, this doesn't necessarily mean the posts would be ordered ID.
     */
    abstract private function keyedByPostIds(): bool;

    public function current(): ?\WP_Post
    {
        return $this->getQuery()->post = $this->getQuery()->posts[$this->getQuery()->current_post + 1];
    }

    public function next(): int|\WP_Post|null
    {
        return $this->getQuery()->next_post();
    }

    public function key(): ?int
    {
        if (0 === $this->getQuery()->post_count) {
            return null;
        }

        return $this->keyedByPostIds()
            ? $this->getQuery()->current_post
            : $this->getQuery()->post->ID;
    }

    public function valid(): bool
    {
        return $this->getQuery()->have_posts();
    }

    public function rewind(): void
    {
        $this->getQuery()->rewind_posts();
    }
}
