<?php

namespace App\Console\Commands;

use App\Models\Posts;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ParseRealtBy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:realtBy {startPage?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Realt.by';

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
        libxml_use_internal_errors(true);
        $domDoc = new \DOMDocument();

        $startPage = $this->argument('startPage') != null ? $this->argument('startPage') : 0;
        while (true) {
            $domDoc->loadHTMLFile("https://realt.by/rent/flat-for-day/?page=$startPage");
            $xpath = new \DOMXPath($domDoc);

            $postTitles = $xpath->evaluate("//div[@class='media-body']");
            $postPrice = $xpath->evaluate("//span[@class='price-byr']");
            $postCreatedAt = $xpath->evaluate("//p[@class='fl f11 grey']");
            $postStatus = $xpath->evaluate("//div[@class='bd-item-right-top']");
            $postId = $xpath->evaluate("//div[@class='bd-item-right-top']/p[@class='fr f11 grey']");
            $postImages = $xpath->evaluate('//div[@class="bd-item-left"]/div[@class="bd-item-left-top"]/a/img');
            $postMainContent = $xpath->evaluate("//div[@class='bd-item-right-center']");

            if ($postTitles->length == 0) break;

            $data = [];
            for ($i = 0; $i < $postTitles->length; $i++) {
                $currentPostId = ltrim($postId[$i]->nodeValue, '#');
                if (Posts::where('post_id', '=', $currentPostId)->count() > 0) continue;

                array_push($data, [
                    'title'        => $postTitles[$i]->nodeValue,
                    'main_content' => $domDoc->saveHTML($postMainContent[$i]),
                    'price'        => str_replace(' руб/сутки', '', $postPrice[$i]->nodeValue),
                    'image_path'   => $postImages[$i]->attributes[1]->value,
                    'post_status'  => $domDoc->saveHTML($postStatus[$i]),
                    'post_id'      => $currentPostId,
                    'created_at'   => Carbon::parse(trim($postCreatedAt[$i]->nodeValue, ' '))->format('Y-m-d')
                ]);
            }
            Posts::insert($data);
            echo "Page $startPage was parsed \n";
            $startPage++;
        }

        libxml_clear_errors();
    }
}
