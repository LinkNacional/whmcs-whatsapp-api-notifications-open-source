<?php

namespace Lkn\HookNotification\Custom\HooksData\Factories;

use Lkn\HookNotification\Custom\HooksData\QuoteClientRegistered;

final class QuoteChangedRegisteredFactory
{
    /**
     * You should call this method in the add_hook callback.
     *
     * @since 1.0.0
     *
     * @param array $raw raw array coming from the callback provided to add_hook().
     *
     * @return \Lkn\HookNotification\Custom\HooksData\QuoteClientRegistered
     */
    public static function fromHook(array $vars): QuoteClientRegistered
    {
        $quoteId = (int) $vars['quoteid'];
        $quoteStatus = (string) $vars['status'];

        $quoteInfo = localAPI('GetQuotes', ['quoteid' => $quoteId]);

        $quote = $quoteInfo['quotes']->{['quote'][0]}[0];

        $quoteSubject = $quote->{['subject'][0]};
        $clientId = $quote->{['userid'][0]};
        $quoteEmail = $quote->{['email'][0]};

        return new QuoteClientRegistered(
            $vars,
            $quoteId,
            $quoteStatus,
            $quoteSubject,
            $clientId,
            $quoteEmail,
        );
    }
}
