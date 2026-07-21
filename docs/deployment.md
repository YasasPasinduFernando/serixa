# Deployment

SERIXA apps are plain PHP: a front controller in `public/` that renders HTML and returns it. Anything that can run PHP 8.2+ and serve a folder can host one. This page covers local serving, a real web server, and the CSS story for production.

## The one rule: serve from `public/`

Every generated app puts `index.php`, `app.css`, and `assets/runtime/serixa.js` under `public/`. Your web server's document root must point at that folder — not the app root — or the runtime script and stylesheet will 404.

```text
blog/
  public/        ← document root goes here
    index.php
    app.css
    assets/runtime/serixa.js
  pages/
  layouts/
  vendor/        ← keep this OUTSIDE the document root
```

## Local development: `php -S`

```bash
php -S localhost:8000 -t public
```

`-t public` sets the document root. This is what `composer install` sets up as the `start` script in generated apps:

```bash
composer start
```

Good for development; PHP's built-in server is single-threaded and not meant for production traffic.

## Apache

Point the vhost's `DocumentRoot` at `public/`, and route unmatched requests to `index.php` (SERIXA apps read `$_GET['page']`-style routing, so you generally don't need a catch-all rewrite unless your app uses path-based URLs):

```apache
<VirtualHost *:80>
    ServerName blog.example.com
    DocumentRoot /var/www/blog/public

    <Directory /var/www/blog/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

If you want clean paths (`/articles` instead of `/?page=articles`), add a `public/.htaccess` that rewrites everything to `index.php` and read `$_SERVER['PATH_INFO']` (or similar) in your own routing code — SERIXA has no opinion here, since it ships no router.

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

## nginx

```nginx
server {
    listen 80;
    server_name blog.example.com;
    root /var/www/blog/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
```

Adjust the `fastcgi_pass` socket/port to match your PHP-FPM setup.

## The runtime JS path

`ClientRuntime::register()` writes a `<script defer src="...">` tag pointing at whatever URL you pass it — by convention `/assets/runtime/serixa.js`:

```php
ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');
```

That URL has to resolve to an actual file. `serixa new` copies the shipped script into `public/assets/runtime/serixa.js` automatically. If you move, rename, or proxy your app, make sure that file still exists at the URL you registered — a mismatch is a common cause of a 404 for the runtime script with no visible errors (interactive widgets like `Modal` or `Dropdown` will render but simply not respond to clicks). See [troubleshooting.md](troubleshooting.md#runtime-script-404).

To point at a different path (e.g. serving assets from a CDN or a versioned folder), just register a different URL and copy the file there yourself:

```php
ClientRuntime::register($document->assets(), 'https://cdn.example.com/serixa/0.11.0/serixa.js');
```

The canonical source file is always `Serixa\Runtime\ClientRuntime::scriptPath()` (physically `assets/runtime/serixa.js` in the framework package).

## Tailwind: CDN vs. production CSS

Generated apps register Tailwind via the CDN script for convenience:

```php
$document->assets()->js('https://cdn.tailwindcss.com');
```

This is fine for local development and demos. It is **not** recommended for production:

- It downloads and compiles Tailwind's entire class set in the browser on every page load.
- It depends on an external CDN being available.
- SERIXA's theme (`DefaultTheme`) only decides *which* Tailwind class names to emit — it does not compile CSS. The CDN script (or a real build step) is what turns those class names into actual styles.

For production, replace the CDN script with a compiled stylesheet:

1. Install Tailwind CLI (or PostCSS) in your app, separately from SERIXA: `npm install -D tailwindcss`.
2. Point Tailwind's `content` glob at your PHP files, since utility classes live inside PHP strings, not `.html`/`.jsx`:

   ```js
   // tailwind.config.js
   module.exports = {
     content: ['./pages/**/*.php', './components/**/*.php', './layouts/**/*.php'],
   };
   ```

3. Build once (or on every deploy):

   ```bash
   npx tailwindcss -i ./resources/app.css -o ./public/app.css --minify
   ```

4. Remove the CDN `js()` call and keep only `->css('/app.css')` in your layout's `wrap()`:

   ```php
   $document->assets()->css('/app.css'); // no more cdn.tailwindcss.com
   ```

SERIXA does not ship a bundler or a Tailwind build pipeline — this step is entirely app-owned, same as routing and auth.

## Composer path repositories in production

If your app's `composer.json` uses a local `path` repository (common right after `serixa new`, while developing next to a framework clone), switch to a real version constraint before deploying:

```json
{
    "require": {
        "serixa/framework": "^0.11"
    }
}
```

and remove the `repositories` block. Path repositories with `"symlink": true` are a local development convenience; they don't make sense once the app is deployed independently. See [Path-repository recursion](troubleshooting.md#path-repository-recursion) for the related monorepo warning.

## Checklist before you deploy

- [ ] Document root points at `public/`, not the app root.
- [ ] `public/assets/runtime/serixa.js` exists and matches the URL passed to `ClientRuntime::register()`.
- [ ] Tailwind CDN script replaced with a compiled `app.css` (or accepted deliberately for a low-traffic demo).
- [ ] `composer.json` uses a version constraint for `serixa/framework`, not a local `path` repository.
- [ ] `composer install --no-dev --optimize-autoloader` run on the deployment target.
