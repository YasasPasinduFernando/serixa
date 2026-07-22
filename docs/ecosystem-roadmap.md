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

## Phase 1 — Enterprise (in progress)

| Package | Depends on | Status |
|---------|------------|--------|
| `serixa/rbac` | framework | pending |
| `serixa/permissions` | rbac | pending |
| `serixa/organization` | framework | pending |
| `serixa/teams` | organization | pending |
| `serixa/activity-log` | framework | pending |
| `serixa/audit` | activity-log | pending |

## Phase 2 — Commerce

`cart` → `checkout` → `invoice` → `tax` → `subscriptions`

## Phase 3 — CMS

`cms` → `menu` → `seo` → `sitemap` → `blog`

## Phase 4 — Storage

`local-storage` → `aws-s3` → `cloudinary` → `google-drive` → `dropbox`

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
