# Security Policy

## Supported versions

| Version | Supported |
|---------|-----------|
| 1.x     | Yes       |
| < 1.0   | Best-effort for critical issues only |

## Reporting a vulnerability

Please **do not** open a public GitHub issue for security vulnerabilities.

Email the maintainers with:

- A clear description of the issue
- Steps to reproduce
- Affected package(s) and version(s) (`serixa/framework`, `serixa/cli`, etc.)
- Impact assessment if known

Use the contact channel listed on the [GitHub repository](https://github.com/YasasPasinduFernando/serixa) (Security Advisories preferred when available).

We aim to acknowledge reports within **72 hours** and ship fixes as promptly as possible for supported versions.

## Scope

In scope: XSS via rendered HTML, CSRF handling in official form helpers, unsafe defaults in Document / Asset APIs, dependency advisories that affect published packages.

Out of scope: application-level auth bugs in example apps, intentional progressive-enhancement trade-offs documented in the accessibility guide.

## PHP runtime advisories

See [PHP runtime security](docs/php-runtime-security.md) for the October 2026 runtime vulnerabilities, fixed versions and deployment verification. Composer updates alone do not patch PHP.
