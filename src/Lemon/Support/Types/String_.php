<?php

namespace Lemon\Support\Types;

use Stringable;

class String_ implements Stringable
{
    /** String content */
    public string $content;

    /**
     * Lemon string type
     *
     * @param String $subject
     */
    public function __construct(String $subject)
    {
        $this->content = $subject; 
    }

    /**
     * Returns size of string
     *
     * @return int
     */
    public function size(): int
    {
        return strlen($this->content);
    }

    /**
     * Returns size of string
     *
     * @return int
     */
    public function len(): int
    {
        return strlen($this->content);
    }

    public function __toString()
    {
        return $this->content;
    }

    /**
     * Splits string to array by separator 
     *
     * @param String $separator
     * @return Array_
     */
    public function split(string $separator): Array_
    {
        return new Array_(
            explode($separator, $this->content)
        );
    }

    /**
     * Joins given Array items with string
     *
     * @param Array|Array_ $array
     * @return self
     */
    public function join(array $array): self
    {
        return String_::from(
            implode($this->content, $array)
        );
    }

    /**
     * Converts first character to uppercase
     *
     * @return self
     */
    public function capitalize(): self 
    {
        $this->content = ucfirst($this->content);
        return $this;
    }

    /**
     * Converts first character to lovercase
     *
     * @return self
     */
    public function decapitalize(): self
    {
        $this->content = lcfirst($this->content);
        return $this;
    }

    /**
     * Converts string to lovercase
     *
     * @return self
     */
    public function toLower(): self
    {
        $this->content = strtolower($this->content);
        return $this;
    }

    /**
     * Converts string to uppercase
     *
     * @return self
     */
    public function toUpper(): self
    {
        $this->content = strtoupper($this->content);
        return $this;
    }

    /**
     * Returns whenever string contains given substring
     *
     * @param String|String_ $substring
     * @return bool
     */
    public function contains(String $substring): bool
    {
        return str_contains($this->content, $substring);
    }

    public function has(String $substring): bool
    {
        return $this->contains($substring);
    }

    /**
     * Returns whenever string starts with given substring
     *
     * @param String $substring
     * @return bool
     */
    public function startsWith(String $substring): bool
    {
        return str_starts_with($this->content, $substring); 
    }

    /**
     * Returns whenever string ends with given substring
     *
     * @param String $substring
     * @return bool
     */
    public function endsWith(string $substring): bool
    {
        return str_ends_with($this->content, $substring);
    }

    /**
     * Replaces all occurences of given search string with replace string
     *
     * @param String|String_ $search
     * @param String|String_ $replace
     * @return String_
     */
    public function replace(String $search, String $replace): self 
    {
        $this->content = str_replace($search, $replace, $this->content);
        return $this;
    }

    /**
     * Randomly shuffles string
     *
     * @return String_
     */
    public function shuffle(): self 
    {
        $this->content = str_shuffle($this->content);
        return $this;
    }

    /**
     * Reverses string
     *
     * @return String_
     */
    public function reverse(): self 
    {
        $this->content = strrev($this->content);
        return $this;
    }

    /**
     * Creates new String_ instance
     *
     * @param String $subject
     * @return
     */
    public static function from(String $subject): self 
    {
        return new String_($subject);
    }
}
