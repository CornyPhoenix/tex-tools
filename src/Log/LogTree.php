<?php

/*
 * This file is part of the TeX Tools for PHP component.
 *
 * (c) Konstantin S. M. Möllers <ksm.moellers@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CornyPhoenix\Tex\Log;

/**
 * @package CornyPhoenix\Tex\Log
 * @author moellers
 */
class LogTree implements \Iterator
{

    /**
     * @var LogText
     */
    private $text;

    /**
     * @var LogBlock[]
     */
    private $blocks;

    /**
     * @var string
     */
    private $content;

    /**
     * LogTree constructor.
     * @param LogText $text
     */
    public function __construct(LogText $text)
    {
        $this->setText($text);
    }

    /**
     * @param LogText $text
     */
    public function setText(LogText $text)
    {
        $this->text = $text;
        $this->content = $text->__toString();

        $this->parseTree();
    }

    /**
     * @param LogBlock $block
     * @return string
     */
    public function getBlockContent(LogBlock $block)
    {
        return substr($this->content, $block->start, $block->end - $block->start + 1);
    }

    /**
     * @param int $id
     * @return LogBlock
     */
    public function findBlock($id)
    {
        return $this->blocks[$id];
    }

    protected function parseTree()
    {
        $text = $this->content;

        $nesting = -1;
        $blockId = -1;

        /** @var LogBlock[] $blocks */
        $blocks = [];
        $blockStack = new \SplStack();
        $blockStack->push(null);
        for ($i = 0; $i < strlen($text); $i++) {
            if ('(' === $text[$i]) {
                // Increase nesting.
                $nesting++;

                // Save as new block with nesting start pos.
                $blockId++;
                $block = new LogBlock();
                $block->id = $blockId;
                $block->nesting = $nesting;
                $block->start = $i + 1;
                $block->parent = $blockStack->top();
                $blocks[] = $block;

                // Push last ID on stack.
                $blockStack->push($blockId);

                continue;
            }

            if (')' === $text[$i]) {
                $block = $blocks[$blockStack->pop()];
                $block->end = $i - 1;

                // Decrease nesting.
                $nesting--;
            }
        }

        $this->blocks = $this->validateBlocks($blocks);
    }

    /**
     * @return LogText
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param LogBlock[] $blocks
     * @return LogBlock[]
     */
    private function validateBlocks(array $blocks)
    {
        $id = 0;
        $map = [];
        $result = [];
        foreach ($blocks as $block) {
            $content = $this->getBlockContent($block);
            if (preg_match('#^(\.?/[\w/\.-]+)\s?#s', $content, $matches)) {
                list($all, $filename) = $matches;
                $map[$block->id] = $id;
                $block->id = $id;
                $block->parent = $map[$block->parent];
                $block->filename = $filename;
                $block->start += min(strlen($all), $block->end);

                $id++;
                $result[] = $block;
            }
        }

        return $result;
    }

    /**
     * @return LogElement
     */
    public function current()
    {
        return new LogElement($this, current($this->blocks));
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->blocks);
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->current()->getFilename();
    }

    /**
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return false !== current($this->blocks);
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->blocks);
    }
}
