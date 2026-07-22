#!/usr/bin/env python3
from __future__ import annotations

import pathlib
import re

root = pathlib.Path(r"D:/yasas/PersonalProjects/Serixa")
needle = "$document->assets()->js('https://cdn.tailwindcss.com');"
replacement = "StyleSheet::register($document->assets());"
count = 0

paths = list((root / "packages").rglob("*.php"))
paths += list((root / "framework" / "examples").glob("*.php"))

for path in paths:
    if "vendor" in path.parts or "node_modules" in path.parts:
        continue
    text = path.read_text(encoding="utf-8")
    original = text
    if needle in text:
        if "use Serixa\\Assets\\StyleSheet" not in text:
            text = re.sub(
                r"(require [^;]+;)",
                lambda m: m.group(0) + "\n\nuse Serixa\\Assets\\StyleSheet;",
                text,
                count=1,
            )
        text = text.replace(needle, replacement)
    text = text.replace(
        '<script src="https://cdn.tailwindcss.com"></script>',
        '<link rel="stylesheet" href="/assets/app.css">',
    )
    text = text.replace("$document->assets();\n", replacement + "\n")
    while "use Serixa\\Assets\\StyleSheet;\nuse Serixa\\Assets\\StyleSheet;" in text:
        text = text.replace(
            "use Serixa\\Assets\\StyleSheet;\nuse Serixa\\Assets\\StyleSheet;",
            "use Serixa\\Assets\\StyleSheet;",
        )
    if text != original:
        path.write_text(text, encoding="utf-8", newline="\n")
        count += 1
        print(f"fixed {path.relative_to(root)}")

print(f"total {count}")
