<?php

namespace Lkn\HookNotification\Core\AdminUI\Application\Services;

use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

class VersionUpgradeWarningService
{
    final public static function setLatestVersion(string $version): void
    {
        lkn_hn_config_set(Platforms::MODULE, Settings::LATEST_VERSION, $version);
    }

    final public static function setDismissOnAdminHome(bool $dismiss): void
    {
        lkn_hn_config_set(Platforms::MODULE, Settings::NEW_VERSION_DISMISS_ON_ADMIN_HOME, $dismiss);
    }

    final public static function getNewVersion(): ?string
    {
        return lkn_hn_config(Settings::LATEST_VERSION);
    }

    final public static function getDismissNewVersionAlert(): ?bool
    {
        return lkn_hn_config(Settings::NEW_VERSION_DISMISS_ON_ADMIN_HOME);
    }
}
