<?php

 namespace App;

 use Nette;
 use Nette\Application\Routers\RouteList;
 use Nette\Application\Routers\Route;

 class RouterFactory {

     /**
      *
      * @return Nette\Application\IRouter
      */
     public static function createRouter() {
         $router = new RouteList();

         $router[] = $commonRouter = new RouteList('Common');
         $commonRouter[] = new Route('common/register/activate/<id>','Register:activate');
         $commonRouter[] = new Route('common/<presenter>/<action>', 'Common:default');

         $router[] = $adminRouter = new RouteList('Admin');
         $adminRouter[] = new Route('admin/<presenter>/<action>[/<id>]', 'Dashboard:default');
         $adminRouter[] = new Route('admin/<presenter>/<action>', 'Dashboard:default');
         

         $router[] = $blogRouter = new RouteList('Blog');
         $blogRouter[] = new Route('blog/<action>[/<id>]', 'Blog:default');

         $router[] = $profileRouter = new RouteList('Profile');
         $profileRouter[] = new Route('profile/<action>[/<id>]', 'Profile:default');

         $router[] = $forumRouter = new RouteList('Forum');
         $forumRouter[] = new Route('forum/do-plus/[</id>]','Forum:doPlus');
         $forumRouter[] = new Route('forum/do-minus/[</id>]','Forum:doMinus');
         $forumRouter[] = new Route('forum/eval-plus/[</id>]','Forum:evalPlus');
         $forumRouter[] = new Route('forum/eval-minus/[</id>]','Forum:evalMinus');
         $forumRouter[] = new Route('forum/search','Forum:search');
         $forumRouter[] = new Route('forum/<presenter>/<action>[/<id>]', 'Forum:default');

         $router[] = $frontRouter = new RouteList('Front');
         $frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

         return $router;
     }

 }
 