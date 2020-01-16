<?php

namespace App\Model;

use Illuminate\Support\Facades\Event;

class Repository {
    public function publish($data) {
		// For now, this simply notifies any subscriber to the generic event.
		// In a later iteration, we would differentiate between what types an
		// observer is subscribed to, and only notify relevant subscribers
        Event::dispatch('repository.publish', [$data]);
    }
}
