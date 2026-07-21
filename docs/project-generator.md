# Project generator

```bash
serixa new blog
serixa new crm
serixa new ecommerce
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

| Preset | Extra page |
|--------|------------|
| blog | `PostsPage` |
| crm | `ContactsPage` |
| ecommerce | `CatalogPage` |

After generation:

```bash
cd blog
composer install
php -S localhost:8000 -t public
```
