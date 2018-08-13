DIRS:=$(shell for dir in */ ; do echo $$dir | sed 's/\///g'; done)

all: help

.PHONY: $(DIRS)

$(DIRS):
	@if [ -f $@/index.php ]; then \
		echo "<?php\n"; \
		for filename in $@/*.php; do \
		    if [ "$$filename" != "$@/index.php" ]; then \
				sed 's/<?php//g' $$filename; \
			fi; \
		done; \
		sed 's/<?php//g' $@/index.php; \
		echo "\n?>\n"; \
	else \
		echo "Error: No \"$@/index.php\" file found."; \
	fi

help:
	@echo 'Usage: make <folder>'
	@echo ''
	@echo 'Concatenates all *.php files inside specified folder.'
	@echo 'index.php is obligatory. It will be the last one to concat.'
	@echo 'All <?php ?> tags will be removed.'
	@echo ''
