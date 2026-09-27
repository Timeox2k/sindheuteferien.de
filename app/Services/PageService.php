<?php
/**
 * File: PageService.php
 * Created: May 2025
 * Project: sindheuteferien.de
 */

namespace App\Services;

class PageService
{
    protected string $appName = "SindHeuteFerien.de";
    protected string $title = "";
    protected string $description = "";
    protected string $author = "SindHeuteFerien.de";
    protected string $keywords = "";
    protected ?string $canonical = null;
    protected string $robots = "index, follow";
    protected string $ogType = "website";
    protected ?string $ogImage = null;
    protected array $breadcrumbs = [];
    protected array $schemas = [];

    public function __construct(
        string $appName = ""
    ) {
        if (!empty($appName)) {
            $this->appName = $appName;
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function hasTitle(): bool
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

    public function getCanonical(): ?string
    {
        return $this->canonical;
    }

    public function setCanonical(?string $canonical): void
    {
        $this->canonical = $canonical;
    }

    public function getRobots(): string
    {
        return $this->robots;
    }

    public function setRobots(string $robots): void
    {
        $this->robots = $robots;
    }

    public function getOgType(): string
    {
        return $this->ogType;
    }

    public function setOgType(string $ogType): void
    {
        $this->ogType = $ogType;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(?string $ogImage): void
    {
        $this->ogImage = $ogImage;
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function setBreadcrumbs(array $breadcrumbs): void
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    public function addBreadcrumb(string $name, ?string $url = null): void
    {
        $this->breadcrumbs[] = [
            'name' => $name,
            'url'  => $url,
        ];
    }

    public function getSchemas(): array
    {
        $schemas = $this->schemas;

        if (!empty($this->breadcrumbs)) {
            $itemListElement = [];
            $position = 1;
            foreach ($this->breadcrumbs as $breadcrumb) {
                $item = [
                    '@type'    => 'ListItem',
                    'position' => $position++,
                    'name'     => $breadcrumb['name'],
                ];
                if (!empty($breadcrumb['url'])) {
                    $item['item'] = $breadcrumb['url'];
                }
                $itemListElement[] = $item;
            }

            $schemas[] = [
                '@context'        => 'https://schema.org',
                '@type'           => 'BreadcrumbList',
                'itemListElement' => $itemListElement,
            ];
        }

        return $schemas;
    }

    public function addSchema(array $schema): void
    {
        $this->schemas[] = $schema;
    }

    public function toArray(): array
    {
        return [
            'appName'     => $this->appName,
            'title'       => $this->title,
            'description' => $this->description,
            'author'      => $this->author,
            'keywords'    => $this->keywords,
            'canonical'   => $this->canonical,
            'robots'      => $this->robots,
            'ogType'      => $this->ogType,
            'ogImage'     => $this->ogImage,
            'breadcrumbs' => $this->breadcrumbs,
            'schemas'     => $this->getSchemas(),
        ];
    }
}
