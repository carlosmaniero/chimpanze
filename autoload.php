<?php
require_once 'vendor/autoload.php';
require_once 'settings.php';
require_once 'core/database.php';
require_once 'core/queryset.php';
require_once 'core/models.php';
require_once 'core/widgets.php';
require_once 'models/email.php';

$widgets_registration = new WidgetRegistration();

require_once 'widgets/text.php';
