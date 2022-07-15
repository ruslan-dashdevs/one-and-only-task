.PHONY: install
install : ; \
    composer install

.PHONY: lint
lint : phpcs

.PHONY: phpcs
phpcs : ; \
    vendor/bin/phpcs
