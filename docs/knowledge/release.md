# Composer synchronization and releases

Packagist `serixa/framework` tracks `YasasPasinduFernando/serixa-framework`, not the monorepo. As checked on 2026-10-31 its published stable version was v1.0.0. Root monorepo tags do not automatically become package releases.

The framework sync workflow copies only tracked `framework/` contents to the package repository's main branch. It preserves destination commit history, creates no tags and never force-pushes. Do not edit the mirror directly: a subsequent sync replaces its tracked content with the framework snapshot. Repository settings are not mirrored.

A push affecting framework files triggers sync; workflow_dispatch supports recovery. The destination-scoped SSH deploy key is stored as the source repository Actions secret `FRAMEWORK_SYNC_SSH_KEY`. No private key belongs in this repository. Missing credentials fail visibly. Concurrent sync runs serialize.

After sync, Packagist can refresh dev-main through its GitHub webhook or scheduled crawl. Confirm the webhook separately; syncing GitHub is not proof of Packagist refresh. Stable releases require an explicitly chosen semver tag in the package repository after QA. Never infer a version bump from a docs or agent-skills commit. Consumers update through Composer within their constraints; existing lockfiles do not update themselves.

Before a stable release: run framework QA, review the changelog and compatibility, inspect the synced commit, create the approved tag on the package repo, then verify Packagist's source reference and install from a clean consumer project. PHP runtime upgrades remain the host operator's responsibility.

Sources: https://packagist.org/packages/serixa/framework and https://packagist.org/about#managing-package-versions
