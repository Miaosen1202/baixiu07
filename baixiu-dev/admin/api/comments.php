<?php

// 接收客户端的 AJAX 请求 返回评论数据

// 载入封装的所有的函数
require_once '../../functions.php';

// 取得客户端传递过来的分页页码
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);

$length = 30;
// 根据页码计算越过多少条
$offset = ($page - 1) * $length;

$sql = sprintf('select
  comments.*,
  posts.title as post_title
from comments
inner join posts on comments.post_id = posts.id
order by comments.created desc
limit %d, %d;', $offset, $length);
// Consolas  Fira Code  Source Code Pro

// 查询所有的评论数据
$comments = xiu_fetch_all($sql);

// 先查询到所有的数据的数量
$total_count = xiu_fetch_one('select count(1) as count
from comments
inner join posts on comments.post_id = posts.id')['count'];
$total_pages = ceil($total_count / $length);
// 虽然返回的数据类型是 float 但是数字一定是一个整数

// 因为网络之间传输的只能是字符串
// 所以我们先将数据转换成字符串（序列化）
$json = json_encode(array(
  'total_pages' => $total_pages,
  'comments' => $comments
));

// 设置响应的响应体类型为 JSON
header('Content-Type: application/json');

// 响应给客户端
echo $json;
