<?php

return [
    // Which column will be used as the order column.
    'order_column_name' => 'sort_order',

    /*
     * Define if the models should sort when creating.
     * When true, the package will automatically assign the highest order number to a new mode
     */
    'sort_when_creating' => true,

    // Add sort on has many in all the models.
    'sort_on_has_many' => true,
];
