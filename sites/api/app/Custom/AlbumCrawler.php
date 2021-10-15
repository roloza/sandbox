<?php

namespace App\Custom;


use App\Models\Album;
use App\Models\Artist;
use App\Models\CrawlerPagination;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psy\Util\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class AlbumCrawler extends CrawlObserver
{

    private $album;

    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     */
    public function willCrawl(UriInterface $url): void
    {
//        Log::debug('willCrawl: ' . $url);
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        if ($response->getStatusCode() !== 200) {
            Log::error('Url non traitée. Mauvais statut : ' . $response->getStatusCode());
            return;
        }
        if (strpos(current($response->getHeader('content-type')), 'text/html') === false) {
            Log::error('Url non traitée. Mauvais type : ' . strpos(current($response->getHeader('content-type'))));
            return;
        }
        if ($response->getBody()->getSize() <= 1000) {
            Log::error('Url non traitée. Taille trop grande : ' . $response->getBody()->getSize());
            return;
        }

        $crawler = new \Symfony\Component\DomCrawler\Crawler((string)$response->getBody());
        $artist = $crawler->filter('#subzone2 .artist  a')->each(function (\Symfony\Component\DomCrawler\Crawler $node) {
            try {
                return [
                    'artist' => $node->text(),
                    'artist_url' => $node->attr('href'),
                ];
            } catch (\Exception $e) {
                Log::error('albums: ' . $e->getMessage());
            }
        });
        $artist = current($artist);
        $released = $crawler->filter('#subzone2 .released')->each(function (\Symfony\Component\DomCrawler\Crawler $node) {
            try {
                return trim(str_replace('Date de sortie :', '', $node->text()));
            } catch (\Exception $e) {
                Log::error('albums: ' . $e->getMessage());
            }
        });

        $res = Artist::updateOrCreate([
            'slug' => \Illuminate\Support\Str::slug($artist['artist']),
        ], [
            'name' => $artist['artist'],
            'url' => $artist['artist_url']
        ]);

        $this->album->update([
           'released' => $released,
           'artiste_name' => $artist['name'],
        ]);

        Log::debug('crawled: ' . $url);
    }

    /**
     * Called when the crawler had a problem crawling the given url.
     *
     * @param \Psr\Http\Message\UriInterface $url
     * @param \GuzzleHttp\Exception\RequestException $requestException
     * @param \Psr\Http\Message\UriInterface|null $foundOnUrl
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        Log::error($requestException->getCode() . ' - ' . (string)$url);
        Log::error($requestException->getMessage());
        // TODO: Implement crawlFailed() method.
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): void
    {
        Log::debug('finishedCrawling');
    }
}
