<?php

declare(strict_types=1);

namespace Serixa\DevTools\Runtime;

use Serixa\DevTools\Contracts\PanelInterface;
use Serixa\DevTools\Support\Html;
use Serixa\DevTools\Support\ResolvesDevToolsTheme;
use Serixa\Support\ClassMerger;

/**
 * Renders the fixed DevTools toolbar overlay with tab panels and JSON payload.
 */
final class Overlay
{
    use ResolvesDevToolsTheme;

    private static ClassMerger $merger;

    /**
     * @param array<string, PanelInterface> $panels
     * @param array<string, mixed> $data
     */
    public function render(array $panels, array $data, bool $visible = true): string
    {
        self::$merger ??= new ClassMerger();
        $theme = $this->theme();

        $overlayClasses = self::$merger->toString($theme->classes('devtools.overlay'), []);
        $toolbarClasses = self::$merger->toString($theme->classes('devtools.toolbar'), []);
        $rootClasses = self::$merger->toString($theme->classes('devtools.root'), []);

        $tabs = '';
        $panelHtml = '';
        $first = true;

        foreach ($panels as $panel) {
            $tabClasses = self::$merger->toString(
                $theme->classes('devtools.tab', ['active' => $first]),
                [],
            );
            $tabs .= Html::tag(
                'button',
                sprintf(
                    ' type="button" class="%s" data-devtools-tab="%s" aria-selected="%s"',
                    Html::escape($tabClasses),
                    Html::escape($panel->name()),
                    $first ? 'true' : 'false',
                ),
                Html::escape($panel->label()),
            );

            $panelHtml .= Html::tag(
                'div',
                sprintf(
                    ' data-devtools-panel-content="%s"%s',
                    Html::escape($panel->name()),
                    $first ? '' : ' hidden',
                ),
                $panel->render($data),
            );

            $first = false;
        }

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        $toolbar = Html::tag(
            'div',
            sprintf(' class="%s" role="toolbar" aria-label="SERIXA DevTools tabs"', Html::escape($toolbarClasses)),
            $tabs . Html::tag(
                'span',
                ' class="ml-auto text-xs text-slate-400"',
                Html::escape('Ctrl+Shift+D'),
            ),
        );

        $panelsRegion = Html::tag(
            'div',
            ' class="' . Html::escape(self::$merger->toString($theme->classes('devtools.panel'), [])) . '"',
            $panelHtml,
        );

        $dataScript = Html::tag(
            'script',
            ' type="application/json" id="serixa-devtools-data"',
            $json,
        );

        $hidden = $visible ? '' : ' hidden';

        return Html::tag(
            'aside',
            sprintf(
                ' id="serixa-devtools" class="%s %s"%s role="complementary" aria-label="SERIXA DevTools" data-serixa-devtools="1"',
                Html::escape($overlayClasses),
                Html::escape($rootClasses),
                $hidden,
            ),
            $toolbar . $panelsRegion . $dataScript,
        );
    }
}
