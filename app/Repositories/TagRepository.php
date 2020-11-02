<?php


namespace App\Repositories;


use App\Models\Blog\Post;
use App\Models\Blog\Tag as Model;


class TagRepository extends CoreRepository
{
    public function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param Post[] $posts
     * @return \Illuminate\Support\Collection
     */
    public function getTagsToPosts(array $posts){
        $posts_id_list = [];
        foreach ($posts as $post) {
            $posts_id_list[] = $post->id;
        }

        $columns = [
            'tags.id',
            'tags.title',
            'post_to_tag.post_id',
        ];
        $tags = \DB::table('post_to_tag')
            ->select($columns)
            ->join('tags', 'tags.id', '=', 'post_to_tag.tag_id')
            ->whereIn('post_to_tag.post_id', $posts_id_list)
            ->where('tags.active', '=', 1)
            ->get();

        return $tags;
    }


    /**
     * Получить модель для редактирования в админке
     * @param $id
     * @return mixed
     */
    public function getEdit($id){
        return $this->startConditions()->find($id);
    }
}