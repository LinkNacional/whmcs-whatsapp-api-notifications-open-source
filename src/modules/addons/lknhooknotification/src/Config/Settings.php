<?php

namespace Lkn\HookNotification\Config;

enum Settings: string
{
    case WP_MSG_TEMPLATE_ASSOCS = 'msg_templates_assoc';
    case WP_MESSAGE_TEMPLATES = 'message-templates';
    case WP_CUSTOM_FIELD_ID = 'custom-field-id';
    case WP_BUSINESS_ACCOUNT_ID = 'business-account-id';
    case WP_PHONE_NUMBER_ID = 'phone-number-id';
    case WP_SEND_TO_CHATWOOT = 'send-to-chatwoot';
    case WP_USER_ACCESS_TOKEN = 'user-access-token';

    case CW_ACCOUNT_ID = 'account-id';
    case CW_URL = 'url';
    case CW_WHATSAPP_INBOX_ID = 'whatsapp-inbox-id';
    case CW_API_ACCESS_TOKEN = 'api-access-token';

    case ENABLE_LOG = 'enable-log';
    case LKN_LICENSE = 'lkn-license';
    case DEFAULT_CLIENT_NAME = 'default-client-name';
    case LATEST_VERSION = 'latest-version';
    case NEW_VERSION_DISMISS_ON_ADMIN_HOME = 'new-version-dismiss-on-admin-home';
}
