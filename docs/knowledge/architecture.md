# Architecture and ownership

Serixa is a Flutter-inspired PHP UI/widget engine producing server-rendered semantic HTML. It is not a replacement for a full-stack MVC framework. Preserve public widget APIs and progressive enhancement.

- `framework/src/Widget`, `Tree`, `Rendering`, `Pipeline`: widget representation and rendering. Follow the existing tests before changing rendering order or escaping.
- `framework/src/Document`, `Assets`, `Theme`, `Styling`, `Token`: document metadata, resources and styling. Read actual public methods rather than inventing SEO helper signatures.
- `framework/src/State`, `Event`, `Runtime`, `Component`: interactive behavior and lifecycle.
- `packages/`: separately named Composer ecosystem packages. Presence in the monorepo does not prove public availability or production-ready cloud integrations; inspect each implementation and tests.
- `starters/`: consumer scaffolds. Check path repositories and development constraints before shipping outside the monorepo.
- `website/`: a Serixa docs application with PHP rendering and a static export option.
- `tools/`: shared build, benchmark and skeleton validation utilities.

Root `composer.json` is the development meta-project `serixa/serixa`; published `serixa/framework` is defined by `framework/composer.json`. Consumers must receive framework contents at repository root. Framework QA is PHPUnit, PHPStan and PHP-CS-Fixer. Root package/starter checks validate skeletons, not complete integration behavior.

Read `docs/API_STABILITY.md`, existing ADRs, `framework/CHANGELOG.md`, and the affected source/tests for specific changes. Roadmaps describe intent; source establishes implementation. Nested package `.github/workflows` do not execute as root GitHub Actions workflows.
