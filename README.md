# Newsjet Plugin

Mailjet integration plugin

This plugin implements the Mailjet API with a subscription form functionality for the OctoberCMS.

## Features
+ Add subscribers to the Mailjet Contact list of your choice and **send them your newsletters** from Mailjet
+ Support the **GDPR**: you can add as many checkbox (required or not) as you need
+ **Double optin**: send your Mailjet template with confirmation link to validate the subscription
+ Use your [Mailjet transactional template](https://app.mailjet.com/templates/transactional) for the confirmation link email
+ **Multilanguage**: compatible with [Rainlab.Translate](http://octobercms.com/plugin/rainlab-translate) plugin, you can set different messages and Mailjet template for each language

## Configuring

In order to use the plugin you need to get the API keys from your [Mailjet account](https://app.mailjet.com/transactional).

1. In the OctoberCMS back-end go to the Settings page and click the Mailjet Config link. 
2. Fill all the required fields in the Mailjet configuration tab (API key API secret Template Id... )
3. Fill the texts in Subscribe form and Double Optin tabs
4. Create a confirmation page in the CMS and add and configure the `doubleOptin` component to it, set the page URL with `:vkey` and `:umail` parameters. Ex: /newsletter-optin/:vkey/:umail
5. Add and configure the `subscribeForm` component in the pages or layouts you want.

**You must add the [OctoberCMS Ajax framework](https://octobercms.com/docs/ajax/introduction#framework-script) on the page or layouts in order to make the `subscribeForm` component work.**

Note: in the Subscribe Form settings tab the sender email must be allowed as sender in your [Mailjet account](https://app.mailjet.com/account/sender)

That's it!

