<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\ExceededEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ArchiveExpiredEvents extends Command
{
    protected $signature = 'events:archive-expired';
    protected $description = 'Archive events whose endDate is in the past by copying to exceeded_events and marking archived_at';

    public function handle(): int
    {
        $now = Carbon::now();
        $count = 0;
        DB::transaction(function () use ($now, &$count) {
            $expired = Event::whereNull('archived_at')
                ->where('endDate', '<', $now)
                ->get();
            foreach ($expired as $event) {
                ExceededEvent::updateOrCreate(
                    ['original_event_id' => $event->id],
                    [
                        'ownerId' => $event->ownerId,
                        'eventName' => $event->eventName,
                        'location' => $event->location,
                        'startDate' => $event->startDate,
                        'endDate' => $event->endDate,
                        'description' => $event->description,
                        'participantLimit' => $event->participantLimit,
                        'archived_at' => $now,
                    ]
                );
                $event->archived_at = $now;
                $event->save();
                $count++;
            }
        });
        $this->info("Archived {$count} events.");
        return self::SUCCESS;
    }
}


