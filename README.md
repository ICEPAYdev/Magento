![ICEPAY](https://camo.githubusercontent.com/49043ebb42bd9b98941d6013761d4aadcd33f14f/68747470733a2f2f6963657061792e636f6d2f6e6c2f77702d636f6e74656e742f7468656d65732f6963657061792f696d616765732f6865616465722f6c6f676f2e737667)

## Payment Module for Magento

All online payment methods for your Magento webshop in one go! Make it possible for customers to pay in your Magento webshop. Download the Magento Advanced webshop module [here](https://github.com/icepay/Magento/releases) and gain access to the most popular national and international online payment methods.

The master branche may not be stable. See the [release list](https://github.com/icepay/Magento/releases) for stable version of this module. Use [Magento Connect](http://www.magentocommerce.com/magento-connect/icepay-payment-advanced.html) for mainstream updates.

## Requirements

Type       | Value
---------- | ------------------
Magento    | 1.5.0.0 - 1.9.2.1

## License

Our module is available under the BSD-2-Clause. See the [LICENSE](https://github.com/icepay/Magento/blob/master/LICENSE) file for more information.

## Contributing

* Fork it
* Create your feature branch (`git checkout -b my-new-feature`)
* Commit your changes (`git commit -am 'Add some feature'`)
* Push to the branch (`git push origin my-new-feature`)
* Create new Pull Request

## Bug report

If you found a repeatable bug, and troubleshooting tips didn't help, then be sure to [search existing issues](https://github.com/icepay/Magento/issues) first. Include steps to consistently reproduce the problem, actual vs. expected results, screenshots, and your Magento and Payment module version number. Disable all extensions to verify the issue is a core bug.

## Changelog

Version      | Release date   | Changes
------------ | -------------- | ------------------------
1.2.10       | xx/xx/2015     | Better performance on the icepay_transactions table with a high record count. (Thank you Igor)<br>Modern deployment: composer.json and modman files added. (Thank you Tim)<br>Multi-stores will no longer create ERR_0007 (Checksum Error) during checkout. (Thank you Tim)
1.2.9        | 18/08/2015     | Compatiblity with CE 1.9.2.1 and the [SUPEE-6285](http://merch.docs.magento.com/ce/user_guide/Magento_Community_Edition_User_Guide.html#magento/patch-releases-2015.html) patch.<br>Removed IPCheck for compatibility with the ICEPAY cloud.
1.2.2        | 13/11/2013     | Initial commit to GitHub on 03/06/2015.
