<?php

namespace Lkn\HookNotification\Custom\HooksData\Factories;

use Lkn\HookNotification\Custom\HooksData\QuoteClientUnregistered;

final class QuoteChangedUnregisteredFactory
{
    /**
     * You should call this method in the add_hook callback.
     *
     * @since 1.0.0
     *
     * @param array $raw raw array coming from the callback provided to add_hook().
     *
     * @return \Lkn\HookNotification\Custom\HooksData\QuoteClientUnregistered
     */
    public static function fromHook(array $vars): QuoteClientUnregistered
    {
        $quoteId = (int) $vars['quoteid'];
        $quoteStatus = (string) $vars['status'];

        $quoteInfo = localAPI('GetQuotes', ['quoteid' => $quoteId]);

        $quote = $quoteInfo['quotes']->{['quote'][0]}[0];

        $quoteSubject = $quote->{['subject'][0]};
        $clientId = $quote->{['userid'][0]};
        $quoteEmail = $quote->{['email'][0]};
        $phoneNumber = preg_replace('/\D/', '', $quote->{['phonenumber'][0]});
        $firstName = $quote->{['firstname'][0]};

        return new QuoteClientUnregistered(
            $vars,
            $quoteId,
            $quoteStatus,
            $quoteSubject,
            $clientId,
            $quoteEmail,
            $phoneNumber,
            $firstName
        );
    }
}
