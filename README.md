## Installation

### by Composer

```
composer config repositories.yBuy git https://github.com/yappybuy-org/magento2.git
composer require yappybuy-org/magento2:master

bin/magento setup:upgrade
bin/magento setup:di:compile
```

### manually

copy archive content to folder 
magento_root/app/code/YappyBuy/Checkout

and run
```
bin/magento setup:upgrade
bin/magento setup:di:compile
```


