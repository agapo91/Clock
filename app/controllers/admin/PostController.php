<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogPost;
use Sirius\Validation\Validator;

class PostController  extends BaseController {
    public function getIndex() {
        $blogPosts = BlogPost::all();
        return $this->render('admin/posts.twig', ['blogPosts' => $blogPosts]);
    }

    public function getCreate() {
        return $this->render('admin/insert-post.twig');
    }

    public function getEdit($id) {
        $blogPost = BlogPost::find($id);
        return $this->render('admin/edit-post.twig', [
            'blogPost' => $blogPost
        ]);
    }

    public function getDelete($id) {
        $blogPost = BlogPost::find($id);
        $blogPost->delete();
        header('Location: ' . BASE_URL . 'admin/posts');
    }
    public function postCreate() {
        $errors = [];
        $result = false;
        $validator = $this->validateBlogPost();
        if($validator->validate($_POST)) {
            $blogPost = new BlogPost();
            $blogPost->title = $_POST['title'];
            $blogPost->content = $_POST['content'];
            $blogPost->img_url = (!$_POST['img'] ? BASE_URL . 'images/default.jpg' : $_POST['img']);
            $blogPost->save();
            $result = true;
        } else {
            $errors =  $validator->getMessages();
        }

        return $this->render('admin/insert-post.twig', [
            'result' => $result,
            'errors' => $errors
        ]);
    }

    public function postEdit($id) {
        $errors = [];
        $result = false;
        $validator = $this->validateBlogPost();
        if($validator->validate(($_POST))) {
            $blogPost = BlogPost::find($id);
            $blogPost->title = $_POST['title'];
            $blogPost->content = $_POST['content'];
            $blogPost->img_url = (!$_POST['img'] ? BASE_URL . 'images/default.jpg' : $_POST['img']);
            $blogPost->save();
            $result = true;
        } else {
            $errors =  $validator->getMessages();
        }

        return $this->render('admin/insert-post.twig', [
            'result' => $result,
            'errors' => $errors
        ]);
    }
    public function validateBlogPost() {
        $validator = new Validator();
        $validator->add('title', 'required');
        $validator->add('title:Title', 'maxlength', 'max=100', '{label} must have less than {max} characters');
        $validator->add('content', 'required');
        $validator->add('content:Content', 'maxlength', 'max=800', '{label} must have less than {max} characters');
        return $validator;
    }
}