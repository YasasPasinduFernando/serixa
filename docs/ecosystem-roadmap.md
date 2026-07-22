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

**Next (awaiting confirmation):** Phase 4 — Storage.

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

## Phase 5 — Files

`media-library` → `file-manager` → `pdf` → `excel` → `word` → `zip`

## Phase 6 — Security

`passkeys` → `webauthn` → `two-factor` → `recaptcha` → `hcaptcha`

## Phase 7 — Search

`meilisearch` → `algolia` → `typesense` → `elasticsearch`

## Phase 8 — AI

`openai` → `gemini` → `anthropic` → `deepseek` → `ollama`

## Phase 9 — Notifications

`firebase` → `onesignal` → `twilio` → `slack` → `discord` → `telegram` → `whatsapp`

## Phase 10 — Integrations

Calendar: `google-calendar` → `outlook-calendar` → `ical` → `caldav`  
OAuth: `google-oauth` → `github-oauth` → `facebook-oauth` → `microsoft-oauth`  
Payments: `stripe` → `paypal` → `payhere` → `koko`  
(Requires Phase 2 checkout before payment adapters.)

## Phase 11 — Official CLI Templates

`serixa new admin|blog|ecommerce|crm|cms|saas`

## Phase 12 — Specialized Starters

booking, school, hospital, restaurant, inventory, real-estate, lms, hrm, pos

## Related

- [monorepo.md](monorepo.md)
- [framework-review.md](framework-review.md)
- [API_STABILITY.md](API_STABILITY.md)
