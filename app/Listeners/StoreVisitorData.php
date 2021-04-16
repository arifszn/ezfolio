<?php

namespace App\Listeners;

use App\Events\FrontendVisited;
use App\Services\Contracts\VisitorInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class StoreVisitorData
{
    private $visitor;

    /**
     * Create the event listener.
     *
     * @param VisitorInterface $visitor
     * @return void
     */
    public function __construct(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Handle the event.
     *
     * @param  FrontendVisited  $event
     * @return void
     */
    public function handle(FrontendVisited $event)
    {
        try {
            $this->visitor->store($event->data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
