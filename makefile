
# -----------------------------------------------------------------------------
# Code Quality
# -----------------------------------------------------------------------------

qa: phplint phpcs phpstan

QA_PATHS = src/
QA_STANDARD = psr12

phplint:
	find $(QA_PATHS) -name "*.php" -print0 | xargs -0 -n1 -P8  php -l > /dev/null

phpstan:
	vendor/bin/phpstan analyse $(QA_PATHS)

phpcs:
	vendor/bin/phpcs --standard=$(QA_STANDARD) $(QA_PATHS)

phpcbf:
	vendor/bin/phpcbf --standard=$(QA_STANDARD) $(QA_PATHS)

todolist:
	git grep -C2 -p -E '[@]todo'

# -----------------------------------------------------------------------------
# Tests
# -----------------------------------------------------------------------------

test:
	vendor/bin/phpunit

.PHONY: coverage
coverage:
	vendor/bin/phpunit --coverage-html coverage
