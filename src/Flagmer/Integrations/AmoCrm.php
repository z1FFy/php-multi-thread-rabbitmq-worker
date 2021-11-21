<?php


namespace App\Flagmer\Integrations;


use App\Flagmer\Integrations\Amocrm\sendLeadDto;

class AmoCrm
{
    public function sendLeadAction(sendLeadDto $lead): void
    {
        echo "Sending lead {$lead->lead_id} to AmoCRM...";
        sleep(random_int(1,3));
        echo "Done\n";
    }
}