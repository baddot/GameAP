<?php

//--------------------------------------------------------------------
// Collect our options
//--------------------------------------------------------------------

$log_user_string            = isset($log_user) && $log_user ? 'true' : 'false';
$set_created_string         = isset($set_created) && $set_created ? 'true' : 'false';
$set_modified_string        = isset($set_modified) && $set_modified ? 'true' : 'false';
$use_soft_deletes_string    = isset($use_soft_deletes) && $use_soft_deletes ? 'true' : 'false';
$return_insert_id_string    = isset($return_insert_id) && $return_insert_id ? 'true' : 'false';

//--------------------------------------------------------------------
// Build our Fields
//--------------------------------------------------------------------

$fields = "protected \$table_name      = '{$table_name}';
    protected \$primary_key     = '{$primary_key}';

    /* Auto-Date support */
    protected \$set_created     = {$set_created_string};
    protected \$set_modified    = {$set_modified_string};
    protected \$created_field   = '{$created_field}';
    protected \$modified_field  = '{$modified_field}';
    protected \$date_format     = '{$date_format}';

    /* Soft Deletes */
    protected \$soft_deletes    = {$use_soft_deletes_string};
    protected \$soft_delete_key = '{$soft_delete_key}';

    /* User Logging */
    protected \$log_user            = FALSE;
    protected \$created_by_field    = 'created_by';
    protected \$modified_by_field   = 'modified_by';
    protected \$deleted_by_field    = 'deleted_by';
";

$protect = "'". implode("', '", $protected) . "'";

//--------------------------------------------------------------------
// Create the full model
//--------------------------------------------------------------------

echo "<?php

use Myth\Models\CIDbModel;

/**
 * {$model_name}
 *
 * Auto-generated by Sprint on {$today}
 */
class {$model_name} extends CIDbModel {

    {$fields}

    /**
     * Various callbacks available to the class. They are simple lists of
     * method names (methods will be ran on \$this).
     */
    protected \$before_insert   = array();
    protected \$after_insert    = array();
    protected \$before_update   = array();
    protected \$after_update    = array();
    protected \$before_find     = array();
    protected \$after_find      = array();
    protected \$before_delete   = array();
    protected \$after_delete    = array();

    /**
     * Protected, non-modifiable attributes
     */
    protected \$protected_attributes = [{$protect}];

    /**
     * By default, we return items as objects. You can change this for the
     * entire class by setting this value to 'array' instead of 'object'.
     * Alternatively, you can do it on a per-instance basis using the
     * 'as_array()' and 'as_object()' methods.
     */
    protected \$return_type = 'object';

    /*
        If TRUE, inserts will return the last_insert_id. However,
        this can potentially slow down large imports drastically
        so you can turn it off with the return_insert_id(false) method.
     */
    protected \$return_insert_id = true;

    /**
     * @var Array List of fields in the table.
     *
     * This can be set to avoid a database call if using \$this->prep_data()
     * and/or \$this->get_field_info().
     *
     * Should be in the format: ['field1', 'field2', ...]
     */
    protected \$fields = [];

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     */
    protected \$validation_rules = {$rules};

    /**
     * An array of extra rules to add to validation rules during inserts only.
     * Often used for adding 'required' rules to fields on insert, but not updates.
     *
     *   array( 'username' => 'required|strip_tags' );
     * @var array
     */
    protected \$insert_validate_rules = [];

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------

}";