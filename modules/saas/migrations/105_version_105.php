<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_105 extends App_module_migration
{
    public function up()
    {
        add_option('saas_default_landing_page', 1);
    }
}
