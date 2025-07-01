<?php

namespace Lkn\HookNotification\Core\Shared\Infrastructure\Repository;

use Exception;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Settings;

final class SettingsRepository extends BaseRepository
{
    /**
     * @param  array     $newValuesBySetting [setting => value]
     * @param  Platforms $platform
     *
     * @return \Lkn\HookNotification\Core\Shared\Infrastructure\Result
     */
    public function massUpsert(Platforms $platform, array $newValuesBySetting)
    {
        try {
            $upsertStatus = [];

            foreach ($newValuesBySetting as $setting => $value) {
                $result = $this->query->table('mod_lkn_hook_notification_configs')
                    ->updateOrInsert(
                        ['platform' => $platform->value, 'setting' => $setting],
                        ['value' => $value]
                    );

                $upsertStatus[$setting] = $result;
            }

            lkn_hn_log(
                'Update platform settings',
                [
                    'platform' => $platform,
                    'newValuesBySetting' => $newValuesBySetting,
                ],
                ['upsertStatus' => $upsertStatus]
            );

            return lkn_hn_result(
                'success',
                data: ['upsertStatus' => $upsertStatus]
            );
        } catch (Exception $e) {
            return lkn_hn_result(
                'error',
                errors: ['exception' => $e->getMessage()]
            );
        }
    }

    public function getSettingsForPlatform(Platforms $platform): array
    {
        $rawPlatformSettings = $this
            ->query->table('mod_lkn_hook_notification_configs')
            ->where('platform', $platform->value)
            ->get()
            ->toArray();

        return array_column($rawPlatformSettings, 'value', 'setting');
    }

    public function updateSettingsForPlatform(
        Platforms $platform,
        Settings $setting,
        mixed $newValue
    ) {
        $updateResults = $this->query
            ->table('mod_lkn_hook_notification_configs')
            ->where('setting', $setting->value)
            ->when('platform', $platform->value)
            ->update(['value' => $newValue]);

        return $updateResults;
    }
}
