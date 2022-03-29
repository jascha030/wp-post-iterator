<?php

declare(strict_types=1);

namespace Jascha030\WpPostIterator;

/**
 * Simple implementation, extends WP_Query and uses its methods to implement the Iterator interface.
 */
class PostIterator extends \WP_Query implements \Iterator
{
    private bool $keyByPostId;

    public function __construct($query = '')
    {
        $this->keyByPostId = false;

        parent::__construct($query);
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

    /**
     * {@inheritDoc}
     */
    public function current(): ?\WP_Post
    {
        if (! isset($this->post)) {
            $this->the_post();
        }

        return $this->post;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return $this->next_post();
    }

    /**
     * {@inheritDoc}
     */
    public function key(): ?int
    {
        if (0 === $this->post_count) {
            return null;
        }

        return $this->keyByPostId
            ? $this->current_post
            : $this->post->ID;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool
    {
        return $this->have_posts();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind(): void
    {
        $this->rewind_posts();
    }
}
