<?php

namespace Lkn\HookNotification\Core\AdminUI\Application\Services;

use Lkn\HookNotification\Core\Notification\Application\NotificationFactory;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;
use Lkn\HookNotification\Core\Shared\Infrastructure\Result;
use Lkn\HookNotification\Core\Shared\Infrastructure\Singleton;

require_once __DIR__ . '/license_func.php';

final class LicenseService extends Singleton
{
    private readonly ?string $license;
    private const MAX_NOTIFICATION_ON_FREE_PLAN = 3;

    private ?Result $licenseStatusCache          = null;
    private ?bool $blockNotificationEditCache    = null;
    private ?bool $blockNotificationSendingCache = null;
    private ?bool $blockProFeaturesCache         = null;


    protected function __construct()
    {
        $this->license = lkn_hn_config(Settings::LKN_LICENSE);
    }

    public function hasLicense(): bool
    {
        return !empty($this->license);
    }

    public function mustBlockNotificationEdit(): bool
    {
        if ($this->blockNotificationEditCache !== null) {
            return $this->blockNotificationEditCache;
        }

        if ($this->isLicenseActive()->code === 'yes') {
            return $this->blockNotificationEditCache = false;
        }

        $enabledNotifications = NotificationFactory::getInstance()->makeEnabledNotifs();

        return $this->blockNotificationEditCache = count($enabledNotifications) >= self::MAX_NOTIFICATION_ON_FREE_PLAN;
    }

    public function mustBlockNotificationSending(): bool
    {
        return $this->isLicenseActive()->code !== 'yes'
            && count(
                NotificationFactory::getInstance()->makeEnabledNotifs()
            ) > self::MAX_NOTIFICATION_ON_FREE_PLAN;
    }

    public function mustBlockProFeatures(): bool
    {
        return $this->isLicenseActive()->code !== 'yes';
    }

    public function isLicenseActive(): Result
    {
        return $this->licenseStatusCache = lkn_hn_result(code: 'yes');
    }
}
