# SERIXA Ecosystem Roadmap

Governing product plan for growing SERIXA as a complete ecosystem after framework **v1.0.0**.

Framework development is **API frozen** (additive-only). New work is packages, starters, and CLI templates — not framework redesign.

## Rules

1. Build in phase order only.
2. Never implement an adapter before its base package exists.
3. Every package: README, CHANGELOG, LICENSE, examples, docs, tests, PHPStan, CS Fixer, QA scripts, Packagist metadata.
4. Every package: commit → local tag → release report. Do not push or publish unless asked.
5. Awkward framework APIs → document in `docs/framework-review.md`. Do not silently patch.
6. After each **phase**: phase report → wait for confirmation → next phase.

## Completed foundation

- `serixa/framework` v1.0.0
- Packages: admin, auth-ui, forms, icons, charts, calendar, cli, devtools
- Starters: admin, blog, crm (full); docs, ecommerce, portfolio (scaffolds)

## Phase 1 — Enterprise (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/rbac` | framework | **0.1.0** (`serixa-rbac-v0.1.0`) |
| `serixa/permissions` | rbac | **0.1.0** (`serixa-permissions-v0.1.0`) |
| `serixa/organization` | framework | **0.1.0** (`serixa-organization-v0.1.0`) |
| `serixa/teams` | organization | **0.1.0** (`serixa-teams-v0.1.0`) |
| `serixa/activity-log` | framework | **0.1.0** (`serixa-activity-log-v0.1.0`) |
| `serixa/audit` | activity-log | **0.1.0** (`serixa-audit-v0.1.0`) |

Phase tag: `phase-1-enterprise-v0.1.0`. Report: [phase-1-enterprise.md](releases/phase-1-enterprise.md).

## Phase 2 — Commerce (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/cart` | framework | **0.1.0** (`serixa-cart-v0.1.0`) |
| `serixa/checkout` | cart | **0.1.0** (`serixa-checkout-v0.1.0`) |
| `serixa/invoice` | framework | **0.1.0** (`serixa-invoice-v0.1.0`) |
| `serixa/tax` | framework | **0.1.0** (`serixa-tax-v0.1.0`) |
| `serixa/subscriptions` | framework | **0.1.0** (`serixa-subscriptions-v0.1.0`) |

Phase tag: `phase-2-commerce-v0.1.0`. Report: [phase-2-commerce.md](releases/phase-2-commerce.md).

## Phase 3 — CMS (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/cms` | framework | **0.1.0** (`serixa-cms-v0.1.0`) |
| `serixa/menu` | framework | **0.1.0** (`serixa-menu-v0.1.0`) |
| `serixa/seo` | framework | **0.1.0** (`serixa-seo-v0.1.0`) |
| `serixa/sitemap` | framework | **0.1.0** (`serixa-sitemap-v0.1.0`) |
| `serixa/blog` | cms | **0.1.0** (`serixa-blog-v0.1.0`) |

Phase tag: `phase-3-cms-v0.1.0`. Report: [phase-3-cms.md](releases/phase-3-cms.md).

## Phase 4 — Storage (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/local-storage` | framework | **0.1.0** (`serixa-local-storage-v0.1.0`) |
| `serixa/aws-s3` | local-storage | **0.1.0** (`serixa-aws-s3-v0.1.0`) |
| `serixa/cloudinary` | local-storage | **0.1.0** (`serixa-cloudinary-v0.1.0`) |
| `serixa/google-drive` | local-storage | **0.1.0** (`serixa-google-drive-v0.1.0`) |
| `serixa/dropbox` | local-storage | **0.1.0** (`serixa-dropbox-v0.1.0`) |

Phase tag: `phase-4-storage-v0.1.0`. Report: [phase-4-storage.md](releases/phase-4-storage.md).

## Phase 5 — Files (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/media-library` | local-storage | **0.1.0** (`serixa-media-library-v0.1.0`) |
| `serixa/file-manager` | local-storage, media-library | **0.1.0** (`serixa-file-manager-v0.1.0`) |
| `serixa/pdf` | framework | **0.1.0** (`serixa-pdf-v0.1.0`) |
| `serixa/excel` | framework | **0.1.0** (`serixa-excel-v0.1.0`) |
| `serixa/word` | framework | **0.1.0** (`serixa-word-v0.1.0`) |
| `serixa/zip` | framework | **0.1.0** (`serixa-zip-v0.1.0`) |

Phase tag: `phase-5-files-v0.1.0`. Report: [phase-5-files.md](releases/phase-5-files.md).

## Phase 6 — Security (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/passkeys` | framework | **0.1.0** (`serixa-passkeys-v0.1.0`) |
| `serixa/webauthn` | passkeys | **0.1.0** (`serixa-webauthn-v0.1.0`) |
| `serixa/two-factor` | framework | **0.1.0** (`serixa-two-factor-v0.1.0`) |
| `serixa/recaptcha` | framework | **0.1.0** (`serixa-recaptcha-v0.1.0`) |
| `serixa/hcaptcha` | framework | **0.1.0** (`serixa-hcaptcha-v0.1.0`) |

Phase tag: `phase-6-security-v0.1.0`. Report: [phase-6-security.md](releases/phase-6-security.md).

## Phase 7 — Search (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/meilisearch` | framework | **0.1.0** (`serixa-meilisearch-v0.1.0`) |
| `serixa/algolia` | meilisearch | **0.1.0** (`serixa-algolia-v0.1.0`) |
| `serixa/typesense` | meilisearch | **0.1.0** (`serixa-typesense-v0.1.0`) |
| `serixa/elasticsearch` | meilisearch | **0.1.0** (`serixa-elasticsearch-v0.1.0`) |

Phase tag: `phase-7-search-v0.1.0`. Report: [phase-7-search.md](releases/phase-7-search.md).

## Phase 8 — AI (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/openai` | framework | **0.1.0** (`serixa-openai-v0.1.0`) |
| `serixa/gemini` | openai | **0.1.0** (`serixa-gemini-v0.1.0`) |
| `serixa/anthropic` | openai | **0.1.0** (`serixa-anthropic-v0.1.0`) |
| `serixa/deepseek` | openai | **0.1.0** (`serixa-deepseek-v0.1.0`) |
| `serixa/ollama` | openai | **0.1.0** (`serixa-ollama-v0.1.0`) |

Phase tag: `phase-8-ai-v0.1.0`. Report: [phase-8-ai.md](releases/phase-8-ai.md).

## Phase 9 — Notifications (complete)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/firebase` | framework | **0.1.0** (`serixa-firebase-v0.1.0`) |
| `serixa/onesignal` | firebase | **0.1.0** (`serixa-onesignal-v0.1.0`) |
| `serixa/twilio` | firebase | **0.1.0** (`serixa-twilio-v0.1.0`) |
| `serixa/slack` | firebase | **0.1.0** (`serixa-slack-v0.1.0`) |
| `serixa/discord` | firebase | **0.1.0** (`serixa-discord-v0.1.0`) |
| `serixa/telegram` | firebase | **0.1.0** (`serixa-telegram-v0.1.0`) |
| `serixa/whatsapp` | firebase | **0.1.0** (`serixa-whatsapp-v0.1.0`) |

Phase tag: `phase-9-notifications-v0.1.0`. Report: [phase-9-notifications.md](releases/phase-9-notifications.md).

## Phase 10 — Integrations (complete)

### Calendar

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/google-calendar` | framework | **0.1.0** (`serixa-google-calendar-v0.1.0`) |
| `serixa/outlook-calendar` | google-calendar | **0.1.0** (`serixa-outlook-calendar-v0.1.0`) |
| `serixa/ical` | google-calendar | **0.1.0** (`serixa-ical-v0.1.0`) |
| `serixa/caldav` | google-calendar | **0.1.0** (`serixa-caldav-v0.1.0`) |

### OAuth

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/google-oauth` | framework | **0.1.0** (`serixa-google-oauth-v0.1.0`) |
| `serixa/github-oauth` | google-oauth | **0.1.0** (`serixa-github-oauth-v0.1.0`) |
| `serixa/facebook-oauth` | google-oauth | **0.1.0** (`serixa-facebook-oauth-v0.1.0`) |
| `serixa/microsoft-oauth` | google-oauth | **0.1.0** (`serixa-microsoft-oauth-v0.1.0`) |

### Payments

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/stripe` | checkout | **0.1.0** (`serixa-stripe-v0.1.0`) |
| `serixa/paypal` | stripe, checkout | **0.1.0** (`serixa-paypal-v0.1.0`) |
| `serixa/payhere` | stripe, checkout | **0.1.0** (`serixa-payhere-v0.1.0`) |
| `serixa/koko` | stripe, checkout | **0.1.0** (`serixa-koko-v0.1.0`) |

Phase tag: `phase-10-integrations-v0.1.0`. Report: [phase-10-integrations.md](releases/phase-10-integrations.md).

**Next (awaiting confirmation):** Phase 11 — Official CLI Templates.

## Phase 11 — Official CLI Templates

`serixa new admin|blog|ecommerce|crm|cms|saas`

## Phase 12 — Specialized Starters

booking, school, hospital, restaurant, inventory, real-estate, lms, hrm, pos

## Related

- [monorepo.md](monorepo.md)
- [framework-review.md](framework-review.md)
- [API_STABILITY.md](API_STABILITY.md)
