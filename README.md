# Newsjet Plugin

Mailjet integration plugin

This plugin implements the Mailjet API with a subscription form functionality for the OctoberCMS.

## Features
+ Add subscribers to the Mailjet Contact list of your choice
+ Support the GDPR: you can add as many checkbox (required or not) as you need
+ Double optin: send your Mailjet template with confirmation link to validate the subscription
+ Multilanguage: compatible with Rainlab.Translate plugin, you can set different messages and Mailjet template for each language

## Configuring

In order to use the plugin you need to get the API keys from your [Mailjet account](https://app.mailjet.com/transactional).

1. In the OctoberCMS back-end go to the Settings page and click the Mailjet Config link. 
2. Fill all the required fields in the Mailjet configuration tab (API key API secret Template Id... )
3. Fill the texts in Subscribe form and Double Optin tabs
4. Create a confirmation page in the CMS and add and configure the `doubleOptin` component to it
5. Add and congigure the `subscribeForm` component in the pages or layout you want.


That's it!

