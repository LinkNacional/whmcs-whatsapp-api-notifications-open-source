<?php

namespace Lkn\HookNotification\Core\Notification\Domain;

use Exception;
use Lkn\HookNotification\Core\Shared\Infrastructure\Config\Platforms;

final class NotificationTemplate
{
    /**
     * @param  Platforms  $platform
     * @param  string     $lang
     * @param  string     $template
     * @param  null|array $platformPayload Use this to save platform-specific
     *                                parameters.
     */
    public function __construct(
        public ?Platforms $platform,
        public ?string $lang,
        public string $template,
        public ?array $platformPayload = [],
    ) {
    }

    public function getUsedParameterCodes(null|string $template = null)
    {
        $subject = $template ?? $this ->template;

        preg_match_all('/\{\{(.*?)\}\}/', $subject, $matches);

        return $matches[1];
    }

    // Should move this to MetaWhatsAppNotificationTemplate
    public function getParamCodeForPos(
        string $component,
        int $position
    ): ?string {
        if ($component === 'body' || $component === 'header') {
            $componentPayload = $this->platformPayload[$component];

            if (!$componentPayload) {
                return null;
            }

            $param_association = current(
                array_filter(
                    $this->platformPayload[$component],
                    fn(array $param_association): bool =>
                    $param_association['key'] === "$position"
                )
            );

            return $param_association['value'];
        } elseif ($component === 'button') {
            $componentPayload = $this->platformPayload[$component][0]['params'];

            if (!$componentPayload) {
                return null;
            }

            $param_association = current(
                array_filter(
                    $componentPayload,
                    fn(array $param_association): bool =>
                    $param_association['key'] === "$position"
                )
            );

            return $param_association['value'];
        }

        throw new Exception('Not implemented.');
    }
}
