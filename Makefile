PHP = `which php`
all: init
init:
	@$(PHP) init.php
restore:
	@$(PHP) restore.php
