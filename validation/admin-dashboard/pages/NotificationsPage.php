<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Alert;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\SizedBox;
use Serixa\Component\Toast;
use Serixa\Document\Document;

final class NotificationsPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()
                ->title('Notifications')
                ->subtitle('System alerts and toast triggers.')
                ->actions(
                    Button::make('Show toast')
                        ->primary()
                        ->attribute('data-serixa-show-toast', 'admin-notify-toast'),
                ),
            SizedBox::height(2),
            Alert::make()
                ->tone('success')
                ->title('Backup complete')
                ->body('Nightly database backup finished at 02:14 UTC.')
                ->dismissible(),
            Alert::make()
                ->tone('warning')
                ->title('Quota warning')
                ->body('Storage is at 82%. Consider archiving old exports.'),
            Alert::make()
                ->tone('info')
                ->title('Maintenance window')
                ->body('Planned maintenance Saturday 04:00–05:00 UTC.'),
            Alert::make()
                ->tone('danger')
                ->title('Failed login streak')
                ->body('5 failed attempts for admin@example.com from a new IP.'),
            Toast::make()
                ->id('admin-notify-toast')
                ->message('Notification acknowledged')
                ->tone('info')
                ->open(false),
        ])->gap(3)->alignStart();

        return Document::make()
            ->title('Notifications · Admin')
            ->body(AdminLayout::shell('notifications', $content));
    }
}
