<?php

namespace App\Controller;

use App\Exception\Handler\HttpException;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class ArticleController extends AbstractController
{
  
    public function list()
    {
        // throw new \Exception("23432", 800);

        // $response->run();

        $time = $this->request->input("article_time", 0);
        $category_id = $this->request->input("category_id", 0);

        $query = Db::table("article")->where('status', 1);

        if (!empty($time)) {
            $day = date("Y-m-d", strtotime($time));

            $min_time = strtotime($day . " 00:00:00");
            $max_time = strtotime($day . " 23:59:59");

            $query->whereBetween('create_time', [$min_time, $max_time]);
        }

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        $data = $query->orderBy('is_recommend', 'asc')->limit(6)->select()->get();

        return $this->success("",  $data);

    }
}
