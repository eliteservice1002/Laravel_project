<?php

namespace App\Domains\Accounting\Models\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self equity()
 * @method static self fixed_asset()
 * @method static self other_asset()
 * @method static self other_current_asset()
 * @method static self cash()
 * @method static self bank()
 * @method static self credit_card()
 * @method static self cost_of_goods_sold()
 * @method static self accounts_receivable()
 * @method static self accounts_payable()
 * @method static self income()
 * @method static self other_income()
 * @method static self expense()
 * @method static self other_expense()
 * @method static self long_term_liability()
 * @method static self other_liability()
 * @method static self other_current_liability()
 */
class AccountingAccountType extends Enum
{
}
