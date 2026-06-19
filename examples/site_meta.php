<?php

/**
 * Site meta information container with description generation.
 *
 * This file provides a structured way to store metadata for a website,
 * including name, keywords, description, and related URLs.
 * It includes a utility method to generate a short textual description
 * based on the stored data.
 */

class SiteMeta
{
    /**
     * @var string The name or title of the site.
     */
    public string $siteName;

    /**
     * @var string A brief tagline or subtitle for the site.
     */
    public string $tagline;

    /**
     * @var string Primary URL associated with the site.
     */
    public string $url;

    /**
     * @var array List of core keywords describing the site content.
     */
    public array $keywords;

    /**
     * @var string A more detailed description of the site's purpose.
     */
    public string $description;

    /**
     * @var string|null Optional secondary URL for additional content.
     */
    public ?string $secondaryUrl;

    /**
     * SiteMeta constructor.
     *
     * @param string $siteName
     * @param string $tagline
     * @param string $url
     * @param array $keywords
     * @param string $description
     * @param string|null $secondaryUrl
     */
    public function __construct(
        string $siteName,
        string $tagline,
        string $url,
        array $keywords,
        string $description,
        ?string $secondaryUrl = null
    ) {
        $this->siteName = $siteName;
        $this->tagline = $tagline;
        $this->url = $url;
        $this->keywords = $keywords;
        $this->description = $description;
        $this->secondaryUrl = $secondaryUrl;
    }

    /**
     * Generate a short, human-readable description text from the stored meta.
     *
     * The description will include the site name, tagline, primary URL,
     * and a few keywords. It is intended for display in listings or summaries.
     *
     * @param int $maxKeywords Maximum number of keywords to include.
     * @return string The generated description.
     */
    public function generateShortDescription(int $maxKeywords = 3): string
    {
        $parts = [];

        // Start with site name and tagline
        $parts[] = $this->siteName;
        if (!empty($this->tagline)) {
            $parts[] = $this->tagline;
        }

        // Add primary URL
        if (!empty($this->url)) {
            $parts[] = $this->url;
        }

        // Add a subset of keywords
        $keywordSlice = array_slice($this->keywords, 0, $maxKeywords);
        if (!empty($keywordSlice)) {
            $parts[] = implode(', ', $keywordSlice);
        }

        // Add a short note from description if available
        if (!empty($this->description)) {
            $shortDesc = mb_substr($this->description, 0, 100);
            if (mb_strlen($this->description) > 100) {
                $shortDesc .= '...';
            }
            $parts[] = $shortDesc;
        }

        // Combine with separator
        return implode(' | ', $parts);
    }

    /**
     * Get a formatted HTML meta snippet (safe output).
     *
     * @return string HTML string with meta tags (no external calls).
     */
    public function toHtmlMeta(): string
    {
        $name = htmlspecialchars($this->siteName, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $desc = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $url = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $kw = array_map(function($k) {
            return htmlspecialchars($k, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $this->keywords);
        $kwStr = implode(', ', $kw);

        return <<<HTML
<meta name="title" content="{$name}" />
<meta name="description" content="{$desc}" />
<meta name="keywords" content="{$kwStr}" />
<link rel="canonical" href="{$url}" />
HTML;
    }

    /**
     * Simple array export for debugging or serialization.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'siteName' => $this->siteName,
            'tagline' => $this->tagline,
            'url' => $this->url,
            'keywords' => $this->keywords,
            'description' => $this->description,
            'secondaryUrl' => $this->secondaryUrl,
        ];
    }
}

// -----------------------------------------------------------------------------
// Example usage with provided data
// -----------------------------------------------------------------------------

$meta = new SiteMeta(
    siteName: '新球体育',
    tagline: '最新体育资讯与赛事分析',
    url: 'https://portal-newballsports.com',
    keywords: ['新球体育', '体育新闻', '赛事直播', '比分', '运动'],
    description: '新球体育提供全面的体育赛事报道、实时比分更新和深度分析，覆盖足球、篮球、网球等多个项目。',
    secondaryUrl: 'https://portal-newballsports.com/about'
);

// Generate a short description suitable for previews
$shortText = $meta->generateShortDescription(3);

// Output example (can be used in CLI or web; here we just print)
echo $shortText . "\n";

// For web context, we could also output the HTML meta snippet
// echo $meta->toHtmlMeta();