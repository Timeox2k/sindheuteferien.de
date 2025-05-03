<?php
/**
 * File: PageService.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Services;

class PageService
{
    protected string $appName = "";
    protected string $title = "";
    protected string $description = "";
    protected string $author = "";
    protected string $keywords = "";

    public function __construct(
        string $appName = ""
    )
    {
        $this->appName = $appName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function hasTitle() : bool
    {
        return !empty($this->title);
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function setAppName(string $appName): void
    {
        $this->appName = $appName;
    }

    public function toArray(): array
    {
        return [
            'appName' => $this->appName,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'keywords' => $this->keywords,
        ];
    }
}
