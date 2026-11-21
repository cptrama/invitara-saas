<?php

namespace App\Services;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use Twig\Extension\DebugExtension;

class View
{
    private static ?Environment $twig = null;

    public static function getTwig(): Environment
    {
        if (self::$twig === null) {
            $configPath = dirname(__DIR__, 2) . '/config/twig.php';
            $config = file_exists($configPath) ? require $configPath : [];

            $viewsPath = $config['views_path'] ?? (dirname(__DIR__) . '/Views');
            $cachePath = $config['cache_path'] ?? false;
            $debug = $config['debug'] ?? true;

            $loader = new FilesystemLoader($viewsPath);
            $twig = new Environment($loader, [
                'cache' => $cachePath,
                'debug' => $debug,
                'auto_reload' => true,
            ]);

            if ($debug) {
                $twig->addExtension(new DebugExtension());
            }

            // Register global variables
            $twig->addGlobal('app_name', env('APP_NAME', 'Invitara'));
            $twig->addGlobal('app_url', env('APP_URL', 'http://localhost:8000'));
            
            // Dynamic globals via functions for fresh state
            $twig->addFunction(new TwigFunction('auth_user', function () {
                return AuthService::user();
            }));

            $twig->addFunction(new TwigFunction('is_authenticated', function () {
                return AuthService::check();
            }));

            $twig->addFunction(new TwigFunction('is_admin', function () {
                $u = AuthService::user();
                return $u && ($u['role'] ?? '') === 'admin';
            }));

            $twig->addFunction(new TwigFunction('url', function (string $path = '') {
                return url($path);
            }));

            $twig->addFunction(new TwigFunction('asset', function (string $path) {
                return asset($path);
            }));

            $twig->addFunction(new TwigFunction('csrf_token', function () {
                return CsrfService::getToken();
            }));

            $twig->addFunction(new TwigFunction('csrf_field', function () {
                return CsrfService::renderField();
            }, ['is_safe' => ['html']]));

            $twig->addFunction(new TwigFunction('flash', function (string $type) {
                return Flash::get($type);
            }));

            $twig->addFunction(new TwigFunction('has_flash', function (string $type) {
                return Flash::has($type);
            }));

            $twig->addFunction(new TwigFunction('all_flashes', function () {
                return Flash::all();
            }));

            $twig->addFunction(new TwigFunction('old', function (string $key, $default = '') {
                return old($key, $default);
            }));

            self::$twig = $twig;
        }

        return self::$twig;
    }

    /**
     * Render Twig template and return HTML string
     */
    public static function render(string $template, array $data = []): string
    {
        return self::getTwig()->render($template, $data);
    }
}
