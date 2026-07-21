# Templates

Scaffolding never concatenates PHP source inline. Files live under `packages/cli/templates`:

```text
templates/
  project/base/ …
  project/blog/ …
  project/crm/ …
  project/ecommerce/ …
  page/Page.stub
  component/Component.stub
  layout/Layout.stub
  theme/Theme.stub
  plugin/Plugin.stub
```

## Placeholders

`{{ClassName}}`, `{{Namespace}}`, `{{Name}}`, `{{ProjectName}}`, `{{ProjectTitle}}`, `{{Preset}}`, `{{FrameworkPath}}`

`.stub` files are rendered; other files are copied as-is.

## Community templates (future)

Additional roots can be registered later (Composer packages shipping a `templates/` tree) without changing the render engine.
