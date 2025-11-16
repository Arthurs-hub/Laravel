# Makefile for Hotel Booking Platform
# Build, test, and deployment automation

.PHONY: help install setup serve test test-unit test-feature clean cache-clear migrate seed build deploy

# Variables
PHP = php
COMPOSER = composer
ARTISAN = $(PHP) artisan
NPM = npm

# Colors for output
GREEN = \033[0;32m
YELLOW = \033[1;33m
RED = \033[0;31m
NC = \033[0m # No Color

# Help - shows available commands
help:
	@echo "$(GREEN)Hotel Booking Platform - Available Commands:$(NC)"
	@echo ""
	@echo "$(YELLOW)Installation & Setup:$(NC)"
	@echo "  make install     - Install dependencies"
	@echo "  make setup       - Complete project setup"
	@echo "  make env         - Create .env file"
	@echo ""
	@echo "$(YELLOW)Development:$(NC)"
	@echo "  make serve       - Start development server"
	@echo "  make migrate     - Run database migrations"
	@echo "  make seed        - Seed database with Google Drive images"
	@echo "  make fresh       - Fresh database with complete data"
	@echo ""
	@echo "$(YELLOW)Testing:$(NC)"
	@echo "  make test        - Run all tests"
	@echo "  make test-unit   - Run unit tests"
	@echo "  make test-feature - Run feature tests"
	@echo "  make test-coverage - Tests with code coverage"
	@echo ""
	@echo "$(YELLOW)Maintenance:$(NC)"
	@echo "  make clean       - Clear cache and temporary files"
	@echo "  make cache-clear - Clear all Laravel caches"
	@echo "  make optimize    - Optimize for production"
	@echo "  make build       - Build frontend assets"
	@echo ""

# Install dependencies
install:
	@echo "$(GREEN)Installing PHP dependencies...$(NC)"
	$(COMPOSER) install --no-dev --optimize-autoloader
	@echo "$(GREEN)Installing Node.js dependencies...$(NC)"
	$(NPM) install
	@echo "$(GREEN)Dependencies installed!$(NC)"

# Install development dependencies
install-dev:
	@echo "$(GREEN)Installing development dependencies...$(NC)"
	$(COMPOSER) install
	$(NPM) install
	@echo "$(GREEN)Development dependencies installed!$(NC)"

# Create .env file
env:
	@if [ ! -f .env ]; then \
		echo "$(GREEN)Creating .env file...$(NC)"; \
		cp .env.example .env; \
		$(ARTISAN) key:generate; \
		echo "$(GREEN).env file created!$(NC)"; \
	else \
		echo "$(YELLOW).env file already exists$(NC)"; \
	fi

# Complete project setup
setup: install-dev env
	@echo "$(GREEN)Setting up project...$(NC)"
	$(ARTISAN) storage:link
	@echo "$(GREEN)Project setup complete! Don't forget to configure database in .env$(NC)"

# Start development server
serve:
	@echo "$(GREEN)Starting development server...$(NC)"
	$(ARTISAN) serve

# Run database migrations
migrate:
	@echo "$(GREEN)Running database migrations...$(NC)"
	$(ARTISAN) migrate

# Seed database with Google Drive images
seed:
	@echo "$(GREEN)Seeding database with Google Drive images...$(NC)"
	$(ARTISAN) app:quick-setup

# Fresh database with complete data
fresh:
	@echo "$(GREEN)Creating fresh database with complete data...$(NC)"
	$(ARTISAN) app:setup-database --fresh

# Run all tests
test:
	@echo "$(GREEN)Running all tests...$(NC)"
	$(ARTISAN) test

# Run unit tests
test-unit:
	@echo "$(GREEN)Running unit tests...$(NC)"
	$(ARTISAN) test --testsuite=Unit

# Run feature tests
test-feature:
	@echo "$(GREEN)Running feature tests...$(NC)"
	$(ARTISAN) test --testsuite=Feature

# Tests with code coverage
test-coverage:
	@echo "$(GREEN)Running tests with coverage analysis...$(NC)"
	$(PHP) -d xdebug.mode=coverage ./vendor/bin/pest --coverage --min=80

# Run specific test file
test-file:
	@read -p "Enter test file path: " file; \
	$(ARTISAN) test $$file

# Clear all caches
cache-clear:
	@echo "$(GREEN)Clearing caches...$(NC)"
	$(ARTISAN) cache:clear
	$(ARTISAN) config:clear
	$(ARTISAN) route:clear
	$(ARTISAN) view:clear
	$(COMPOSER) dump-autoload

# Clean temporary files
clean: cache-clear
	@echo "$(GREEN)Cleaning temporary files...$(NC)"
	rm -rf storage/logs/*.log
	rm -rf storage/framework/cache/data/*
	rm -rf storage/framework/sessions/*
	rm -rf storage/framework/views/*

# Optimize for production
optimize:
	@echo "$(GREEN)Optimizing for production...$(NC)"
	$(COMPOSER) install --no-dev --optimize-autoloader
	$(ARTISAN) config:cache
	$(ARTISAN) route:cache
	$(ARTISAN) view:cache
	$(NPM) run build

# Build frontend assets
build:
	@echo "$(GREEN)Building frontend assets...$(NC)"
	$(NPM) run build

# Build frontend in development mode
build-dev:
	@echo "$(GREEN)Building frontend (development mode)...$(NC)"
	$(NPM) run dev

# Run in watch mode
watch:
	@echo "$(GREEN)Running in watch mode...$(NC)"
	$(NPM) run dev -- --watch

# Check code quality
lint:
	@echo "$(GREEN)Checking code quality...$(NC)"
	./vendor/bin/pint --test

# Fix code style
lint-fix:
	@echo "$(GREEN)Fixing code style...$(NC)"
	./vendor/bin/pint

# Create database backup
backup:
	@echo "$(GREEN)Creating database backup...$(NC)"
	@timestamp=$$(date +%Y%m%d_%H%M%S); \
	$(PHP) artisan db:dump --path=storage/backups/backup_$$timestamp.sql; \
	echo "$(GREEN)Backup created: storage/backups/backup_$$timestamp.sql$(NC)"

# Restore database from backup
restore:
	@read -p "Enter backup file path: " backup_file; \
	if [ -f "$$backup_file" ]; then \
		echo "$(GREEN)Restoring database from $$backup_file...$(NC)"; \
		mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE) < $$backup_file; \
		echo "$(GREEN)Database restored!$(NC)"; \
	else \
		echo "$(RED)File $$backup_file not found!$(NC)"; \
	fi

# Check project status
status:
	@echo "$(GREEN)Project Status:$(NC)"
	@echo "PHP version: $$($(PHP) --version | head -n1)"
	@echo "Composer version: $$($(COMPOSER) --version)"
	@echo "Node.js version: $$(node --version)"
	@echo "NPM version: $$($(NPM) --version)"
	@echo ""
	@echo "$(GREEN)Laravel Information:$(NC)"
	@$(ARTISAN) --version
	@$(ARTISAN) env

# Quick start for new developers
quickstart: setup
	@echo "$(GREEN)Setting up database with Google Drive images...$(NC)"
	$(ARTISAN) app:quick-setup
	@echo "$(GREEN)Quick start completed!$(NC)"
	@echo "$(YELLOW)You can now run: make serve$(NC)"

# Complete project reinstallation
reinstall: clean
	@echo "$(GREEN)Complete project reinstallation...$(NC)"
	rm -rf vendor node_modules
	$(MAKE) setup
	@echo "$(GREEN)Project reinstalled!$(NC)"

# Deploy to production
deploy: optimize
	@echo "$(GREEN)Deploying to production...$(NC)"
	$(ARTISAN) migrate --force
	$(ARTISAN) queue:restart
	@echo "$(GREEN)Deployment completed!$(NC)"