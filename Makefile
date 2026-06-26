# ──────────────────────────────────────────────────────────────────────────────
# LaraCore · Makefile
# ──────────────────────────────────────────────────────────────────────────────
# Usage:
#   make install          — composer install + hook setup
#   make quality          — full pipeline: lint → fix → analyse → test
#   make test             — run all PHPUnit test suites
# ──────────────────────────────────────────────────────────────────────────────

PHP        ?= php
COMPOSER   ?= composer
CS_FIXER   := ./vendor/bin/php-cs-fixer
PHPSTAN    := ./vendor/bin/phpstan
PHPUNIT    := ./vendor/bin/phpunit
CS_CONFIG  := .php-cs-fixer.php
STAN_CONFIG:= phpstan.neon

.DEFAULT_GOAL := help

# ── Meta ───────────────────────────────────────────────────────────────────────

.PHONY: help
help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
	  | sort \
	  | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-22s\033[0m %s\n", $$1, $$2}'

# ── Setup ──────────────────────────────────────────────────────────────────────

.PHONY: install
install: ## Install dependencies and git hooks
	$(COMPOSER) install --no-interaction --prefer-dist --optimize-autoloader
	$(PHP) scripts/install-hooks.php

.PHONY: install-hooks
install-hooks: ## (Re-)install git hooks only
	$(PHP) scripts/install-hooks.php

# ── Code Style ─────────────────────────────────────────────────────────────────

.PHONY: cs-check
cs-check: vendor ## Dry-run CS-Fixer (report only, no changes)
	$(CS_FIXER) fix \
	  --config=$(CS_CONFIG) \
	  --dry-run \
	  --diff \
	  --ansi \
	  --allow-risky=yes \
	  --show-progress=dots \
	  -v

.PHONY: fix
fix: vendor ## Auto-fix code style with PHP-CS-Fixer
	$(CS_FIXER) fix \
	  --config=$(CS_CONFIG) \
	  --allow-risky=yes \
	  --ansi \
	  --show-progress=dots \
	  -v; \
	EXIT=$$?; \
	if [ $$EXIT -eq 0 ] || [ $$EXIT -eq 8 ]; then exit 0; else exit $$EXIT; fi

.PHONY: lint
lint: ## Check PHP syntax for all source files
	@ERRORS=0; \
	for f in $$(find . -name "*.php" \
	    -not -path "./vendor/*" \
	    -not -path "./resources/*"); do \
	  if ! $(PHP) -l "$$f" > /dev/null 2>&1; then \
	    $(PHP) -l "$$f"; \
	    ERRORS=$$((ERRORS + 1)); \
	  fi; \
	done; \
	if [ $$ERRORS -gt 0 ]; then \
	  echo "$$ERRORS file(s) have parse errors."; exit 1; \
	else \
	  echo "All PHP files OK."; \
	fi

# ── Static Analysis ────────────────────────────────────────────────────────────

.PHONY: analyse
analyse: vendor ## Run PHPStan at level 5
	$(PHPSTAN) analyse \
	  --configuration=$(STAN_CONFIG) \
	  --error-format=table \
	  --ansi

.PHONY: analyse-baseline
analyse-baseline: vendor ## Regenerate PHPStan baseline (accept current errors)
	$(PHPSTAN) analyse \
	  --configuration=$(STAN_CONFIG) \
	  --generate-baseline=phpstan-baseline.neon \
	  --ansi
	@echo "Baseline written to phpstan-baseline.neon"

# ── Tests ──────────────────────────────────────────────────────────────────────

.PHONY: test
test: vendor ## Run all PHPUnit test suites
	APP_ENV=testing $(PHPUNIT) --testdox --colors=always

.PHONY: test-unit
test-unit: vendor ## Run Unit test suite only
	$(PHPUNIT) --testsuite Unit --testdox --colors=always

.PHONY: test-integration
test-integration: vendor ## Run Integration test suite only
	APP_ENV=testing $(PHPUNIT) --testsuite Integration --testdox --colors=always

.PHONY: test-feature
test-feature: vendor ## Run Feature test suite only
	APP_ENV=testing $(PHPUNIT) --testsuite Feature --testdox --colors=always

# ── Combined Quality Gate ──────────────────────────────────────────────────────

.PHONY: quality
quality: lint fix analyse test ## Full quality pipeline (same as pre-push hook)
	@echo ""
	@echo "\033[32m✔  All quality checks passed.\033[0m"

# ── Docker Shortcuts ───────────────────────────────────────────────────────────

.PHONY: docker-up
docker-up: ## Start the app containers
	docker compose up -d

.PHONY: docker-down
docker-down: ## Stop and remove app containers
	docker compose down

.PHONY: docker-test
docker-test: ## Run PHPUnit inside Docker
	docker compose run --rm test

.PHONY: docker-build
docker-build: ## Rebuild Docker images
	docker compose build

# ── Internal ───────────────────────────────────────────────────────────────────

vendor: composer.json composer.lock
	$(COMPOSER) install --no-interaction --prefer-dist --quiet
	@touch vendor
