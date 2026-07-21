# Versioning policy

SERIXA follows [Semantic Versioning 2.0.0](https://semver.org/).

## Package

- Composer: `serixa/framework`
- Tags: `vMAJOR.MINOR.PATCH` (e.g. `v0.5.0`)

## While on `0.x`

- **MINOR** — new features, additive APIs, deprecations allowed.
- **PATCH** — bug fixes, docs, CI; no new public APIs required.
- Breaking changes to **public** APIs require a MINOR bump in `0.x` and a clear CHANGELOG section. Prefer deprecation first.

## From `1.0.0` onward

- **MAJOR** — breaking public API changes.
- **MINOR** — additive features.
- **PATCH** — fixes only.

## What is public?

Public = classes/interfaces/methods in `src/` **without** `@internal`, plus documented fluent widget/document APIs.

`@internal` types (pipeline passes, concerns, HeadElement base, etc.) may change in any release.

## Namespace

Runtime namespace `Serixa\` is public API. See [NAMESPACE.md](NAMESPACE.md) and ADR 0006.
