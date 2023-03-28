<?php

namespace App\Providers;

use App\Http\Controllers\CommonController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->checkVersionNumbering();

        if ((new CommonController())->GetSetting('app_mode') != "PRODUCTION") {
            $this->recordsLogs();
        }

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('web', 'adminauth')
                ->prefix('admin')
                ->as('admin.')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function recordsLogs()
    {
        if (array_key_exists('REQUEST_URI', $_SERVER) && str_contains($_SERVER['REQUEST_URI'], 'api')) {
            $date = date('Y-m-d', strtotime("now"));
            $txtName = $date . ".txt";
            $txt = "";
            $txt .= "......................................" . "\n";
            if (array_key_exists('REQUEST_URI', $_SERVER)) {
                $txt .= "ENTRY POINT: " . $_SERVER['REQUEST_URI'] . "\n";
            }

            if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) {
                $txt .= "HTTP_AUTHORIZATION: " . $_SERVER['HTTP_AUTHORIZATION'] . "\n";
            }
            $txt .= "DATA: " . json_encode($_REQUEST);
            $txt .= "\n";
            $txt .= "RAW: " . file_get_contents('php://input');
            $txt .= "\n";
            if (array_key_exists('audio', $_FILES)) {
                $txt .= "audio: " . $_FILES['audio']['name'];
                $txt .= "\n";
            }
            if (array_key_exists('multiple_images', $_FILES)) {
                foreach ($_FILES['multiple_images']['name'] as $name) {
                    $txt .= "multiple_images: " . $name;
                    $txt .= "\n";
                }
            }
            $txt .= "......................................" . "\n";
            $txt .= "\n\n";
            Storage::disk('local')->append("SystemLogs/" . $txtName, $txt);
        }
    }

    protected function checkVersionNumbering()
    {
        $pass = env("VERSION_NUMBER");
        $file = fopen(app_path()."/version.bat","r");
        $hash = fread($file, filesize(app_path() . "/version.bat"));
        fclose($file);
        if(!password_verify($pass, $hash))
        {
            echo "Version Number is not same, Checksum Failed";
            die;
        }
    }
}
