<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \App\Models\Blog\PostToTag;
use Faker\Generator as Faker;

$factory->define(PostToTag::class, function (Faker $faker) {
    $GetDataPostToTag = new GetDataPostToTag();
    $post_and_tag = $GetDataPostToTag->getPostIdAndTagId();
    return [
        'post_id' => $post_and_tag['post_id'],
        'tag_id' => $post_and_tag['tag_id'],
    ];
});


// Класс позволяет заполнять таблицу связей поста с тегами без повторений
class GetDataPostToTag{
    private static $arrDataPT = [];

    public function getPostIdAndTagId(){
        $post_id = \App\Models\Blog\Post::orderByRaw("RAND()")->first()->id;
        $tag_id = \App\Models\Blog\Tag::orderByRaw("RAND()")->first()->id;

        $resCheckDataPT = $this->checkDataPT(self::$arrDataPT, $post_id, $tag_id);
        if($resCheckDataPT){
            return $this->getPostIdAndTagId();
        }

        self::$arrDataPT[] = [$post_id, $tag_id];


        return [
            'post_id' => $post_id,
            'tag_id' => $tag_id,
        ];
    }

    private function checkDataPT($arrDataPT, $post_id, $tag_id){
        foreach ($arrDataPT as $item){
            if($item[0] == $post_id && $item[1] == $tag_id){
                return true;
            }
        }

        return false;
    }
}
