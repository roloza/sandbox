<?php

namespace App\Custom;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class CrawlAlbumsList extends CrawlProfile
{
    protected mixed $baseUrl;

    public function __construct($baseUrl)
    {
        if (! $baseUrl instanceof UriInterface) {
            $baseUrl = new Uri($baseUrl);
        }

        $this->baseUrl = $baseUrl;
    }

    public function shouldCrawl(UriInterface $url): bool
    {
//        return Str::contains($this->baseUrl->getPath(), '/albums');
        return str_contains($this->baseUrl->getPath(), 'albums');
        return $this->baseUrl->getHost() === $url->getHost();
    }
}
