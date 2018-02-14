![]( https://icepay.com/app/themes/icepay/dist/images/logos/logo_icepay.svg)

## Magento Payment Module

All online payment methods for your Magento webshop in one go! Make it possible for customers to pay in your Magento webshop. Download the Magento Advanced webshop module [here](https://github.com/ICEPAY/Magento/releases) and gain access to the most popular national and international online payment methods.

The master branche may not be stable. See the [release list](https://github.com/ICEPAY/Magento/releases) for stable version of this module. Use [Magento Connect](http://www.magentocommerce.com/magento-connect/icepay-payment-advanced.html) for mainstream updates.

## Requirements

Type       | Value
---------- | ------------------
Magento    | 1.5.0.0 - 1.9.3.2

## License

Our module is available under the BSD-2-Clause. See the [LICENSE](https://github.com/ICEPAY/Magento/blob/master/LICENSE) file for more information.

## Contributing

* Fork it
* Create your feature branch (`git checkout -b my-new-feature`)
* Commit your changes (`git commit -am 'Add some feature'`)
* Push to the branch (`git push origin my-new-feature`)
* Create new Pull Request

## Bug report

If you found a repeatable bug, and troubleshooting tips didn't help, then be sure to [search existing issues](https://github.com/ICEPAY/Magento/issues) first. Include steps to consistently reproduce the problem, actual vs. expected results, screenshots, and your Magento and Payment module version number. Disable all extensions to verify the issue is a core bug.

## Changelog

Version      | Release date   | Changes
------------ | -------------- | ------------------------
1.2.13       | 08/05/2017     | Store-specific Success and Error URLs
1.2.12       | 05/12/2016     | BugFix: ECC-100 - Checksum validation missing
1.2.11       | 06/11/2015     | Compatibility with CE 1.9.2.2 and the [SUPEE-6788](https://magento.com/security/patches/supee-6788) patch. (Thank you Tim)
1.2.10       | xx/xx/2015     | Better performance on the icepay_transactions table with a high record count. (Thank you Igor)<br>Modern deployment: composer.json and modman files added. (Thank you Tim)<br>Multi-stores will no longer create ERR_0007 (Checksum Error) during checkout. (Thank you Tim)
1.2.9        | 18/08/2015     | Compatibility with CE 1.9.2.1 and the [SUPEE-6285](http://merch.docs.magento.com/ce/user_guide/Magento_Community_Edition_User_Guide.html#magento/patch-releases-2015.html) patch.<br>Removed IPCheck for compatibility with the ICEPAY cloud.
1.2.2        | 13/11/2013     | Initial commit to GitHub on 03/06/2015.
