# Serixa agent guidance

## Working agreement
Read this file first. Skills live in `.agents/skills/*/SKILL.md`; read only skills relevant to the task. User instructions take precedence over skill defaults. Existing user authorization remains valid: avoid repeated approval questions. Preserve unrelated local changes and stage explicit paths. Do not force-push or publish releases without task authorization.

Use systematic-debugging for unexplained failures, test-driven-development for behavior changes, and verification-before-completion before reporting success. Skill suggestions to delegate require available tools and task authorization. Report actual commands/results and distinguish local verification from production verification.

Never commit secrets, chat transcripts, environment files, generated dependency trees, or production data. Use current source and migrations as authority when historical handoff notes disagree. Do not install speculative MCP servers or execute downloaded hooks automatically.

## Project skill
Read `.agents/skills/serixa-engineering/SKILL.md` for project work.
