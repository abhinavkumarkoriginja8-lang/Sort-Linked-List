<?php

declare(strict_types=1);

namespace MyLibrary;

use InvalidArgumentException;
use Iterator;

class SortedLinkedList implements Iterator
{
    private ?Node $head = null;
    private ?Node $tail = null;
    private int $size = 0;
    private string $type = '';

    public function __construct(string $type)
    {
        if (!in_array($type, ['int', 'string'], true)) {
            throw new InvalidArgumentException('Type must be "int" or "string".');
        }
        $this->type = $type;
    }

    public function add(int|string $value): void
    {
        $this->assertType($value);
        $node = new Node($value);

        // Empty list
        if ($this->head === null) {
            $this->head = $this->tail = $node;
        } else {
            $current = $this->head;
            while ($current !== null && $current->value < $value) {
                $current = $current->next;
            }
            if ($current === $this->head) {
                $node->next = $this->head;
                $this->head->prev = $node;
                $this->head = $node;
            } elseif ($current === null) {
                $this->tail->next = $node;
                $node->prev = $this->tail;
                $this->tail = $node;
            } else {
                $prev = $current->prev;
                $prev->next = $node;
                $node->prev = $prev;
                $node->next = $current;
                $current->prev = $node;
            }
        }
        $this->size++;
    }

    public function remove(int|string $value): bool
    {
        $this->assertType($value);
        $current = $this->head;

        while ($current !== null && $current->value !== $value) {
            $current = $current->next;
        }

        if ($current === null) {
            return false;
        }

        if ($current->prev !== null) {
            $current->prev->next = $current->next;
        } else {
            $this->head = $current->next;
        }

        if ($current->next !== null) {
            $current->next->prev = $current->prev;
        } else {
            $this->tail = $current->prev;
        }

        $this->size--;
        return true;
    }

    public function size(): int
    {
        return $this->size;
    }

    private function assertType(mixed $value): void
    {
        if (($this->type === 'int' && !is_int($value))
            || ($this->type === 'string' && !is_string($value))
        ) {
            throw new InvalidArgumentException("Value must be of type {$this->type}.");
        }
    }

    private ?Node $iterator = null;

    public function current(): mixed
    {
        return $this->iterator?->value;
    }

    public function key(): int
    {
        return 0;
    }

    public function next(): void
    {
        $this->iterator = $this->iterator?->next;
    }

    public function rewind(): void
    {
        $this->iterator = $this->head;
    }

    public function valid(): bool
    {
        return $this->iterator !== null;
    }
}

class Node
{
    public int|string $value;
    public ?Node $prev = null;
    public ?Node $next = null;

    public function __construct(int|string $value)
    {
        $this->value = $value;
    }
}
