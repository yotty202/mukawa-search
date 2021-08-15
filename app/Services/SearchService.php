<?php

namespace app\Services;

use GuzzleHttp\Client;
use DOMWrap\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchService 
{
    static function guzzle()
    {
        # Memo: 年齢確認画面が出た場合は、同じURLのbodyにForm Data: restricted_age_agree=1を入れてPOST
        $response = Http::get('https://mukawa-spirit.com/?mode=srh&sort=n&page=1');
        $doc = new Document();
        $node = $doc->html($response->body());
        // Log::info('res', [$node]);
        $item_list = $node->find('#listInfo > .item > li > a');
        // Log::info($item_list);
        foreach ($item_list as $item) {
            $href_list[] = $item->getAttribute('href');
        }
        Log::info($href_list);
    }

    
    static function guzzl()
    {
        \Log::debug('guzzle');

        $base_url = 'https://mukawa-spirit.com/';
        $client = new Client([
            'base/url' => $base_url
        ]);
        // ウイスキーカテゴリのトップページにリクエストを投げる
        // $response = $client->request('GET', 'https://mukawa-spirit.com/', ['verify' => false, 
        //                                 'query' => ['mode' => 'f7']]);
        $response = $client->request('GET', 'https://mukawa-spirit.com/?mode=f7', ['verify' => false]);
        $doc = new Document;
        $node = $doc->html($response->getBody()->getContents());
        Log::info($node);
        $datas = $node->find('#lNav > li > a');
        $links = [];
        // ウイスキーのカテゴリのURLを取得
        foreach ($datas as $data) {
            array_push($links, $data->getAttribute('href'));
        }
        // dd($links);
        // dd($data->getAttribute('href'));

        // ウイスキーの各カテゴリトップにリクエストを投げる
        $response = $client->request('GET', $links[0], ['verify' => false]);
        // メーカーのURLを取得
        $node = $doc->html($response->getBody()->getContents());
        $datas = $node->find('#lNav > li > a');
        $links = [];
        foreach ($datas as $data) {
            array_push($links, $data->getAttribute('href'));
        }
        // dd($links);
        // 商品ページにリクエストを投げる
        $req_url = str_replace($base_url, $base_url . $links[0], $base_url);
        $response = $client->request('GET', $req_url, ['verify' => false]);
        $node = $doc->html($response->getBody()->getContents());
        dd($req_url);
        // 商品の情報を取得する
        // 年齢確認ページ

        // 商品の情報をDBに保存
    }  
}