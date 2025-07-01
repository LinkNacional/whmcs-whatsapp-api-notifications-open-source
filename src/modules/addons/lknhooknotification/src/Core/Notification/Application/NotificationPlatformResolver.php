<?php

namespace Lkn\HookNotification\Core\Notification\Application;

use Lkn\HookNotification\Core\Notification\Domain\AbstractNotification;
use Lkn\HookNotification\Core\Notification\Domain\NotificationTemplate;
use Lkn\HookNotification\Core\Platforms\Common\Infrastructure\PlatformFactory;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;

final class NotificationPlatformResolver
{
    private PlatformFactory $platformFactory;

    public function __construct()
    {
        $this->platformFactory = new PlatformFactory();
    }

    public function resolve(AbstractNotification $notification)
    {
        $templateForClientLang = current(
            array_filter(
                $notification->templates,
                fn(NotificationTemplate $template) =>
                $template->lang === $notification->client->locale
            )
        );

        // Try to find template for the system default language.
        if (!$templateForClientLang) {
            $systemLocale = lkn_hn_get_system_locale();

            $templateForClientLang = current(
                array_filter(
                    $notification->templates,
                    fn(NotificationTemplate $template) =>
                    $template->lang === $systemLocale
                )
            );
        }

        if (!$templateForClientLang) {
            // Bulk messages
            if (is_null($notification->templates[0]->lang)) {
                $templateForClientLang = $notification->templates[0];

                $platform = $this->platformFactory->make($templateForClientLang->platform);

                return [$platform, $templateForClientLang];
            }

            $result = new Result(
                'no-template-found-for-client-lang',
                msg: 'No template found for client language.'
            );

            lkn_hn_log(
                'Send notification',
                ['whmcsHookParams' => $notification->whmcsHookParams],
                ['resultCode' => $result->code]
            );

            return $result;
        }

        $platform = $this->platformFactory->make($templateForClientLang->platform);

        if (

            isset($platform->platformSettings)
            && isset($platform->platformSettings->enabled)
            && !$platform->platformSettings->enabled
        ) {
            $result = new Result(
                'platform-is-disabled',
                msg: 'The platform is disabled.'
            );

            lkn_hn_log(
                'Platform is disabled',
                ['whmcsHookParams' => $notification->whmcsHookParams],
                ['resultCode' => $result->code]
            );

            return $result;
        }

        return [$platform, $templateForClientLang];
    }
}
