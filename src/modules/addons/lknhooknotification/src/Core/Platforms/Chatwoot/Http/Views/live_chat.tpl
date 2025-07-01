{$page_params.messenger_script}

<script type="text/javascript">
    window.addEventListener("chatwoot:ready", function() {
        const client_identifier_hash = '{$page_params.client_identifier_key}';

        window.$chatwoot.setUser(client_identifier_hash, {
            identifier_hash: '{$page_params.identifier_hash}',
            name: '{$page_params.client_details['name']}',
            email: '{$page_params.client_details['email']}',
            phone_number: '{$page_params.client_details['phone_number']}',
            country_code: '{$page_params.client_details['country_code']}',
            {if !empty($page_params.client_details['city'])}
                city: '{$page_params.client_details['city']}',
            {/if}
            company_name: '{$page_params.client_details['company_name']}',
        });

        window.$chatwoot.setCustomAttributes({$page_params.custom_attrs_script});
    })
</script>
