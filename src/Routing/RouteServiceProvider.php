<?php

namespace Netflex\Routing;

use App;

use Netflex\Builder\Page;
use Netflex\Http\PageController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  /**
   * This namespace is applied to your controller routes.
   *
   * In addition, it is set as the URL generator's root namespace.
   *
   * @var string
   */
  protected $namespace = 'App\Http\Controllers';

  /**
   * The path to the "home" route for your application.
   *
   * @var string
   */
  public const HOME = '/';

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map()
  {
    $this->mapPages();
    $this->mapApiRoutes();
    $this->mapWebRoutes();
    $this->mapRedirects();
  }

  /**
   * Registers routes for Pages defined in Netflex
   *
   * @return void
   */
  protected function mapPages()
  {
    Route::middleware(['web'])
      ->group(function () {
        foreach (Page::all() as $page) {
          if ($controller = $page->controller) {

            if ($controller = $page->controller) {
              $route = Route::any($page->url, function (Request $request) use ($page, $controller) {
                return $controller->index($request);
              });

              $route->data('page', $page);
              $route->name($page->name);

              $route->middleware('page');

              if (!$page->public) {
                $route->middleware('group_auth');
              }
            }
          }
        };
      });
  }

  /**
   * Registers redirects defined in Netflex
   *
   * @return void
   */
  protected function mapRedirects()
  {
    //
  }

  /**
   * Define the "web" routes for the application.
   *
   * These routes all receive session state, CSRF protection, etc.
   *
   * @return void
   */
  protected function mapWebRoutes()
  {
    Route::middleware('web')
      ->namespace($this->namespace)
      ->group(base_path('routes/web.php'));
  }

  /**
   * Define the "api" routes for the application.
   *
   * These routes are typically stateless.
   *
   * @return void
   */
  protected function mapApiRoutes()
  {
    Route::prefix('api')
      ->middleware('api')
      ->namespace($this->namespace)
      ->group(base_path('routes/api.php'));
  }
}
