<?php

use MyLibrary\SortedLinkedList;
use PHPUnit\Framework\TestCase;

final class SortedLinkedListTest extends TestCase
{
    public function testAddIntValues(): void
    {
        $list = new SortedLinkedList('int');
        $list->add(5);
        $list->add(2);
        $list->add(10);

        $values = iterator_to_array($list);
        $this->assertEquals([2, 5, 10], $values);
    }

    public function testAddStringValues(): void
    {
        $list = new SortedLinkedList('string');
        $list->add('b');
        $list->add('a');
        $list->add('c');

        $values = iterator_to_array($list);
        $this->assertEquals(['a', 'b', 'c'], $values);
    }

    public function testRemoveValue(): void
    {
        $list = new SortedLinkedList('int');
        $list->add(1);
        $list->add(3);
        $list->add(2);

        $this->assertTrue($list->remove(2));
        $this->assertFalse($list->remove(4));

        $values = iterator_to_array($list);
        $this->assertEquals([1, 3], $values);
    }

    public function testTypeEnforcement(): void
    {
        $list = new SortedLinkedList('int');
        $this->expectException(InvalidArgumentException::class);
        $list->add('string'); // invalid
    }
}
