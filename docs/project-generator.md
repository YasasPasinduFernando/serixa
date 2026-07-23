# Project generator

```bash
serixa new admin
serixa new blog
serixa new ecommerce
serixa new crm
serixa new cms
serixa new saas
serixa new my-app --type=blog
```

## Output layout

```text
{name}/
  public/           # index.php, app.css, assets/runtime/serixa.js
  resources/        # optional source assets
  pages/            # HomePage (+ preset pages)
  components/
  layouts/          # AppLayout
  config/app.php
  composer.json     # path repo → serixa/framework
  README.md
```

Presets add:

| Preset | Extra page | Try |
|--------|------------|-----|
| admin | `DashboardPage` | `?page=dashboard` |
| blog | `PostsPage` | `?page=posts` |
| ecommerce | `CatalogPage` | `?page=catalog` |
| crm | `ContactsPage` | `?page=contacts` |
| cms | `ArticlesPage` | `?page=articles` |
| saas | `BillingPage` | `?page=billing` |

Generated `public/index.php` resolves `?page=` to `App\Pages\{Name}Page` when the class exists.

After generation:

```bash
cd blog
composer install
php -S localhost:8000 -t public
```
