# Scaffolding

Make commands render stubs from `packages/cli/templates` through `Serixa\Cli\Scaffold\TemplateEngine`.

## Rules

1. **PSR-4** namespaces (`App\Pages`, `App\Components`, …)
2. **PHPDoc** on generated classes
3. **No overwrite** — duplicate detection throws / exits `1`
4. **Suffixes** — `Page`, `Layout`, `Theme`, `Plugin` applied when missing

## Working directory

Generators write relative to `getcwd()` (the project root). Run them inside an application created by `serixa new`.
