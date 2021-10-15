<?php

namespace App\Console\Commands;


use App\Custom\AlbumCrawler;
use App\Custom\CrawlAlbumsList;
use App\Custom\SongCrawler;
use App\Models\Album;
use App\Models\CrawlerPagination;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SongWrapper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:song-wrapper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * On démarre par une url
         * On cherche les paginations
         *  Save Bdd
         */

        $argFindAblum = (bool)$this->argument('find-album');

        $domain = 'https://www.lacoccinelle.net';


        $path = '/albums/index.html';
        CrawlerPagination::updateOrCreate(['url' => $path], ['to_crawl' => 1]);
        $crawled = [];
        do {
            $paginationUrls = CrawlerPagination::where('to_crawl', 1)->take(1)->get();
            foreach ($paginationUrls as $paginationUrl) {
                if (in_array($paginationUrl->url, $crawled)) {
                    CrawlerPagination::updateOrCreate(['url' => $paginationUrl->url], ['to_crawl' => 0]);
                    continue;
                }
                \Spatie\Crawler\Crawler::create()
                    ->ignoreRobots()
                    ->setCurrentCrawlLimit(1)
                    //            ->setCrawlProfile(new CrawlAlbumsList($url))
                    ->setCrawlObserver(new SongCrawler())
                    ->startCrawling($domain . $paginationUrl->url);
                $crawled[] = $paginationUrl->url;
                CrawlerPagination::updateOrCreate(['url' => $paginationUrl->url], ['to_crawl' => 0]);
            }

            Log::debug('Crawl: ' . $path);
        } while ($paginationUrls->count() > 100);

        $albums = Album::get();

        foreach ($albums as $album) {
            \Spatie\Crawler\Crawler::create()
                ->ignoreRobots()
                ->setCurrentCrawlLimit(1)
                //            ->setCrawlProfile(new CrawlAlbumsList($url))
                ->setCrawlObserver(new AlbumCrawler($album))
                ->startCrawling($domain . $album->url);
        }

        return Command::SUCCESS;
    }

}
