<?php

namespace App\Controllers;

use App\Models\BlogPost;
use App\Models\User;

class IndexController extends BaseController {

    public function getIndex() {
        if(isset($_GET['page'])) {
            $page = $_GET['page'];
            $blogPosts = BlogPost
                ::join('users', 'blog_posts.user_id', '=', 'users.id')
                ->select('blog_posts.img_url', 'blog_posts.id', 'blog_posts.title', 'blog_posts.content','blog_posts.user_id', 'users.username')
                ->orderBy('blog_posts.id', 'desc')
                ->getQuery()
                ->get();
            $totalPages = ceil ($blogPosts->count() / 6);
            $blogInPage = $blogPosts->forPage($page, 6);

            if(!$blogInPage->isEmpty() && $_GET['page'] > 0) {
                $logged = false;
                $currentUser = [];
                if(isset($_SESSION['userId'])) {
                    $logged = true;
                    $currentUser = User::find($_SESSION['userId']);
                }
                foreach ($blogInPage as $blog) {
                    if($blog->content != $this->cutText($blog->content)) {
                        $blog->content = $this->cutText($blog->content);
                        $blog->cutted = true;
                    }
                }
                return $this->render('blog.twig', [
                    'totalPages' => $totalPages,
                    'blogInPage' => $blogInPage,
                    'page' => $page,
                    'logged' => $logged,
                    'currentUser' => $currentUser
                ]);
            } else {
                header('Location: ' . BASE_URL . 'error404');
            }
        } else {
            return $this->render('cover/content.twig');
        }
    }

    public function getBlogpost($param) {
        $blogPost = BlogPost::find($param);
        $user = User::find($blogPost->user_id);
        return $this->render('blogpost.twig', [
            'blogPost' => $blogPost,
            'user' => $user
        ]);
    }
    public function getUser($param) {
        $user = User::find($param);
        return $this->render('user.twig', [
            'user' => $user
        ]);
    }
    public function anyError404() {
        return $this->render('pages/notFound.twig');
    }
    public function cutText ($text) {
        if(strlen($text) > 100){
            $textShown = substr($text, 0, 100) . '...';
        } else {
            $textShown = $text;
        }
        return $textShown;
    }
}