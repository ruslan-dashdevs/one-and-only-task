.PHONY: install
install : ; \
    composer install

.PHONY: lint
lint : phpcs phpstan

.PHONY: phpcs
phpcs : ; \
    vendor/bin/phpcs

.PHONY: phpcsf
phpcsf: ; \
	vendor/bin/php-cs-fixer fix src

.PHONY: phpstan
phpstan : ; \
	vendor/bin/phpstan
