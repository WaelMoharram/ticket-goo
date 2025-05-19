<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Event;
use App\Models\Team;
use App\Models\Stadium;
use App\Models\League;

class FetchFootballEvents extends Command
{
    protected $signature = 'events:fetch';
    protected $description = 'Fetch football events from FootballTicketNet API and store them';

    public function handle()
    {
        $this->info('Fetching events from FootballTicketNet API...');

        $user = env('FOOTBALL_TICKET_NET_USER');
        $action = 'list_events';
        $timestamp = time();
        $apiKey = env('FOOTBALL_TICKET_NET_KEY');

        $signatureString = "{$user}-{$action}-{$timestamp}-{$apiKey}";
        $signature = hash('sha256', $signatureString);

        $url = 'https://www.footballticketnet.com/api';

        $response = Http::get($url, [
            'action' => $action,
            'u' => $user,
            'ts' => $timestamp,
            's' => $signature,
            'out' => 'json',
        ]);


        $json = $response->json();

        if ($json === null) {
            $this->error('API response is not valid JSON or empty.');
            $this->line('Raw response body: ' . $response->body());
            return 1;
        }

        if (!isset($json['data']) || !is_array($json['data'])) {
            $this->error('API response does not contain expected "data" key.');
            $this->line('Full response JSON: ' . json_encode($json));
            return 1;
        }

        $events = $json['data'];

        if (empty($events)) {
            $this->warn('No events received.');
            return Command::SUCCESS;
        }

        $this->info("Processing " . count($events) . " events...");
        $bar = $this->output->createProgressBar(count($events));
        $bar->start();

        foreach ($events as $item) {
            $stadium = Stadium::updateOrCreate(
                ['football_ticket_net_id' => $item['venue']['venue_id']],
                [
                    'name' => ['en' => $item['venue']['venue_name']],
                    'city' => ['en' => $item['venue']['venue_city_name']],
                    'country' => ['en' => $item['venue']['venue_country_name']],
                    'image' => $item['venue']['venue_image'] ?? null,
                ]
            );

            $league = League::updateOrCreate(
                ['football_ticket_net_id' => $item['tour']['id']],
                [
                    'name' => ['en' => $item['tour']['name']],
                    'nice_name' => ['en' => $item['tour']['nice_name'] ?? ''],
                    'image' => $item['tour']['image'] ?? null,
                ]
            );

            $team1 = Team::updateOrCreate(
                ['football_ticket_net_id' => $item['team1']['id']],
                [
                    'name' => ['en' => $item['team1']['name']],
                    'nice_name' => ['en' => $item['team1']['t2_nice_name'] ?? ''],
                    'image' => $item['team1']['image'] ?? null,
                ]
            );

            $team2 = Team::updateOrCreate(
                ['football_ticket_net_id' => $item['team2']['id']],
                [
                    'name' => ['en' => $item['team2']['name']],
                    'nice_name' => ['en' => $item['team2']['t2_nice_name'] ?? ''],
                    'image' => $item['team2']['image'] ?? null,
                ]
            );

            Event::updateOrCreate(
                ['football_ticket_net_id' => $item['id']],
                [
                    'name' => ['en' => $item['name']],
                    'date' => $item['date'],
                    'full_date' => $item['fullDate'],
                    'link' => $item['link'],
                    'currency_code' => $item['min_price']['currency_code'] ?? null,
                    'stadium_id' => $stadium->id,
                    'league_id' => $league->id,
                    'team1_id' => $team1->id,
                    'team2_id' => $team2->id,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Events fetched and stored successfully.');
        return Command::SUCCESS;
    }

    /**
     * Call FootballTicketNet API with signature
     */
    private function getFootballEvents(): array
    {
        $user = env('FOOTBALL_TICKET_NET_USER');
        $action = 'list_events';
        $timestamp = time();
        $apiKey = env('FOOTBALL_TICKET_NET_KEY');

        $signatureString = "{$user}-{$action}-{$timestamp}-{$apiKey}";
        $signature = hash('sha256', $signatureString);

        $url = 'https://www.footballticketnet.com/api';

        $response = Http::get($url, [
            'action' => $action,
            'u' => $user,
            'ts' => $timestamp,
            's' => $signature,
            'out' => 'json',
        ]);

        if ($response->successful() && $response->json()) {
            return $response->json();
        }

        return [
            'error' => true,
            'status' => $response->status(),
            'message' => $response->body(),
        ];
    }
}
