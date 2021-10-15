<?php

namespace App\Custom;


use App\Models\Album;
use App\Models\CrawlerPagination;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Psy\Util\Str;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class SongCrawler extends CrawlObserver
{


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
        $crawler->filter('#subzone2 .homeList .list .title a')->each(function (\Symfony\Component\DomCrawler\Crawler $node) {
            try {
                Album::updateOrCreate([
                    'slug' => \Illuminate\Support\Str::slug($node->text())
                ], [
                    'name' => $node->text(),
                    'url' => $node->attr('href')
                ]);
                return [
                    'href' => $node->attr('href'),
                    'title' => $node->text()
                ];
            } catch (\Exception $e) {
                Log::error('albums: ' . $e->getMessage());
            }
        });

        $crawler->filter('#subzone2 .pages a')->each(function (\Symfony\Component\DomCrawler\Crawler $node) {
            try {
                CrawlerPagination::updateOrCreate(['url' => $node->attr('href')], ['to_crawl' => true]);
            } catch (\Exception $e) {
                Log::error('paginations: ' . $e->getMessage());
            }
        });
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
