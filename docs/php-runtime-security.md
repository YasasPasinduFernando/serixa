# PHP runtime security — 31 October 2026

The PHP security team reported CVE-2026-109618 and CVE-2026-109619 on 21 October 2026. They affect PHP serving web requests and can execute before application code. Updating Serixa or a Composer dependency does not patch PHP.

Official source: https://www.php.net/archive/2026.php#2026-10-21-5

Upstream fixed releases: PHP 8.2.35, 8.3.36, 8.4.27 and 8.5.12. Use the latest maintained patch release on your supported branch. PHP 8.2 reaches upstream end of support on 31 December 2026. For distribution backports, verify the installed package against the vendor advisory rather than relying solely on the PHP version string.

## Deployment verification

1. Inventory every web runtime (FPM pools, Apache modules, containers and workers), not just CLI PHP. Record the package/image version and vendor advisory evidence.
2. Upgrade through the hosting provider, OS package manager or container image build and restart all affected workers. Test requests after restart. Do not expose a public phpinfo page.
3. Set `session.upload_progress.enabled=0` in php.ini or server/FPM configuration before request parsing, then restart workers. PHP's 23 October advisory recommends this pending the regular fix for CVE-2026-115560; it does not replace upgrading PHP. Do not depend on application ini_set or FPM .user.ini for this mitigation.
4. Windows deployments need the updated -1 builds (Brotli 1.2.1) documented on 26 October; the runtime version string does not identify that rebuild.
5. Run the framework QA and application smoke tests against the patched runtime. Record runtime and image/package evidence in the release notes.

The framework's PHP ^8.2 language constraint expresses API compatibility, not security certification. Existing Composer installations and PHP services still require operational patching. Static HTML exported by website/bin/export-static.php does not run PHP on the hosting edge, but the build environment still needs maintained PHP.

No production runtime has been inventoried or upgraded by adding this document. Do not mark the deployment fixed until the actual hosting evidence is available.
