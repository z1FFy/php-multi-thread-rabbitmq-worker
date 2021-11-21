<?php

namespace App\Task;

use App\Flagmer\Billing\Account;
use App\Flagmer\Integrations\AmoCrm;
use App\Flagmer\Integrations\Amocrm\sendLeadDto;

class TaskHandler
{

    public function handle($task)
    {
        $mapper = new \JsonMapper();
        $taskDto = new TaskDto();

        $mapper->map(json_decode($task), $taskDto);

        if ($taskDto->category === 'account' && $taskDto->task === 'processPayment') {
            $account = new Account();
            $processPaymentDto = new Account\processPaymentDto();
            $processPaymentDto->account_id = $taskDto->data->account_id;
            $processPaymentDto->amount = $taskDto->data->amount;

            $account->processPaymentAction($processPaymentDto);
        }

        if ($taskDto->category === 'amocrm' && $taskDto->task === 'sendLead') {
            $sendLeadDto = new sendLeadDto();
            $sendLeadDto->lead_id = $taskDto->data->lead_id;

            $amoCrm = new AmoCrm();
            $amoCrm->sendLeadAction($sendLeadDto);
        }

    }

}