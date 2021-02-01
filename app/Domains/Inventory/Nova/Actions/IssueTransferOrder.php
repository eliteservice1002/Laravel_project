<?php

namespace App\Domains\Inventory\Nova\Actions;

use App\Domains\Inventory\States\TransferOrderState\Transitions\DraftToIssued;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;

class IssueTransferOrder extends Action
{
    public $confirmButtonText = 'Issue';

    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $order) {
            /** @var \App\Domains\Inventory\Models\TransferOrder $order */
            try {
                $order->state->transition(new DraftToIssued($order, $fields->get('issue_date')));
            } catch (CouldNotPerformTransition $e) {
            }
        }

        return Action::message('Issued!');
    }

    public function fields(): array
    {
        return [
            Date::make('Issue Date')
                ->default(Carbon::now()),
        ];
    }
}
